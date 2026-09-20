<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "valida/verifica_acessoAdm.php";
include "funcoes/compara_cadastro_lib.php";

function cmp_h($texto)
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

function cmp_origem($origem)
{
    if ($origem === '') {
        return '<span class="cmp-badge origem-nao">Não encontrado</span>';
    }
    $classe = 'origem-' . $origem;
    return '<span class="cmp-badge ' . cmp_h($classe) . '">' . cmp_h(compara_rotulo_origem($origem)) . '</span>';
}

$arquivoPadrao = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'Pessoas_2026824_1629.csv';
$arquivoUpload = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'comparacao_upload.csv';
$arquivoAtual = $arquivoPadrao;
$msgUpload = '';
if (isset($_GET['ok'])) {
    $msgUpload = 'Arquivo CSV carregado com sucesso.';
}

if (isset($_GET['usar']) && $_GET['usar'] === 'padrao') {
    if (is_file($arquivoUpload)) {
        @unlink($arquivoUpload);
    }
    $arquivoAtual = $arquivoPadrao;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivo_csv']) && is_uploaded_file($_FILES['arquivo_csv']['tmp_name'])) {
    $nomeOriginal = isset($_FILES['arquivo_csv']['name']) ? $_FILES['arquivo_csv']['name'] : '';
    $ext = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
    if ($ext !== 'csv') {
        $msgUpload = 'Envie um arquivo .csv (a primeira linha deve conter as colunas).';
    } else {
        if (!is_dir(dirname($arquivoUpload))) {
            @mkdir(dirname($arquivoUpload), 0777, true);
        }
        if (move_uploaded_file($_FILES['arquivo_csv']['tmp_name'], $arquivoUpload)) {
            header('Location: compara_cadastro.php?filtro=divergente&ok=1');
            exit;
        }
        $msgUpload = 'Falha ao gravar o arquivo enviado.';
    }
} elseif (is_file($arquivoUpload)) {
    $arquivoAtual = $arquivoUpload;
}

include "topo.php";
?>
<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<link rel="stylesheet" href="css/compara_cadastro.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php

$comparacao = compara_executar($arquivoAtual);
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'divergente';
$busca = isset($_GET['q']) ? trim($_GET['q']) : '';
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($pagina < 1) {
    $pagina = 1;
}
$porPagina = 50;

function cmp_passa_busca($texto, $busca)
{
    if ($busca === '') {
        return true;
    }
    return strpos(compara_normalizar_texto($texto), compara_normalizar_texto($busca)) !== false;
}

$linhas = array();
if ($filtro === 'conforme' || $filtro === 'divergente' || $filtro === 'vinculados' || $filtro === 'origem_proprietario' || $filtro === 'origem_dependente') {
    foreach ($comparacao['vinculados'] as $item) {
        if (($filtro === 'conforme' || $filtro === 'divergente') && $item['status'] !== $filtro) {
            continue;
        }
        $blob = $item['cpf'] . ' ' . $item['sistema']['nome'] . ' ' . $item['arquivo']['nome'] . ' ' . $item['arquivo']['matricula'];
        if (!empty($item['dependente'])) {
            $blob .= ' ' . $item['dependente']['nome'] . ' ' . $item['dependente']['proprietario'];
        }
        if (!cmp_passa_busca($blob, $busca)) {
            continue;
        }
        if ($filtro === 'origem_proprietario' && $item['origem'] !== 'proprietario' && $item['origem'] !== 'ambos') {
            continue;
        }
        if ($filtro === 'origem_dependente' && $item['origem'] !== 'dependente' && $item['origem'] !== 'ambos') {
            continue;
        }
        $item['_tipo'] = 'vinculado';
        $linhas[] = $item;
    }
} elseif ($filtro === 'so_arquivo') {
    foreach ($comparacao['so_arquivo'] as $item) {
        $blob = $item['arquivo']['cpf'] . ' ' . $item['arquivo']['nome'] . ' ' . $item['arquivo']['matricula'];
        if (!cmp_passa_busca($blob, $busca)) {
            continue;
        }
        $item['_tipo'] = 'so_arquivo';
        $linhas[] = $item;
    }
} elseif ($filtro === 'so_sistema') {
    foreach ($comparacao['so_sistema'] as $item) {
        $blob = $item['sistema']['cpf'] . ' ' . $item['sistema']['nome'];
        if (!cmp_passa_busca($blob, $busca)) {
            continue;
        }
        $item['_tipo'] = 'so_sistema';
        $linhas[] = $item;
    }
} elseif ($filtro === 'sem_cpf') {
    foreach ($comparacao['sem_cpf'] as $item) {
        $blob = $item['arquivo']['nome'] . ' ' . $item['arquivo']['matricula'];
        if (!cmp_passa_busca($blob, $busca)) {
            continue;
        }
        $item['_tipo'] = 'sem_cpf';
        $linhas[] = $item;
    }
} elseif ($filtro === 'cpf_repetido') {
    foreach ($comparacao['cpf_repetido'] as $item) {
        $blob = $item['cpf'] . ' ' . implode(' ', $item['nomes_arquivo']);
        if (!empty($item['sistema'])) {
            $blob .= ' ' . $item['sistema']['nome'];
        }
        $blob .= ' ' . implode(' ', $item['matriculas']);
        if (!cmp_passa_busca($blob, $busca)) {
            continue;
        }
        $item['_tipo'] = 'cpf_repetido';
        $linhas[] = $item;
    }
} elseif ($filtro === 'sem_unidade') {
    foreach ($comparacao['sem_unidade'] as $item) {
        $blob = $item['sistema']['cpf'] . ' ' . $item['sistema']['nome'];
        if (!empty($item['arquivo'])) {
            $blob .= ' ' . $item['arquivo']['nome'] . ' ' . $item['arquivo']['matricula'];
        }
        if (!cmp_passa_busca($blob, $busca)) {
            continue;
        }
        $item['_tipo'] = 'sem_unidade';
        $linhas[] = $item;
    }
}

$totalLinhas = count($linhas);
$totalPaginas = $totalLinhas > 0 ? (int) ceil($totalLinhas / $porPagina) : 1;
if ($pagina > $totalPaginas) {
    $pagina = $totalPaginas;
}
$offset = ($pagina - 1) * $porPagina;
$linhasPagina = array_slice($linhas, $offset, $porPagina);

function cmp_qs($extra)
{
    $base = $_GET;
    foreach ($extra as $k => $v) {
        $base[$k] = $v;
    }
    return 'compara_cadastro.php?' . http_build_query($base);
}

$tot = $comparacao['totais'];
$nomeArquivo = $comparacao['arquivo'];
?>

<div id="conteudo">
    <div id="cont" class="cmp-wrap">
        <h2>Comparação de dados cadastrais</h2>
        <p class="cmp-sub">Confronta o arquivo CSV com as tabelas <b>proprietario</b> (campo CPF) e <b>dependente</b> (campo doc_indentificacao_dependente, quando preenchido com CPF). A origem do encontro é exibida em cada registro.</p>
        <hr />

        <div class="cmp-box">
            <form method="post" action="compara_cadastro.php" enctype="multipart/form-data" class="cmp-upload">
                <label><b>Arquivo CSV:</b></label>
                <input type="file" name="arquivo_csv" accept=".csv,text/csv" />
                <input type="submit" value="Carregar e comparar" />
                <?php if ($arquivoAtual !== $arquivoPadrao) { ?>
                    <a href="compara_cadastro.php?usar=padrao">Usar arquivo original</a>
                <?php } ?>
            </form>
            <p class="cmp-legend">Arquivo atual: <b><?= cmp_h($nomeArquivo) ?></b>
                <?php if ($msgUpload !== '') { echo ' — ' . cmp_h($msgUpload); } ?>
            </p>
        </div>

        <?php if ($comparacao['erro'] !== '') { ?>
            <div class="cmp-obs"><?= cmp_h($comparacao['erro']) ?></div>
        <?php } else { ?>

            <div class="cmp-obs">
                No arquivo enviado, endereço, bairro, cidade e CEP estão vazios na maioria dos registros.
                A comparação desses campos é feita sempre que houver valor nos dois lados. Telefone usa a coluna
                A matrícula (ex.: B-081) é conferida com as unidades do proprietário.
                O CPF do arquivo também é buscado em <b>dependente.doc_indentificacao_dependente</b>.
                Use os cartões <b>CPF com nomes diferentes</b> e <b>Sem unidade</b> para os casos extras.
            </div>

            <div class="cmp-cards">
                <a class="cmp-card <?= $filtro === 'vinculados' ? 'ativo' : '' ?>" href="<?= cmp_qs(array('filtro' => 'vinculados', 'pagina' => 1)) ?>">
                    <strong><?= (int) $tot['vinculados'] ?></strong>
                    <span>Vinculados por CPF</span>
                </a>
                <a class="cmp-card origem_proprietario <?= $filtro === 'origem_proprietario' ? 'ativo' : '' ?>" href="<?= cmp_qs(array('filtro' => 'origem_proprietario', 'pagina' => 1)) ?>">
                    <strong><?= (int) $tot['origem_proprietario'] ?></strong>
                    <span>Encontrados em proprietario</span>
                </a>
                <a class="cmp-card origem_dependente <?= $filtro === 'origem_dependente' ? 'ativo' : '' ?>" href="<?= cmp_qs(array('filtro' => 'origem_dependente', 'pagina' => 1)) ?>">
                    <strong><?= (int) $tot['origem_dependente'] ?></strong>
                    <span>Encontrados em dependente</span>
                </a>
                <a class="cmp-card divergente <?= $filtro === 'divergente' ? 'ativo' : '' ?>" href="<?= cmp_qs(array('filtro' => 'divergente', 'pagina' => 1)) ?>">
                    <strong><?= (int) $tot['divergente'] ?></strong>
                    <span>Divergências</span>
                </a>
                <a class="cmp-card conforme <?= $filtro === 'conforme' ? 'ativo' : '' ?>" href="<?= cmp_qs(array('filtro' => 'conforme', 'pagina' => 1)) ?>">
                    <strong><?= (int) $tot['conforme'] ?></strong>
                    <span>Conformes</span>
                </a>
                <a class="cmp-card cpf_repetido <?= $filtro === 'cpf_repetido' ? 'ativo' : '' ?>" href="<?= cmp_qs(array('filtro' => 'cpf_repetido', 'pagina' => 1)) ?>">
                    <strong><?= (int) $tot['cpf_repetido'] ?></strong>
                    <span>CPF com nomes diferentes</span>
                </a>
                <a class="cmp-card sem_unidade <?= $filtro === 'sem_unidade' ? 'ativo' : '' ?>" href="<?= cmp_qs(array('filtro' => 'sem_unidade', 'pagina' => 1)) ?>">
                    <strong><?= (int) $tot['sem_unidade'] ?></strong>
                    <span>Sem unidade vinculada</span>
                </a>
                <a class="cmp-card so_arquivo <?= $filtro === 'so_arquivo' ? 'ativo' : '' ?>" href="<?= cmp_qs(array('filtro' => 'so_arquivo', 'pagina' => 1)) ?>">
                    <strong><?= (int) $tot['so_arquivo'] ?></strong>
                    <span>Só no arquivo</span>
                </a>
                <a class="cmp-card so_sistema <?= $filtro === 'so_sistema' ? 'ativo' : '' ?>" href="<?= cmp_qs(array('filtro' => 'so_sistema', 'pagina' => 1)) ?>">
                    <strong><?= (int) $tot['so_sistema'] ?></strong>
                    <span>Só no sistema</span>
                </a>
                <a class="cmp-card sem_cpf <?= $filtro === 'sem_cpf' ? 'ativo' : '' ?>" href="<?= cmp_qs(array('filtro' => 'sem_cpf', 'pagina' => 1)) ?>">
                    <strong><?= (int) $tot['sem_cpf'] ?></strong>
                    <span>Sem CPF no arquivo</span>
                </a>
                <div class="cmp-card">
                    <strong><?= (int) $comparacao['total_arquivo'] ?></strong>
                    <span>Linhas no arquivo</span>
                </div>
                <div class="cmp-card">
                    <strong><?= (int) $comparacao['total_sistema'] ?></strong>
                    <span>Proprietários no sistema</span>
                </div>
                <div class="cmp-card">
                    <strong><?= (int) $comparacao['total_dependentes'] ?></strong>
                    <span>Dependentes com CPF no doc</span>
                </div>
            </div>

            <form method="get" action="compara_cadastro.php" class="cmp-toolbar">
                <input type="hidden" name="filtro" value="<?= cmp_h($filtro) ?>" />
                <input type="text" name="q" value="<?= cmp_h($busca) ?>" placeholder="Buscar por nome, CPF ou matrícula" />
                <input type="submit" value="Filtrar" />
                <?php if ($busca !== '') { ?>
                    <a href="<?= cmp_qs(array('q' => '', 'pagina' => 1)) ?>">Limpar busca</a>
                <?php } ?>
                <span class="cmp-legend"><?= (int) $totalLinhas ?> registro(s) neste filtro</span>
            </form>

            <div class="cmp-table-wrap">
                <table class="cmp-table">
                    <thead>
                        <tr>
                            <th>CPF</th>
                            <th>Encontrado em</th>
                            <th>Nome no sistema</th>
                            <th>Nome no arquivo</th>
                            <th>Telefone / celular</th>
                            <th>E-mail</th>
                            <th>Unidade / matrícula</th>
                            <th>Situação</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($totalLinhas === 0) {
                        echo '<tr><td colspan="9">Nenhum registro neste filtro.</td></tr>';
                    }
                    foreach ($linhasPagina as $idx => $item) {
                        $tipo = $item['_tipo'];
                        $idDetalhe = 'd' . $idx . '_' . $pagina;
                        if ($tipo === 'vinculado') {
                            $cpf = compara_formatar_cpf($item['cpf']);
                            $nomeSis = $item['sistema']['nome'];
                            $nomeArq = $item['arquivo']['nome'];
                            $telSis = $item['campos']['telefone']['sistema'];
                            $telArq = $item['campos']['telefone']['arquivo'];
                            $emailSis = $item['campos']['email']['sistema'];
                            $emailArq = $item['campos']['email']['arquivo'];
                            $unSis = $item['campos']['unidade']['sistema'];
                            $unArq = $item['arquivo']['matricula'];
                            $status = $item['status'];
                            $classeLinha = $status === 'divergente' ? 'divergente' : '';
                            $origem = isset($item['origem']) ? $item['origem'] : 'proprietario';
                        } elseif ($tipo === 'so_arquivo') {
                            $cpf = compara_formatar_cpf($item['arquivo']['cpf']);
                            $nomeSis = '—';
                            $nomeArq = $item['arquivo']['nome'];
                            $telSis = '';
                            $telArq = $item['arquivo']['celular'];
                            $emailSis = '';
                            $emailArq = $item['arquivo']['email'];
                            $unSis = '';
                            $unArq = $item['arquivo']['matricula'];
                            $status = 'so_arquivo';
                            $classeLinha = '';
                            $origem = '';
                        } elseif ($tipo === 'so_sistema') {
                            $cpf = compara_formatar_cpf($item['sistema']['cpf']);
                            $nomeSis = $item['sistema']['nome'];
                            $nomeArq = '—';
                            $telSis = $item['sistema']['telefone'];
                            $telArq = '';
                            $emailSis = $item['sistema']['email'];
                            $emailArq = '';
                            $unSis = compara_rotulo_unidades($item['sistema']['unidades']);
                            $unArq = '';
                            $status = 'so_sistema';
                            $classeLinha = '';
                            $origem = isset($item['origem']) ? $item['origem'] : 'proprietario';
                        } elseif ($tipo === 'cpf_repetido') {
                            $cpf = compara_formatar_cpf($item['cpf']);
                            $nomeSis = !empty($item['sistema']) ? $item['sistema']['nome'] : '—';
                            $nomeArq = implode(' | ', $item['nomes_arquivo']);
                            $telSis = !empty($item['sistema']) ? $item['sistema']['telefone'] : '';
                            $telArq = $item['arquivo']['celular'];
                            $emailSis = !empty($item['sistema']) ? $item['sistema']['email'] : '';
                            $emailArq = $item['arquivo']['email'];
                            $unSis = !empty($item['sistema']) ? compara_rotulo_unidades($item['sistema']['unidades']) : '';
                            $unArq = implode(' | ', $item['matriculas']);
                            $status = 'cpf_repetido';
                            $classeLinha = 'divergente';
                            $origem = isset($item['origem']) ? $item['origem'] : '';
                        } elseif ($tipo === 'sem_unidade') {
                            $cpf = compara_formatar_cpf($item['sistema']['cpf']);
                            $nomeSis = $item['sistema']['nome'];
                            $nomeArq = !empty($item['arquivo']) ? $item['arquivo']['nome'] : '—';
                            $telSis = $item['sistema']['telefone'];
                            $telArq = !empty($item['arquivo']) ? $item['arquivo']['celular'] : '';
                            $emailSis = $item['sistema']['email'];
                            $emailArq = !empty($item['arquivo']) ? $item['arquivo']['email'] : '';
                            $unSis = '—';
                            $unArq = !empty($item['arquivo']) ? $item['arquivo']['matricula'] : '';
                            $status = 'sem_unidade';
                            $classeLinha = 'sem-unidade';
                            $origem = isset($item['origem']) ? $item['origem'] : 'proprietario';
                        } else {
                            $cpf = '—';
                            $nomeSis = '—';
                            $nomeArq = $item['arquivo']['nome'];
                            $telSis = '';
                            $telArq = $item['arquivo']['celular'];
                            $emailSis = '';
                            $emailArq = $item['arquivo']['email'];
                            $unSis = '';
                            $unArq = $item['arquivo']['matricula'];
                            $status = 'sem_cpf';
                            $classeLinha = '';
                            $origem = !empty($item['dependente']) ? 'dependente' : '';
                        }
                        ?>
                        <tr class="cmp-row <?= $classeLinha ?>">
                            <td><?= cmp_h($cpf) ?></td>
                            <td><?= cmp_origem($origem) ?></td>
                            <td><?= cmp_h($nomeSis) ?></td>
                            <td><?= cmp_h($nomeArq) ?></td>
                            <td><?php
                                if ($tipo === 'vinculado' || $tipo === 'cpf_repetido' || $tipo === 'sem_unidade') {
                                    echo '<div>' . cmp_h($telSis !== '' ? $telSis : '—') . '</div><div>' . cmp_h($telArq !== '' ? $telArq : '—') . '</div>';
                                } else {
                                    echo cmp_h($telSis !== '' ? $telSis : $telArq);
                                }
                            ?></td>
                            <td><?php
                                if ($tipo === 'vinculado' || $tipo === 'cpf_repetido' || $tipo === 'sem_unidade') {
                                    echo '<div>' . cmp_h($emailSis !== '' ? $emailSis : '—') . '</div><div>' . cmp_h($emailArq !== '' ? $emailArq : '—') . '</div>';
                                } else {
                                    echo cmp_h($emailSis !== '' ? $emailSis : $emailArq);
                                }
                            ?></td>
                            <td><?php
                                if ($tipo === 'vinculado' || $tipo === 'cpf_repetido' || $tipo === 'sem_unidade') {
                                    echo '<div>' . cmp_h($unSis !== '' ? $unSis : '—') . '</div><div>' . cmp_h($unArq !== '' ? $unArq : '—') . '</div>';
                                } else {
                                    echo cmp_h($unSis !== '' ? $unSis : $unArq);
                                }
                            ?></td>
                            <td><span class="cmp-badge <?= cmp_h($status) ?>"><?= cmp_h(compara_rotulo_status($status)) ?></span></td>
                            <td>
                                <button type="button" class="cmp-btn-link" onclick="cmpToggle('<?= $idDetalhe ?>')">Detalhes</button>
                            </td>
                        </tr>
                        <tr id="<?= $idDetalhe ?>" class="cmp-detalhe">
                            <td colspan="9">
                                <?php if ($tipo === 'vinculado') { ?>
                                    <p>CPF encontrado em: <?= cmp_origem($item['origem']) ?>
                                        <?php if ($item['origem'] === 'dependente' && !empty($item['dependente'])) { ?>
                                            — <?= cmp_h($item['dependente']['nome']) ?>
                                            (<?= cmp_h($item['dependente']['parentesco']) ?> de <?= cmp_h($item['dependente']['proprietario']) ?>).
                                            Documento: <b><?= cmp_h($item['dependente']['doc']) ?></b>
                                        <?php } elseif ($item['origem'] === 'ambos' && !empty($item['dependente'])) { ?>
                                            — também cadastrado como dependente
                                            <?= cmp_h($item['dependente']['nome']) ?>
                                            (<?= cmp_h($item['dependente']['parentesco']) ?> de <?= cmp_h($item['dependente']['proprietario']) ?>).
                                        <?php } elseif ($item['origem'] === 'proprietario') { ?>
                                            — cadastro da tabela <b>proprietario</b>.
                                        <?php } ?>
                                    </p>
                                    <table class="cmp-campos">
                                        <tr>
                                            <th width="18%">Campo</th>
                                            <th width="36%">Sistema</th>
                                            <th width="36%">Arquivo</th>
                                            <th width="10%">Resultado</th>
                                        </tr>
                                        <?php
                                        $rotulosCampo = array(
                                            'nome' => 'Nome',
                                            'telefone' => 'Telefone / celular',
                                            'email' => 'E-mail',
                                            'endereco' => 'Endereço',
                                            'cidade' => 'Cidade',
                                            'cep' => 'CEP',
                                            'unidade' => 'Unidade / matrícula'
                                        );
                                        foreach ($rotulosCampo as $chaveCampo => $rotuloCampo) {
                                            $c = $item['campos'][$chaveCampo];
                                            $arqExibir = $c['arquivo'];
                                            if ($chaveCampo === 'unidade' && isset($c['arquivo_normalizado']) && $c['arquivo_normalizado'] !== '') {
                                                $arqExibir = $c['arquivo'] . ' → ' . $c['arquivo_normalizado'];
                                            }
                                            $sisExibir = $c['sistema'];
                                            if ($chaveCampo === 'cidade' && $item['sistema']['estado'] !== '') {
                                                $sisExibir = trim($c['sistema'] . ' / ' . $item['sistema']['estado']);
                                            }
                                            echo '<tr>';
                                            echo '<td><b>' . cmp_h($rotuloCampo) . '</b></td>';
                                            echo '<td class="' . cmp_h($c['status']) . '">' . cmp_h($sisExibir !== '' ? $sisExibir : '—') . '</td>';
                                            echo '<td class="' . cmp_h($c['status']) . '">' . cmp_h($arqExibir !== '' ? $arqExibir : '—') . '</td>';
                                            echo '<td><span class="cmp-badge ' . cmp_h($c['status']) . '">' . cmp_h(compara_rotulo_status($c['status'])) . '</span></td>';
                                            echo '</tr>';
                                        }
                                        if ($item['arquivo']['observacao'] !== '') {
                                            echo '<tr><td><b>Observação (arquivo)</b></td><td colspan="3">' . cmp_h($item['arquivo']['observacao']) . '</td></tr>';
                                        }
                                        ?>
                                    </table>
                                <?php } elseif ($tipo === 'so_arquivo') { ?>
                                    <p>Pessoa presente no arquivo e <b>não encontrada</b> na tabela <b>proprietario</b> (CPF) nem na tabela <b>dependente</b> (doc_indentificacao_dependente).</p>
                                    <p>
                                        Matrícula: <b><?= cmp_h($item['arquivo']['matricula']) ?></b> —
                                        Celular: <b><?= cmp_h($item['arquivo']['celular'] !== '' ? $item['arquivo']['celular'] : '—') ?></b> —
                                        E-mail: <b><?= cmp_h($item['arquivo']['email'] !== '' ? $item['arquivo']['email'] : '—') ?></b>
                                    </p>
                                    <?php if (!empty($item['dependente'])) { ?>
                                        <p>Há cadastro semelhante em <b>dependente</b> pelo nome (o documento não confere com este CPF):
                                            <?= cmp_h($item['dependente']['nome']) ?>
                                            (<?= cmp_h($item['dependente']['parentesco']) ?> de <?= cmp_h($item['dependente']['proprietario']) ?>).
                                        </p>
                                    <?php } ?>
                                <?php } elseif ($tipo === 'cpf_repetido') { ?>
                                    <?php if ((int) $item['qtd_arquivo'] > 1) { ?>
                                        <p>O CPF aparece <b><?= (int) $item['qtd_arquivo'] ?></b> vezes no arquivo, associado a nomes diferentes.</p>
                                    <?php } else { ?>
                                        <p>O mesmo CPF está com <b>nomes diferentes</b> no sistema e no arquivo.</p>
                                    <?php } ?>
                                    <?php if (!empty($item['sistema'])) { ?>
                                        <p>Cadastro no sistema: <?= cmp_origem(isset($item['origem']) ? $item['origem'] : '') ?>
                                            — <b><?= cmp_h($item['sistema']['nome']) ?></b>
                                            <?php if (!empty($item['dependente']) && $item['origem'] !== 'proprietario') { ?>
                                                (<?= cmp_h($item['dependente']['parentesco']) ?> de <?= cmp_h($item['dependente']['proprietario']) ?>)
                                            <?php } ?>
                                        </p>
                                    <?php } else { ?>
                                        <p>Este CPF <b>não foi encontrado</b> nas tabelas proprietario e dependente.</p>
                                    <?php } ?>
                                    <table class="cmp-campos">
                                        <tr>
                                            <th>Origem</th>
                                            <th>Nome</th>
                                            <th>Matrícula</th>
                                            <th>Celular</th>
                                            <th>E-mail</th>
                                        </tr>
                                        <?php if (!empty($item['sistema'])) { ?>
                                            <tr>
                                                <td><?= cmp_origem(isset($item['origem']) ? $item['origem'] : '') ?></td>
                                                <td><?= cmp_h($item['sistema']['nome']) ?></td>
                                                <td><?= cmp_h(compara_rotulo_unidades($item['sistema']['unidades']) !== '' ? compara_rotulo_unidades($item['sistema']['unidades']) : '—') ?></td>
                                                <td><?= cmp_h($item['sistema']['telefone'] !== '' ? $item['sistema']['telefone'] : '—') ?></td>
                                                <td><?= cmp_h($item['sistema']['email'] !== '' ? $item['sistema']['email'] : '—') ?></td>
                                            </tr>
                                        <?php } ?>
                                        <?php foreach ($item['linhas'] as $linhaArq) { ?>
                                            <tr>
                                                <td>Arquivo</td>
                                                <td><?= cmp_h($linhaArq['nome']) ?></td>
                                                <td><?= cmp_h($linhaArq['matricula'] !== '' ? $linhaArq['matricula'] : '—') ?></td>
                                                <td><?= cmp_h($linhaArq['celular'] !== '' ? $linhaArq['celular'] : '—') ?></td>
                                                <td><?= cmp_h($linhaArq['email'] !== '' ? $linhaArq['email'] : '—') ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                <?php } elseif ($tipo === 'sem_unidade') { ?>
                                    <p>Este CPF está na tabela <b>proprietario</b> e <b>não possui unidade</b> na tabela unidade.</p>
                                    <p>
                                        Endereço: <b><?= cmp_h($item['sistema']['endereco'] !== '' ? $item['sistema']['endereco'] : '—') ?></b> —
                                        Cidade: <b><?= cmp_h(trim($item['sistema']['cidade'] . ' / ' . $item['sistema']['estado'])) ?></b> —
                                        CEP: <b><?= cmp_h($item['sistema']['cep'] !== '' ? $item['sistema']['cep'] : '—') ?></b>
                                    </p>
                                    <?php if (!empty($item['arquivo'])) { ?>
                                        <p>No arquivo, a matrícula informada é <b><?= cmp_h($item['arquivo']['matricula'] !== '' ? $item['arquivo']['matricula'] : 'não informada') ?></b>
                                            (nome: <?= cmp_h($item['arquivo']['nome']) ?>).</p>
                                    <?php } else { ?>
                                        <p>Este CPF não aparece no arquivo comparado.</p>
                                    <?php } ?>
                                <?php } elseif ($tipo === 'sem_cpf') { ?>
                                    <p>Registro do arquivo sem CPF. Não é possível vincular com a tabela proprietario pela chave principal.</p>
                                    <p>
                                        Nome: <b><?= cmp_h($item['arquivo']['nome']) ?></b> —
                                        Matrícula: <b><?= cmp_h($item['arquivo']['matricula']) ?></b>
                                    </p>
                                    <?php if (!empty($item['dependente'])) { ?>
                                        <p>Nome encontrado em <b>dependente</b>:
                                            <?= cmp_h($item['dependente']['nome']) ?>
                                            (<?= cmp_h($item['dependente']['parentesco']) ?> de <?= cmp_h($item['dependente']['proprietario']) ?>).
                                        </p>
                                    <?php } ?>
                                <?php } else { ?>
                                    <?php if (isset($item['origem']) && $item['origem'] === 'dependente') { ?>
                                        <p>Cadastro encontrado na tabela <b>dependente</b> (documento = CPF) e não localizado no arquivo.</p>
                                        <?php if (!empty($item['dependente'])) { ?>
                                            <p>
                                                <?= cmp_h($item['dependente']['nome']) ?>
                                                (<?= cmp_h($item['dependente']['parentesco']) ?> de <?= cmp_h($item['dependente']['proprietario']) ?>).
                                                Documento: <b><?= cmp_h($item['dependente']['doc']) ?></b>
                                            </p>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <p>Cadastro encontrado na tabela <b>proprietario</b> e não localizado no arquivo pelo CPF.</p>
                                        <p>
                                            Endereço: <b><?= cmp_h($item['sistema']['endereco'] !== '' ? $item['sistema']['endereco'] : '—') ?></b> —
                                            Cidade: <b><?= cmp_h(trim($item['sistema']['cidade'] . ' / ' . $item['sistema']['estado'])) ?></b> —
                                            CEP: <b><?= cmp_h($item['sistema']['cep'] !== '' ? $item['sistema']['cep'] : '—') ?></b>
                                        </p>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPaginas > 1) { ?>
                <div class="cmp-paginacao">
                    <?php
                    $paginasMostrar = array();
                    for ($p = 1; $p <= $totalPaginas; $p++) {
                        if ($p === 1 || $p === $totalPaginas || abs($p - $pagina) <= 2) {
                            $paginasMostrar[$p] = true;
                        }
                    }
                    $anterior = 0;
                    foreach ($paginasMostrar as $p => $ok) {
                        if ($anterior && $p > $anterior + 1) {
                            echo '<span style="background:#fff;color:#666;border:none;">...</span>';
                        }
                        if ($p === $pagina) {
                            echo '<span>' . $p . '</span>';
                        } else {
                            echo '<a href="' . cmp_h(cmp_qs(array('pagina' => $p))) . '">' . $p . '</a>';
                        }
                        $anterior = $p;
                    }
                    ?>
                </div>
            <?php } ?>

        <?php } ?>
        <br />
    </div>
</div>
<script type="text/javascript">
    function cmpToggle(id) {
        var el = document.getElementById(id);
        if (!el) {
            return;
        }
        if (el.className.indexOf('aberto') >= 0) {
            el.className = el.className.replace(' aberto', '').replace('aberto', '');
        } else {
            el.className = el.className + ' aberto';
        }
    }
</script>
<?php
include "rodape.php";
?>

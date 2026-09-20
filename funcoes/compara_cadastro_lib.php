<?php

function compara_para_utf8($texto)
{
    if ($texto === null || $texto === '') {
        return '';
    }
    if (function_exists('mb_check_encoding') && mb_check_encoding($texto, 'UTF-8')) {
        return $texto;
    }
    return utf8_encode($texto);
}

function compara_so_digitos($texto)
{
    return preg_replace('/\D/', '', (string) $texto);
}

function compara_normalizar_texto($texto)
{
    $texto = compara_para_utf8($texto);
    $texto = trim($texto);
    if ($texto === '') {
        return '';
    }
    if (function_exists('mb_strtoupper')) {
        $texto = mb_strtoupper($texto, 'UTF-8');
    } else {
        $texto = strtoupper($texto);
    }
    $acentos = array(
        'Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
        'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
        'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'Ó' => 'O', 'Ò' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O',
        'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
        'Ç' => 'C', 'Ñ' => 'N',
        'á' => 'A', 'à' => 'A', 'â' => 'A', 'ã' => 'A', 'ä' => 'A',
        'é' => 'E', 'è' => 'E', 'ê' => 'E', 'ë' => 'E',
        'í' => 'I', 'ì' => 'I', 'î' => 'I', 'ï' => 'I',
        'ó' => 'O', 'ò' => 'O', 'ô' => 'O', 'õ' => 'O', 'ö' => 'O',
        'ú' => 'U', 'ù' => 'U', 'û' => 'U', 'ü' => 'U',
        'ç' => 'C', 'ñ' => 'N'
    );
    $texto = strtr($texto, $acentos);
    $texto = preg_replace('/\s+/', ' ', $texto);
    return $texto;
}

function compara_valor_util($texto)
{
    $texto = trim(compara_para_utf8($texto));
    if ($texto === '') {
        return '';
    }
    $norm = compara_normalizar_texto($texto);
    if ($norm === 'NAO INFORMADO' || $norm === 'NAOINFORMADO' || $norm === 'NULL' || $norm === '-' || $norm === '0') {
        return '';
    }
    return $texto;
}

function compara_chave_coluna($nome)
{
    $nome = compara_normalizar_texto($nome);
    $nome = strtolower($nome);
    return preg_replace('/[^a-z0-9]/', '', $nome);
}

function compara_formatar_cpf($cpf)
{
    $cpf = compara_so_digitos($cpf);
    if (strlen($cpf) === 11) {
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }
    if (strlen($cpf) === 14) {
        return substr($cpf, 0, 2) . '.' . substr($cpf, 2, 3) . '.' . substr($cpf, 5, 3) . '/' . substr($cpf, 8, 4) . '-' . substr($cpf, 12, 2);
    }
    return $cpf;
}

function compara_mapear_etapa($letra)
{
    $letra = compara_normalizar_texto($letra);
    $mapa = array(
        'A' => 'Azaléia - AZ',
        'AZ' => 'Azaléia - AZ',
        'AZALEIA' => 'Azaléia - AZ',
        'B' => 'Bougainville - BO',
        'BO' => 'Bougainville - BO',
        'BOUGAINVILLE' => 'Bougainville - BO',
        'G' => 'Gardênia - GA',
        'GA' => 'Gardênia - GA',
        'GARDENIA' => 'Gardênia - GA',
        'J' => 'Jacarandás - JAC',
        'JAC' => 'Jacarandás - JAC',
        'JACARANDAS' => 'Jacarandás - JAC',
        'O' => 'Orquídeas - OR',
        'OR' => 'Orquídeas - OR',
        'ORQUIDEAS' => 'Orquídeas - OR',
        'P' => 'Pitangueiras - PIT',
        'PIT' => 'Pitangueiras - PIT',
        'PITANGUEIRAS' => 'Pitangueiras - PIT'
    );
    return isset($mapa[$letra]) ? $mapa[$letra] : '';
}

function compara_extrair_unidades($matricula)
{
    $unidades = array();
    if (!preg_match_all('/([A-Za-zÀ-ÿ]+)\s*[-]?\s*(\d{1,4})/u', (string) $matricula, $matches, PREG_SET_ORDER)) {
        if (!preg_match_all('/([A-Za-z]+)\s*[-]?\s*(\d{1,4})/', (string) $matricula, $matches, PREG_SET_ORDER)) {
            return $unidades;
        }
    }
    foreach ($matches as $m) {
        $etapa = compara_mapear_etapa($m[1]);
        $numero = (int) $m[2];
        if ($etapa === '' || $numero <= 0) {
            continue;
        }
        $chave = $etapa . '|' . $numero;
        $unidades[$chave] = array(
            'etapa' => $etapa,
            'numero' => $numero,
            'rotulo' => $etapa . ' / ' . str_pad((string) $numero, 3, '0', STR_PAD_LEFT)
        );
    }
    return array_values($unidades);
}

function compara_rotulo_unidades($lista)
{
    $rotulos = array();
    foreach ($lista as $u) {
        $rotulos[] = $u['rotulo'];
    }
    return implode(' | ', $rotulos);
}

function compara_extrair_telefones($texto)
{
    $texto = compara_valor_util($texto);
    if ($texto === '') {
        return array();
    }
    $partes = preg_split('/[\/;,|]/', $texto);
    $telefones = array();
    foreach ($partes as $parte) {
        $digitos = compara_so_digitos($parte);
        if (strlen($digitos) >= 8) {
            $telefones[] = $digitos;
        }
    }
    return array_values(array_unique($telefones));
}

function compara_campos_texto($valorSistema, $valorArquivo)
{
    $sis = compara_valor_util($valorSistema);
    $arq = compara_valor_util($valorArquivo);
    $resultado = array(
        'sistema' => $sis,
        'arquivo' => $arq,
        'status' => 'nao_informado'
    );
    if ($sis === '' && $arq === '') {
        $resultado['status'] = 'nao_informado';
        return $resultado;
    }
    if ($sis === '') {
        $resultado['status'] = 'so_arquivo';
        return $resultado;
    }
    if ($arq === '') {
        $resultado['status'] = 'so_sistema';
        return $resultado;
    }
    if (compara_normalizar_texto($sis) === compara_normalizar_texto($arq)) {
        $resultado['status'] = 'igual';
    } else {
        $resultado['status'] = 'divergente';
    }
    return $resultado;
}

function compara_campos_telefone($valorSistema, $celularArquivo, $telefoneArquivo)
{
    $sis = compara_valor_util($valorSistema);
    $arqBruto = trim(compara_valor_util($celularArquivo) . ' / ' . compara_valor_util($telefoneArquivo), ' /');
    $telsSis = compara_extrair_telefones($sis);
    $telsArq = array_merge(compara_extrair_telefones($celularArquivo), compara_extrair_telefones($telefoneArquivo));
    $telsArq = array_values(array_unique($telsArq));

    $resultado = array(
        'sistema' => $sis,
        'arquivo' => $arqBruto,
        'status' => 'nao_informado'
    );
    if (empty($telsSis) && empty($telsArq)) {
        return $resultado;
    }
    if (empty($telsSis)) {
        $resultado['status'] = 'so_arquivo';
        return $resultado;
    }
    if (empty($telsArq)) {
        $resultado['status'] = 'so_sistema';
        return $resultado;
    }
    $igual = false;
    foreach ($telsSis as $s) {
        foreach ($telsArq as $a) {
            if ($s === $a || substr($s, -8) === substr($a, -8) || substr($s, -9) === substr($a, -9)) {
                $igual = true;
                break 2;
            }
        }
    }
    $resultado['status'] = $igual ? 'igual' : 'divergente';
    return $resultado;
}

function compara_campos_email($valorSistema, $emailArquivo, $emailLogs)
{
    $sis = strtolower(compara_valor_util($valorSistema));
    $arq = strtolower(compara_valor_util($emailArquivo));
    if ($arq === '') {
        $arq = strtolower(compara_valor_util($emailLogs));
    }
    return compara_campos_texto($sis, $arq);
}

function compara_campos_cep($valorSistema, $valorArquivo)
{
    $sis = compara_so_digitos($valorSistema);
    $arq = compara_so_digitos($valorArquivo);
    $resultado = array(
        'sistema' => compara_valor_util($valorSistema),
        'arquivo' => compara_valor_util($valorArquivo),
        'status' => 'nao_informado'
    );
    if ($sis === '' && $arq === '') {
        return $resultado;
    }
    if ($sis === '') {
        $resultado['status'] = 'so_arquivo';
        return $resultado;
    }
    if ($arq === '') {
        $resultado['status'] = 'so_sistema';
        return $resultado;
    }
    $resultado['status'] = ($sis === $arq) ? 'igual' : 'divergente';
    return $resultado;
}

function compara_campos_unidade($unidadesSistema, $matriculaArquivo)
{
    $unidadesArquivo = compara_extrair_unidades($matriculaArquivo);
    $resultado = array(
        'sistema' => compara_rotulo_unidades($unidadesSistema),
        'arquivo' => compara_valor_util($matriculaArquivo),
        'arquivo_normalizado' => compara_rotulo_unidades($unidadesArquivo),
        'status' => 'nao_informado'
    );
    if (empty($unidadesSistema) && empty($unidadesArquivo)) {
        return $resultado;
    }
    if (empty($unidadesSistema)) {
        $resultado['status'] = 'so_arquivo';
        return $resultado;
    }
    if (empty($unidadesArquivo)) {
        $resultado['status'] = 'so_sistema';
        return $resultado;
    }
    $chavesSis = array();
    foreach ($unidadesSistema as $u) {
        $chavesSis[$u['etapa'] . '|' . (int) $u['numero']] = true;
    }
    $encontrou = false;
    foreach ($unidadesArquivo as $u) {
        if (isset($chavesSis[$u['etapa'] . '|' . (int) $u['numero']])) {
            $encontrou = true;
            break;
        }
    }
    $resultado['status'] = $encontrou ? 'igual' : 'divergente';
    return $resultado;
}

function compara_ler_csv($caminho)
{
    $saida = array(
        'erro' => '',
        'colunas' => array(),
        'linhas' => array(),
        'arquivo' => basename($caminho)
    );
    if (!is_file($caminho)) {
        $saida['erro'] = 'Arquivo CSV não encontrado.';
        return $saida;
    }
    $raw = file_get_contents($caminho);
    if ($raw === false || $raw === '') {
        $saida['erro'] = 'Não foi possível ler o arquivo CSV.';
        return $saida;
    }
    if (substr($raw, 0, 3) === "\xEF\xBB\xBF") {
        $raw = substr($raw, 3);
        $txt = $raw;
    } elseif (function_exists('mb_check_encoding') && mb_check_encoding($raw, 'UTF-8') && preg_match('/[\xC2-\xF4][\x80-\xBF]/', $raw)) {
        $txt = $raw;
    } else {
        $txt = @iconv('Windows-1252', 'UTF-8//IGNORE', $raw);
        if ($txt === false) {
            $txt = utf8_encode($raw);
        }
    }

    $fp = fopen('php://temp', 'r+');
    fwrite($fp, $txt);
    rewind($fp);
    $cabecalho = fgetcsv($fp, 0, ',');
    if ($cabecalho === false) {
        fclose($fp);
        $saida['erro'] = 'A primeira linha do arquivo não pôde ser lida como colunas.';
        return $saida;
    }

    $mapaNomes = array(
        'id' => 'id',
        'matricula' => 'matricula',
        'usuario' => 'nome',
        'nome' => 'nome',
        'cpf' => 'cpf',
        'celular' => 'celular',
        'telefone' => 'telefone',
        'email' => 'email',
        'emaildelogs' => 'email_logs',
        'endereco' => 'endereco',
        'bairro' => 'bairro',
        'cidade' => 'cidade',
        'cep' => 'cep',
        'observacao' => 'observacao',
        'cargo' => 'cargo'
    );
    $indice = array();
    foreach ($cabecalho as $i => $nomeColuna) {
        $chave = compara_chave_coluna($nomeColuna);
        $saida['colunas'][] = compara_para_utf8($nomeColuna);
        if (isset($mapaNomes[$chave])) {
            $indice[$mapaNomes[$chave]] = $i;
        }
    }

    $campos = array('id', 'matricula', 'nome', 'cpf', 'celular', 'telefone', 'email', 'email_logs', 'endereco', 'bairro', 'cidade', 'cep', 'observacao', 'cargo');
    while (($row = fgetcsv($fp, 0, ',')) !== false) {
        if (count($row) === 1 && trim($row[0]) === '') {
            continue;
        }
        $item = array();
        foreach ($campos as $campo) {
            $item[$campo] = '';
            if (isset($indice[$campo]) && isset($row[$indice[$campo]])) {
                $item[$campo] = compara_valor_util($row[$indice[$campo]]);
            }
        }
        if ($item['nome'] === '' && $item['cpf'] === '') {
            continue;
        }
        $item['cpf_digitos'] = compara_so_digitos($item['cpf']);
        $saida['linhas'][] = $item;
    }
    fclose($fp);
    return $saida;
}

function compara_carregar_proprietarios()
{
    $lista = array();
    $sql = mysql_query("SELECT id_proprietario, CPF, nome, endereco, cidade, estado, cep, email, telefone FROM proprietario");
    if (!$sql) {
        return $lista;
    }
    while ($ln = mysql_fetch_assoc($sql)) {
        $cpf = compara_so_digitos($ln['CPF']);
        $item = array(
            'id_proprietario' => $ln['id_proprietario'],
            'cpf' => $cpf,
            'nome' => compara_para_utf8($ln['nome']),
            'endereco' => compara_para_utf8($ln['endereco']),
            'cidade' => compara_para_utf8($ln['cidade']),
            'estado' => compara_para_utf8($ln['estado']),
            'cep' => compara_para_utf8($ln['cep']),
            'email' => compara_para_utf8($ln['email']),
            'telefone' => compara_para_utf8($ln['telefone']),
            'unidades' => array()
        );
        $lista[$cpf] = $item;
    }

    $sqlUn = mysql_query("SELECT id_proprietario, etapa, numero_etapa FROM unidade");
    $porId = array();
    foreach ($lista as $cpf => $prop) {
        $porId[$prop['id_proprietario']] = $cpf;
    }
    if ($sqlUn) {
        while ($un = mysql_fetch_assoc($sqlUn)) {
            $id = $un['id_proprietario'];
            if (!isset($porId[$id])) {
                continue;
            }
            $cpf = $porId[$id];
            $etapa = compara_para_utf8($un['etapa']);
            $numero = (int) $un['numero_etapa'];
            $lista[$cpf]['unidades'][] = array(
                'etapa' => $etapa,
                'numero' => $numero,
                'rotulo' => $etapa . ' / ' . str_pad((string) $numero, 3, '0', STR_PAD_LEFT)
            );
        }
    }
    return $lista;
}

function compara_carregar_dependentes($proprietarios)
{
    $porNome = array();
    $porDoc = array();
    $sql = mysql_query("SELECT dep.id_dependente, dep.id_proprietario, dep.nome_dependente, dep.doc_indentificacao_dependente, dep.parentesco, prop.nome AS nome_proprietario, prop.CPF
                        FROM dependente dep
                        JOIN proprietario prop ON dep.id_proprietario = prop.id_proprietario");
    if (!$sql) {
        return array('nome' => $porNome, 'doc' => $porDoc);
    }
    while ($ln = mysql_fetch_assoc($sql)) {
        $cpfProprietario = compara_so_digitos($ln['CPF']);
        $unidades = array();
        if (isset($proprietarios[$cpfProprietario]) && !empty($proprietarios[$cpfProprietario]['unidades'])) {
            $unidades = $proprietarios[$cpfProprietario]['unidades'];
        }
        $item = array(
            'id_dependente' => $ln['id_dependente'],
            'id_proprietario' => $ln['id_proprietario'],
            'nome' => compara_para_utf8($ln['nome_dependente']),
            'doc' => compara_para_utf8($ln['doc_indentificacao_dependente']),
            'parentesco' => compara_para_utf8($ln['parentesco']),
            'proprietario' => compara_para_utf8($ln['nome_proprietario']),
            'cpf_proprietario' => $cpfProprietario,
            'unidades' => $unidades
        );
        $nomeChave = compara_normalizar_texto($item['nome']);
        $porNome[$nomeChave] = $item;
        $doc = compara_so_digitos($item['doc']);
        if (strlen($doc) >= 11) {
            $porDoc[$doc] = $item;
        }
    }
    return array('nome' => $porNome, 'doc' => $porDoc);
}

function compara_sistema_de_dependente($dep)
{
    return array(
        'id_proprietario' => $dep['id_proprietario'],
        'cpf' => compara_so_digitos($dep['doc']),
        'nome' => $dep['nome'],
        'endereco' => '',
        'cidade' => '',
        'estado' => '',
        'cep' => '',
        'email' => '',
        'telefone' => '',
        'unidades' => isset($dep['unidades']) ? $dep['unidades'] : array(),
        'tipo_cadastro' => 'dependente',
        'parentesco' => $dep['parentesco'],
        'proprietario' => $dep['proprietario'],
        'doc' => $dep['doc']
    );
}

function compara_definir_origem($prop, $dep)
{
    if ($prop !== null && $dep !== null) {
        return 'ambos';
    }
    if ($prop !== null) {
        return 'proprietario';
    }
    if ($dep !== null) {
        return 'dependente';
    }
    return '';
}

function compara_rotulo_origem($origem)
{
    $mapa = array(
        'proprietario' => 'Tabela proprietario',
        'dependente' => 'Tabela dependente',
        'ambos' => 'Proprietario e dependente'
    );
    return isset($mapa[$origem]) ? $mapa[$origem] : 'Não encontrado';
}

function compara_campos_dependente($dep, $linha)
{
    return array(
        'nome' => compara_campos_texto($dep['nome'], $linha['nome']),
        'telefone' => compara_campos_telefone('', $linha['celular'], $linha['telefone']),
        'email' => compara_campos_email('', $linha['email'], $linha['email_logs']),
        'endereco' => compara_campos_texto('', $linha['endereco']),
        'cidade' => compara_campos_texto('', $linha['cidade']),
        'cep' => compara_campos_cep('', $linha['cep']),
        'unidade' => compara_campos_unidade(isset($dep['unidades']) ? $dep['unidades'] : array(), $linha['matricula'])
    );
}

function compara_executar($caminhoCsv)
{
    $csv = compara_ler_csv($caminhoCsv);
    $proprietarios = compara_carregar_proprietarios();
    $dependentes = compara_carregar_dependentes($proprietarios);

    $resultado = array(
        'erro' => $csv['erro'],
        'arquivo' => $csv['arquivo'],
        'colunas' => $csv['colunas'],
        'total_arquivo' => count($csv['linhas']),
        'total_sistema' => count($proprietarios),
        'total_dependentes' => count($dependentes['doc']),
        'vinculados' => array(),
        'so_arquivo' => array(),
        'so_sistema' => array(),
        'sem_cpf' => array(),
        'cpf_repetido' => array(),
        'sem_unidade' => array(),
        'totais' => array(
            'conforme' => 0,
            'divergente' => 0,
            'so_arquivo' => 0,
            'so_sistema' => 0,
            'sem_cpf' => 0,
            'vinculados' => 0,
            'cpf_repetido' => 0,
            'sem_unidade' => 0,
            'origem_proprietario' => 0,
            'origem_dependente' => 0
        )
    );
    if ($csv['erro'] !== '') {
        return $resultado;
    }

    $porCpfArquivo = array();
    foreach ($csv['linhas'] as $linha) {
        $cpf = $linha['cpf_digitos'];
        if (strlen($cpf) < 11) {
            $dep = null;
            $nomeChave = compara_normalizar_texto($linha['nome']);
            if (isset($dependentes['nome'][$nomeChave])) {
                $dep = $dependentes['nome'][$nomeChave];
            }
            $resultado['sem_cpf'][] = array(
                'arquivo' => $linha,
                'dependente' => $dep
            );
            $resultado['totais']['sem_cpf']++;
            continue;
        }
        if (!isset($porCpfArquivo[$cpf])) {
            $porCpfArquivo[$cpf] = array();
        }
        $porCpfArquivo[$cpf][] = $linha;
    }

    foreach ($porCpfArquivo as $cpf => $linhasCpf) {
        $linha = $linhasCpf[0];
        $prop = isset($proprietarios[$cpf]) ? $proprietarios[$cpf] : null;
        $dep = isset($dependentes['doc'][$cpf]) ? $dependentes['doc'][$cpf] : null;
        $origem = compara_definir_origem($prop, $dep);

        $nomesArquivo = array();
        $matriculas = array();
        foreach ($linhasCpf as $l) {
            $chaveNome = compara_normalizar_texto($l['nome']);
            if ($chaveNome !== '' && !isset($nomesArquivo[$chaveNome])) {
                $nomesArquivo[$chaveNome] = $l['nome'];
            }
            if ($l['matricula'] !== '' && !in_array($l['matricula'], $matriculas)) {
                $matriculas[] = $l['matricula'];
            }
        }
        $nomesDistintos = $nomesArquivo;
        if ($prop !== null) {
            $chaveSis = compara_normalizar_texto($prop['nome']);
            if ($chaveSis !== '' && !isset($nomesDistintos[$chaveSis])) {
                $nomesDistintos[$chaveSis] = $prop['nome'];
            }
        }
        if ($dep !== null) {
            $chaveDep = compara_normalizar_texto($dep['nome']);
            if ($chaveDep !== '' && !isset($nomesDistintos[$chaveDep])) {
                $nomesDistintos[$chaveDep] = $dep['nome'];
            }
        }
        if (count($nomesDistintos) > 1) {
            $resultado['cpf_repetido'][] = array(
                'cpf' => $cpf,
                'sistema' => $prop !== null ? $prop : ($dep !== null ? compara_sistema_de_dependente($dep) : null),
                'dependente' => $dep,
                'origem' => $origem,
                'arquivo' => $linha,
                'linhas' => $linhasCpf,
                'nomes_arquivo' => array_values($nomesArquivo),
                'matriculas' => $matriculas,
                'qtd_arquivo' => count($linhasCpf)
            );
            $resultado['totais']['cpf_repetido']++;
        }

        if ($prop === null && $dep === null) {
            $depNome = null;
            if (isset($dependentes['nome'][compara_normalizar_texto($linha['nome'])])) {
                $depNome = $dependentes['nome'][compara_normalizar_texto($linha['nome'])];
            }
            $resultado['so_arquivo'][] = array(
                'arquivo' => $linha,
                'dependente' => $depNome,
                'origem' => ''
            );
            $resultado['totais']['so_arquivo']++;
            continue;
        }

        if ($prop !== null) {
            $campos = array(
                'nome' => compara_campos_texto($prop['nome'], $linha['nome']),
                'telefone' => compara_campos_telefone($prop['telefone'], $linha['celular'], $linha['telefone']),
                'email' => compara_campos_email($prop['email'], $linha['email'], $linha['email_logs']),
                'endereco' => compara_campos_texto($prop['endereco'], $linha['endereco']),
                'cidade' => compara_campos_texto($prop['cidade'], $linha['cidade']),
                'cep' => compara_campos_cep($prop['cep'], $linha['cep']),
                'unidade' => compara_campos_unidade($prop['unidades'], $linha['matricula'])
            );
            $sistema = $prop;
            $sistema['tipo_cadastro'] = 'proprietario';
        } else {
            $campos = compara_campos_dependente($dep, $linha);
            $sistema = compara_sistema_de_dependente($dep);
        }

        $temDivergencia = false;
        foreach ($campos as $campo) {
            if ($campo['status'] === 'divergente') {
                $temDivergencia = true;
                break;
            }
        }
        $status = $temDivergencia ? 'divergente' : 'conforme';
        $item = array(
            'cpf' => $cpf,
            'status' => $status,
            'origem' => $origem,
            'sistema' => $sistema,
            'arquivo' => $linha,
            'campos' => $campos,
            'dependente' => $dep
        );
        $resultado['vinculados'][] = $item;
        $resultado['totais']['vinculados']++;
        $resultado['totais'][$status]++;
        if ($origem === 'proprietario' || $origem === 'ambos') {
            $resultado['totais']['origem_proprietario']++;
        }
        if ($origem === 'dependente' || $origem === 'ambos') {
            $resultado['totais']['origem_dependente']++;
        }
    }

    foreach ($proprietarios as $cpf => $prop) {
        if ($cpf === '' || $cpf === '03699672000125') {
            continue;
        }
        if (!isset($porCpfArquivo[$cpf])) {
            $resultado['so_sistema'][] = array(
                'sistema' => $prop,
                'origem' => 'proprietario',
                'dependente' => isset($dependentes['doc'][$cpf]) ? $dependentes['doc'][$cpf] : null
            );
            $resultado['totais']['so_sistema']++;
        }
        if (empty($prop['unidades'])) {
            $resultado['sem_unidade'][] = array(
                'sistema' => $prop,
                'origem' => 'proprietario',
                'arquivo' => isset($porCpfArquivo[$cpf]) ? $porCpfArquivo[$cpf][0] : null,
                'linhas_arquivo' => isset($porCpfArquivo[$cpf]) ? $porCpfArquivo[$cpf] : array()
            );
            $resultado['totais']['sem_unidade']++;
        }
    }

    foreach ($dependentes['doc'] as $cpf => $dep) {
        if (isset($porCpfArquivo[$cpf]) || isset($proprietarios[$cpf])) {
            continue;
        }
        $resultado['so_sistema'][] = array(
            'sistema' => compara_sistema_de_dependente($dep),
            'origem' => 'dependente',
            'dependente' => $dep
        );
        $resultado['totais']['so_sistema']++;
    }

    return $resultado;
}

function compara_rotulo_status($status)
{
    $mapa = array(
        'conforme' => 'Conforme',
        'divergente' => 'Divergente',
        'igual' => 'Igual',
        'so_arquivo' => 'Só no arquivo',
        'so_sistema' => 'Só no sistema',
        'sem_cpf' => 'Sem CPF',
        'nao_informado' => 'Não informado',
        'cpf_repetido' => 'CPF com nomes diferentes',
        'sem_unidade' => 'Sem unidade'
    );
    return isset($mapa[$status]) ? $mapa[$status] : $status;
}

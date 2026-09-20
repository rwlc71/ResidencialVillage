<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .bio-wrap { padding: 0 16px 24px; }
    .bio-box { border: 1px solid #ccc; padding: 12px 16px; margin: 12px 0; background: #fafafa; }
    .bio-box h3 { margin: 0 0 10px; font-size: 15px; color: #224; }
    .bio-actions input, .bio-actions button { margin: 4px 6px 4px 0; padding: 6px 12px; cursor: pointer; }
    .bio-status { padding: 8px 10px; margin: 8px 0; border-left: 4px solid #888; background: #fff; display: none; }
    .bio-status.ok { border-color: #2e7d32; display: block; }
    .bio-status.err { border-color: #c62828; display: block; }
    .bio-status.info { border-color: #1565c0; display: block; }
    table.bio-table { width: 100%; border-collapse: collapse; font-size: 12px; background: #fff; }
    table.bio-table th, table.bio-table td { border: 1px solid #ddd; padding: 5px 7px; text-align: left; }
    table.bio-table th { background: #e8eef5; }
    .tag-ok { color: #1b5e20; font-weight: bold; }
    .tag-pend { color: #b71c1c; font-weight: bold; }
    .cfg-grid label { display: inline-block; width: 120px; font-weight: bold; font-size: 12px; }
    .cfg-grid div { margin: 6px 0; }
    .cfg-grid input, .cfg-grid select { padding: 4px; }
    .bio-note { font-size: 12px; color: #444; line-height: 1.45; }
</style>

<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";

$tipoAcesso = isset($_COOKIE['tipo_acesso']) ? $_COOKIE['tipo_acesso'] : '';
$permitidos = array('adm', 'sup', 'seg', 'master', 'cord');
if (!in_array($tipoAcesso, $permitidos, true)) {
    echo "<meta http-equiv='refresh' content='0; URL=home.php'>
    <script>alert('Acesso permitido apenas a Administrativo/Segurança.');</script>";
    return die;
}

require_once "lib/ControlIdSyncService.php";
$service = new ControlIdSyncService($con);
$config = $service->getConfig();
$schemePainel = !empty($config['use_https']) ? 'https' : 'http';
$hostAtivo = isset($config['host']) ? $config['host'] : '';
$painelUrl = $schemePainel . '://' . $hostAtivo . ':' . (int) $config['port'] . '/#/';
$origemHost = isset($config['host_resolvido_origem']) ? $config['host_resolvido_origem'] : '';
$labelOrigem = array(
    'local' => 'IDCONTROL neste PC (127.0.0.1)',
    'lan' => 'rede interna (LAN)',
    'publico' => 'máquina remota do IDCONTROL (IPv4:30443)'
);
$origemTexto = isset($labelOrigem[$origemHost]) ? $labelOrigem[$origemHost] : $origemHost;
$destinoAtual = isset($config['destino']) ? $config['destino'] : 'remoto';
include "topo.php";
?>
<div id="conteudo">
    <div id="cont" class="bio-wrap">
        <h2>&nbsp;&nbsp;Validar Biometria — Integração Control iD</h2>
        <hr>
        <p class="bio-note">
            Integração com o <b>IDCONTROL / iDSecure</b>.
            <code>45.71.177.247</code> é o IPv4 da máquina que roda o IDCONTROL
            — não há URL <code>/ResidencialVillage</code> nesse IP.
            Este localhost conecta em <code><?= htmlspecialchars($painelUrl) ?></code>.
            Tela só de teste: <a href="teste_wamp_remoto.php">Teste IDCONTROL remoto</a>.
        </p>

        <div id="bioStatus" class="bio-status"></div>

        <div class="bio-box">
            <h3>1. Configuração do IDCONTROL</h3>
            <div class="cfg-grid">
                <div>
                    <label>Tipo:</label>
                    <select id="cfg_tipo">
                        <option value="idsecure" <?= (isset($config['tipo']) ? $config['tipo'] : '') === 'idsecure' || !isset($config['tipo']) ? 'selected' : '' ?>>iDSecure / IDCONTROL</option>
                        <option value="equipamento" <?= (isset($config['tipo']) && $config['tipo'] === 'equipamento') ? 'selected' : '' ?>>Equipamento direto (API .fcgi)</option>
                    </select>
                </div>
                <div>
                    <label>Destino:</label>
                    <select id="cfg_destino">
                        <option value="remoto" <?= $destinoAtual === 'remoto' ? 'selected' : '' ?>>Remoto — IPv4 da máquina do IDCONTROL</option>
                        <option value="local" <?= $destinoAtual === 'local' ? 'selected' : '' ?>>Local — 127.0.0.1 (neste PC)</option>
                        <option value="lan" <?= $destinoAtual === 'lan' ? 'selected' : '' ?>>LAN — IP interno 192.168.x.x</option>
                    </select>
                </div>
                <div>
                    <label>IPv4 remoto:</label>
                    <input type="text" id="cfg_host_publico" value="<?= htmlspecialchars(isset($config['host_publico']) ? $config['host_publico'] : '45.71.177.247') ?>" size="18" />
                    <label style="width:50px;margin-left:12px;">Porta:</label>
                    <input type="text" id="cfg_port" value="<?= (int) $config['port'] ?>" size="5" />
                </div>
                <div>
                    <label>Usuário API:</label>
                    <input type="text" id="cfg_login" value="<?= htmlspecialchars($config['login']) ?>" size="16" />
                    <label style="width:60px;margin-left:12px;">Senha:</label>
                    <input type="password" id="cfg_password" value="" size="16" placeholder="(preencher p/ alterar)" />
                </div>
                <div>
                    <label>Modo:</label>
                    <select id="cfg_modo">
                        <option value="standalone" <?= $config['modo'] === 'standalone' ? 'selected' : '' ?>>Standalone</option>
                        <option value="online" <?= $config['modo'] === 'online' ? 'selected' : '' ?>>Online</option>
                    </select>
                    &nbsp;
                    <label style="width:auto;"><input type="checkbox" id="cfg_https" <?= !empty($config['use_https']) ? 'checked' : '' ?> /> HTTPS</label>
                </div>
            </div>
            <div class="bio-actions">
                <button type="button" id="btnSalvarConfig">Salvar configuração</button>
                <button type="button" id="btnTestar">Testar conexão</button>
                <a href="<?= htmlspecialchars($painelUrl) ?>" target="_blank" style="margin-left:8px;">Abrir IDCONTROL</a>
            </div>
            <p class="bio-note">
                Destino ativo: <b><?= htmlspecialchars($origemTexto) ?></b> —
                <code><?= htmlspecialchars($painelUrl) ?></code>
            </p>
        </div>

        <div class="bio-box">
            <h3>Para o IPv4 responder daqui</h3>
            <ol class="bio-note" style="margin:0; padding-left:20px;">
                <li>No roteador, redirecione a porta <b>30443 TCP</b> para o PC do IDCONTROL.</li>
                <li>Libere 30443 no Firewall do Windows desse PC.</li>
                <li>O IDCONTROL precisa aceitar conexão de rede, não só localhost.</li>
            </ol>
            <div id="bioSolucoes" class="bio-note" style="margin-top:10px;"></div>
        </div>

        <div class="bio-box">
            <h3>2. Sincronização cadastral</h3>
            <p class="bio-note">
                Envia/atualiza nomes e matrículas (CPF/documento) no Control iD.
                Depois use “Capturar biometria” com a pessoa no leitor.
            </p>
            <div class="bio-actions">
                <button type="button" id="btnComparar">Comparar bases</button>
                <button type="button" id="btnSyncAll">Sincronizar todos (local → equipamento)</button>
                <button type="button" id="btnAtualizarListas">Atualizar vínculos / logs</button>
            </div>
            <div id="resumoComparacao" class="bio-note" style="margin-top:8px;"></div>
        </div>

        <div class="bio-box">
            <h3>3. Pessoas locais × equipamento</h3>
            <div style="max-height:320px; overflow:auto;">
                <table class="bio-table" id="tblComparacao">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nome</th>
                            <th>Documento</th>
                            <th>No Control iD?</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="5">Clique em “Comparar bases” para carregar.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bio-box">
            <h3>4. Vínculos sincronizados</h3>
            <div style="max-height:260px; overflow:auto;">
                <table class="bio-table" id="tblVinculos">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nome</th>
                            <th>Matrícula</th>
                            <th>ID Device</th>
                            <th>Status</th>
                            <th>Biometria</th>
                            <th>Último sync</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="7">Carregando...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bio-box">
            <h3>5. Log de sincronização</h3>
            <div style="max-height:220px; overflow:auto;">
                <table class="bio-table" id="tblLogs">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Ação</th>
                            <th>Origem</th>
                            <th>OK?</th>
                            <th>Mensagem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="5">Carregando...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var endpoint = 'funcoes/controlid_acao.php';

    function setStatus(msg, tipo) {
        var el = $('#bioStatus');
        el.removeClass('ok err info').addClass(tipo || 'info').text(msg).show();
    }

    function post(acao, data) {
        data = data || {};
        data.acao = acao;
        return $.ajax({
            url: endpoint,
            method: 'POST',
            data: data,
            dataType: 'json'
        });
    }

    function esc(s) {
        return $('<div/>').text(s == null ? '' : s).html();
    }

    $('#btnTestar').on('click', function () {
        setStatus('Testando conexão com o Control iD...', 'info');
        post('testar').done(function (r) {
            if (r.success) {
                setStatus('OK: ' + r.message, 'ok');
                $('#bioSolucoes').html('');
            } else {
                var extra = r.tcp_detalhe ? ' | TCP: ' + r.tcp_detalhe : '';
                setStatus('Falha: ' + (r.message || 'sem detalhe') + extra, 'err');
                if (r.solucoes && r.solucoes.length) {
                    var html = '<b>Ações sugeridas para este erro:</b><ul>';
                    r.solucoes.forEach(function (s) { html += '<li>' + esc(s) + '</li>'; });
                    html += '</ul>';
                    $('#bioSolucoes').html(html);
                }
            }
        }).fail(function () {
            setStatus('Erro de comunicação com o servidor PHP.', 'err');
        });
    });

    $('#btnSalvarConfig').on('click', function () {
        post('salvar_config', {
            tipo: $('#cfg_tipo').val(),
            destino: $('#cfg_destino').val(),
            host_publico: $('#cfg_host_publico').val(),
            port: $('#cfg_port').val(),
            login: $('#cfg_login').val(),
            password: $('#cfg_password').val(),
            modo: $('#cfg_modo').val(),
            use_https: $('#cfg_https').is(':checked') ? '1' : '0'
        }).done(function (r) {
            setStatus(r.message || (r.success ? 'Salvo' : 'Erro'), r.success ? 'ok' : 'err');
            $('#cfg_password').val('');
            if (r.success) {
                window.location.reload();
            }
        }).fail(function () {
            setStatus('Erro ao salvar configuração.', 'err');
        });
    });

    $('#btnSyncAll').on('click', function () {
        if (!confirm('Sincronizar todos os proprietários e dependentes para o Control iD?')) return;
        setStatus('Sincronizando cadastros... isso pode levar alguns minutos.', 'info');
        post('sincronizar_todos').done(function (r) {
            setStatus(r.message || JSON.stringify(r), r.success ? 'ok' : 'err');
            carregarVinculos();
            carregarLogs();
        }).fail(function () {
            setStatus('Erro na sincronização.', 'err');
        });
    });

    function renderComparacao(r) {
        var tbody = $('#tblComparacao tbody').empty();
        if (!r.success) {
            tbody.append('<tr><td colspan="5">' + esc(r.message) + '</td></tr>');
            return;
        }
        $('#resumoComparacao').html(
            'Local: <b>' + r.total_local + '</b> &nbsp;|&nbsp; No dispositivo: <b>' + r.total_dispositivo +
            '</b> &nbsp;|&nbsp; Pendentes de envio: <b>' + r.pendentes_envio +
            '</b> &nbsp;|&nbsp; Só no dispositivo: <b>' + r.somente_dispositivo + '</b>'
        );
        if (!r.locais || !r.locais.length) {
            tbody.append('<tr><td colspan="5">Nenhum cadastro local.</td></tr>');
            return;
        }
        r.locais.forEach(function (item) {
            var status = item.no_dispositivo
                ? '<span class="tag-ok">Sim</span>'
                : '<span class="tag-pend">Não</span>';
            var acoes =
                '<button type="button" class="btn-sync-um" data-tipo="' + esc(item.tipo) + '" data-id="' + esc(item.id) + '">Sync</button> ' +
                '<button type="button" class="btn-bio" data-tipo="' + esc(item.tipo) + '" data-id="' + esc(item.id) + '">Capturar biometria</button>';
            tbody.append(
                '<tr><td>' + esc(item.tipo) + '</td><td>' + esc(item.nome) + '</td><td>' +
                esc(item.documento) + '</td><td>' + status + '</td><td>' + acoes + '</td></tr>'
            );
        });
    }

    $('#btnComparar').on('click', function () {
        setStatus('Comparando base local com o equipamento...', 'info');
        post('comparar').done(function (r) {
            renderComparacao(r);
            setStatus(r.success ? 'Comparação concluída.' : (r.message || 'Falha'), r.success ? 'ok' : 'err');
        }).fail(function () {
            setStatus('Erro ao comparar bases.', 'err');
        });
    });

    $(document).on('click', '.btn-sync-um', function () {
        var tipo = $(this).data('tipo');
        var id = $(this).data('id');
        setStatus('Sincronizando ' + tipo + ' #' + id + '...', 'info');
        post('sincronizar_um', { tipo: tipo, id: id }).done(function (r) {
            setStatus(r.message || '', r.success ? 'ok' : 'err');
            $('#btnComparar').click();
            carregarVinculos();
            carregarLogs();
        });
    });

    $(document).on('click', '.btn-bio', function () {
        var tipo = $(this).data('tipo');
        var id = $(this).data('id');
        if (!confirm('Iniciar captura biométrica no equipamento agora? A pessoa deve estar no leitor.')) return;
        setStatus('Solicitando captura biométrica...', 'info');
        post('solicitar_biometria', { tipo: tipo, id: id }).done(function (r) {
            setStatus(r.message || '', r.success ? 'ok' : 'err');
            carregarVinculos();
            carregarLogs();
        });
    });

    function carregarVinculos() {
        post('listar_vinculos').done(function (r) {
            var tbody = $('#tblVinculos tbody').empty();
            if (!r.success || !r.vinculos || !r.vinculos.length) {
                tbody.append('<tr><td colspan="7">Nenhum vínculo ainda.</td></tr>');
                return;
            }
            r.vinculos.forEach(function (v) {
                tbody.append(
                    '<tr><td>' + esc(v.tipo_origem) + '</td><td>' + esc(v.nome) +
                    '</td><td>' + esc(v.registration) + '</td><td>' + esc(v.controlid_user_id) +
                    '</td><td>' + esc(v.status_sync) + '</td><td>' +
                    (v.biometria_cadastrada == 1 ? 'Sim' : 'Não') +
                    '</td><td>' + esc(v.dthr_sync) + '</td></tr>'
                );
            });
        });
    }

    function carregarLogs() {
        post('listar_logs').done(function (r) {
            var tbody = $('#tblLogs tbody').empty();
            if (!r.success || !r.logs || !r.logs.length) {
                tbody.append('<tr><td colspan="5">Sem logs.</td></tr>');
                return;
            }
            r.logs.forEach(function (l) {
                tbody.append(
                    '<tr><td>' + esc(l.dthr) + '</td><td>' + esc(l.acao) +
                    '</td><td>' + esc((l.tipo_origem || '') + (l.id_origem ? ' #' + l.id_origem : '')) +
                    '</td><td>' + (l.sucesso == 1 ? '<span class="tag-ok">Sim</span>' : '<span class="tag-pend">Não</span>') +
                    '</td><td>' + esc(l.mensagem) + '</td></tr>'
                );
            });
        });
    }

    $('#btnAtualizarListas').on('click', function () {
        carregarVinculos();
        carregarLogs();
        setStatus('Listas atualizadas.', 'info');
    });

    carregarVinculos();
    carregarLogs();
})();
</script>
<?php include "rodape.php"; ?>

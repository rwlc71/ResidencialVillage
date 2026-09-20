<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "funcoes/facial_lib.php";
facial_garantir_tabelas();
$usuario = facial_usuario_atual();
if ($usuario['tipo_acesso'] !== 'adm' && $usuario['tipo_acesso'] !== 'sup') {
    echo "<meta http-equiv='refresh' content='0; URL=home.php'>
          <script>alert('Configuração do portão restrita à administração.');</script>";
    return;
}
include "topo.php";
$cfg = facial_config();
?>
<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<link rel="stylesheet" href="css/facial.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<div id="conteudo">
    <div id="cont" class="fac-wrap">
        <h2>Portão eletrônico — acesso facial</h2>
        <p class="fac-sub">Informe a URL do controlador do portão (relé, Intelbras, Control iD, etc.). Quando o reconhecimento liberar a pessoa, o sistema dispara este endereço para abrir/destravar o portão.</p>
        <hr />
        <div class="fac-box">
            <form id="facCfg">
                <div class="fac-row">
                    <label>URL do comando de abertura</label>
                    <input type="text" name="url_portao" value="<?= htmlspecialchars($cfg['url_portao'], ENT_QUOTES, 'UTF-8') ?>" placeholder="http://192.168.0.50/relay/0?turn=on" />
                </div>
                <div class="fac-row">
                    <label>Método HTTP</label>
                    <select name="metodo_portao">
                        <option value="GET" <?= $cfg['metodo_portao'] === 'GET' ? 'selected' : '' ?>>GET</option>
                        <option value="POST" <?= $cfg['metodo_portao'] === 'POST' ? 'selected' : '' ?>>POST</option>
                    </select>
                </div>
                <div class="fac-row">
                    <label>Token / senha (opcional, enviado como parâmetro token)</label>
                    <input type="text" name="token_portao" value="<?= htmlspecialchars($cfg['token_portao'], ENT_QUOTES, 'UTF-8') ?>" />
                </div>
                <div class="fac-row">
                    <label>Limiar de reconhecimento (0,35 mais rigoroso — 0,60 mais permissivo)</label>
                    <input type="text" name="limiar" value="<?= htmlspecialchars($cfg['limiar'], ENT_QUOTES, 'UTF-8') ?>" />
                </div>
                <div class="fac-row">
                    <label>
                        <input type="checkbox" name="ativo" value="1" <?= $cfg['ativo'] === '1' ? 'checked' : '' ?> />
                        Acionar o portão automaticamente após reconhecimento positivo
                    </label>
                </div>
                <p><input type="submit" value="Salvar configuração" /></p>
                <div id="facStatus" class="fac-status"></div>
            </form>
        </div>
        <div class="fac-box">
            <p><b>Como ligar o portão</b></p>
            <p>A maioria dos módulos de relé e controladoras de acesso aceita um endereço HTTP na rede local. Exemplos:</p>
            <ul>
                <li>Relé Shelly / placa genérica: <code>http://IP/relay/0?turn=on</code></li>
                <li>Controladoras com API: URL e token fornecidos pelo fabricante</li>
            </ul>
            <p>Enquanto a URL não estiver preenchida, o reconhecimento continua funcionando e registra a entrada, sem enviar comando físico.</p>
        </div>
    </div>
</div>
<script>
    document.getElementById('facCfg').onsubmit = function (e) {
        e.preventDefault();
        var fd = new FormData(this);
        if (!this.ativo.checked) {
            fd.set('ativo', '0');
        }
        fetch('funcoes/facial_config_salvar.php', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (j) {
                var el = document.getElementById('facStatus');
                el.className = 'fac-status ' + (j.status === 'success' ? 'ok' : 'erro');
                el.textContent = j.message || '';
            });
    };
</script>
<?php include "rodape.php"; ?>

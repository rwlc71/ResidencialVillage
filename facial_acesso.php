<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "funcoes/facial_lib.php";
facial_garantir_tabelas();
$usuario = facial_usuario_atual();
if (!facial_pode_acessar_portao($usuario)) {
    echo "<meta http-equiv='refresh' content='0; URL=home.php'>
          <script>alert('Acesso facial da portaria restrito à segurança e à administração.');</script>";
    return;
}
include "topo.php";
$config = facial_config();
$portaoTxt = ($config['ativo'] === '1' && trim($config['url_portao']) !== '')
    ? 'Portão eletrônico configurado e ativo.'
    : 'O reconhecimento registra o acesso. Configure a URL do portão em Administrativo para destravá-lo automaticamente.';
?>
<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<link rel="stylesheet" href="css/facial.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script src="js/facial.js"></script>

<div id="conteudo">
    <div id="cont" class="fac-wrap fac-acesso">
        <h2>Acesso facial — portaria</h2>
        <p class="fac-sub">Aponte a câmera do celular para o rosto do proprietário ou dependente. Se estiver cadastrado, o acesso é liberado<?php
            if ($config['ativo'] === '1' && trim($config['url_portao']) !== '') {
                echo ' e o portão é acionado';
            }
        ?>.</p>
        <p class="fac-sub"><?= htmlspecialchars($portaoTxt, ENT_QUOTES, 'UTF-8') ?></p>
        <hr />
        <label class="fac-capture fac-big">
            Capturar face (câmera do celular)
            <input type="file" id="facFoto" accept="image/*" capture="user" />
        </label>
        <img id="facPreview" class="fac-preview" alt="Captura" style="margin:12px auto;" />
        <div id="facStatus" class="fac-status"></div>
        <div id="facResult" class="fac-result"></div>
    </div>
</div>
<script>
    var statusEl = document.getElementById('facStatus');
    var resultEl = document.getElementById('facResult');
    FacialApp.carregar(statusEl);

    document.getElementById('facFoto').onchange = function () {
        resultEl.className = 'fac-result';
        resultEl.innerHTML = '';
        FacialApp.processarArquivo(this.files[0], statusEl, document.getElementById('facPreview'), function (ret) {
            if (!ret) {
                return;
            }
            FacialApp.post('funcoes/facial_reconhecer.php', {
                descritor: JSON.stringify(ret.descritor),
                foto: ret.foto
            }, function (j) {
                if (j.resultado === 'liberado') {
                    resultEl.className = 'fac-result liberado';
                    var portao = (j.portao && j.portao.msg) ? j.portao.msg : '';
                    resultEl.innerHTML = '<h3>ACESSO LIBERADO</h3>' +
                        '<p><b>' + (j.nome || '') + '</b></p>' +
                        '<p>' + (j.tipo || '') + (j.unidade ? '<br>' + j.unidade : '') + '</p>' +
                        '<p>' + portao + '</p>';
                    FacialApp.log(statusEl, j.message, 'ok');
                } else {
                    resultEl.className = 'fac-result negado';
                    resultEl.innerHTML = '<h3>ACESSO NEGADO</h3><p>' + (j.message || 'Face não cadastrada.') + '</p>';
                    FacialApp.log(statusEl, j.message || 'Negado', 'erro');
                }
            });
        });
        this.value = '';
    };
</script>
<?php include "rodape.php"; ?>

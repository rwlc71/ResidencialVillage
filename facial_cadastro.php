<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "funcoes/facial_lib.php";
facial_garantir_tabelas();
$usuario = facial_usuario_atual();
if (!facial_pode_gerenciar($usuario)) {
    echo "<meta http-equiv='refresh' content='0; URL=home.php'>
          <script>alert('Acesso não autorizado.');</script>";
    return;
}
include "topo.php";
$ehCon = ($usuario['tipo_acesso'] === 'con') ? '1' : '0';
?>
<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<link rel="stylesheet" href="css/facial.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script src="js/facial.js"></script>

<div id="conteudo">
    <div id="cont" class="fac-wrap">
        <h2>Cadastro de face</h2>
        <p class="fac-sub">Digite o nome ou CPF do proprietário. A lista sugere o cadastro e, ao escolher, carrega o proprietário e os dependentes para registrar a face.</p>
        <hr />
        <div class="fac-box">
            <div class="fac-row">
                <label>Proprietário / dependente</label>
                <input type="text" id="facBusca" placeholder="Digite o nome ou CPF" autocomplete="off" />
                <div id="facAc" class="fac-ac"></div>
            </div>
            <div id="facLista" class="fac-lista">
                <p style="padding:10px;color:#666;">Digite pelo menos 2 letras para buscar.</p>
            </div>
        </div>
        <div class="fac-box">
            <p id="facSel" class="fac-sub">Nenhuma pessoa selecionada.</p>
            <label class="fac-capture">
                Abrir câmera e capturar face
                <input type="file" id="facFoto" accept="image/*" capture="user" />
            </label>
            <img id="facPreview" class="fac-preview" alt="Prévia da face" />
            <div id="facStatus" class="fac-status"></div>
            <p>
                <button type="button" id="facSalvar" disabled>Salvar face</button>
            </p>
        </div>
    </div>
</div>
<script>
    var pessoa = null;
    var captura = null;
    var ehCon = <?= json_encode($ehCon) ?>;
    var statusEl = document.getElementById('facStatus');
    var acEl = document.getElementById('facAc');
    var listaEl = document.getElementById('facLista');
    var tBusca = null;
    FacialApp.carregar(statusEl);

    function escHtml(s) {
        return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function fecharAc() {
        acEl.className = 'fac-ac';
        acEl.innerHTML = '';
    }

    function pintarItem(p) {
        return '<b>' + escHtml(p.nome) + '</b> ' + (p.tem_face ? '<span class="fac-ok">FACE OK</span>' : '') +
            '<small>' + escHtml(p.extra) + (p.unidade ? ' — ' + escHtml(p.unidade) : '') + '</small>';
    }

    function renderFamilia(j) {
        listaEl.innerHTML = '';
        pessoa = null;
        captura = null;
        document.getElementById('facSalvar').disabled = true;
        document.getElementById('facSel').textContent = 'Nenhuma pessoa selecionada.';
        var grupos = j.grupos || [];
        if (!grupos.length || !grupos[0].pessoas || !grupos[0].pessoas.length) {
            listaEl.innerHTML = '<p style="padding:10px;">Nenhum cadastro encontrado para este proprietário.</p>';
            return;
        }
        grupos.forEach(function (g) {
            var tit = document.createElement('div');
            tit.className = 'fac-grupo-tit';
            var nomeProp = g.proprietario ? g.proprietario.nome : 'Unidade';
            var qtdDep = g.dependentes ? g.dependentes.length : 0;
            tit.textContent = nomeProp + ' — ' + qtdDep + ' dependente(s) para cadastro de face';
            listaEl.appendChild(tit);
            (g.pessoas || []).forEach(function (p) {
                var b = document.createElement('button');
                b.type = 'button';
                b.className = 'fac-item';
                b.innerHTML = pintarItem(p);
                b.onclick = function () {
                    pessoa = p;
                    captura = null;
                    document.getElementById('facSalvar').disabled = true;
                    var itens = listaEl.querySelectorAll('.fac-item');
                    for (var i = 0; i < itens.length; i++) { itens[i].className = 'fac-item'; }
                    b.className = 'fac-item ativo';
                    document.getElementById('facSel').innerHTML = 'Selecionado: <b>' + escHtml(p.nome) + '</b> (' + escHtml(p.extra) + ')';
                };
                listaEl.appendChild(b);
            });
        });
    }

    function carregarFamilia(idProp) {
        fetch('funcoes/facial_pessoas.php?id_proprietario=' + encodeURIComponent(idProp), { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (j) {
                fecharAc();
                renderFamilia(j);
            });
    }

    function buscarAutocomplete(q) {
        if (!q || q.length < 2) {
            fecharAc();
            if (!ehCon || ehCon === '0') {
                listaEl.innerHTML = '<p style="padding:10px;color:#666;">Digite pelo menos 2 letras para buscar.</p>';
            }
            return;
        }
        fetch('funcoes/facial_pessoas.php?q=' + encodeURIComponent(q), { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (j) {
                if (j.modo === 'familia') {
                    fecharAc();
                    renderFamilia(j);
                    return;
                }
                acEl.innerHTML = '';
                var lista = j.sugestoes || [];
                if (!lista.length) {
                    acEl.innerHTML = '<button type="button">Nenhum proprietário ou dependente encontrado</button>';
                    acEl.className = 'fac-ac aberto';
                    return;
                }
                lista.forEach(function (p) {
                    var b = document.createElement('button');
                    b.type = 'button';
                    b.innerHTML = '<b>' + escHtml(p.nome) + '</b><div class="tipo">' + escHtml(p.rotulo || p.extra) +
                        (p.unidade ? ' — ' + escHtml(p.unidade) : '') + '</div>';
                    b.onclick = function () {
                        document.getElementById('facBusca').value = p.tipo === 'proprietario' ? p.nome : (p.extra || p.nome);
                        carregarFamilia(p.id_proprietario);
                    };
                    acEl.appendChild(b);
                });
                acEl.className = 'fac-ac aberto';
            });
    }

    document.getElementById('facBusca').onkeyup = function () {
        var v = this.value;
        clearTimeout(tBusca);
        tBusca = setTimeout(function () { buscarAutocomplete(v); }, 220);
    };
    document.getElementById('facBusca').onfocus = function () {
        if (this.value.length >= 2) {
            buscarAutocomplete(this.value);
        }
    };
    document.addEventListener('click', function (ev) {
        if (!document.getElementById('facBusca').contains(ev.target) && !acEl.contains(ev.target)) {
            fecharAc();
        }
    });

    if (ehCon === '1') {
        document.getElementById('facBusca').parentNode.style.display = 'none';
        fetch('funcoes/facial_pessoas.php', { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(renderFamilia);
    }

    document.getElementById('facFoto').onchange = function () {
        if (!pessoa) {
            FacialApp.log(statusEl, 'Selecione a pessoa na lista (proprietário ou dependente) antes de capturar a face.', 'erro');
            this.value = '';
            return;
        }
        FacialApp.processarArquivo(this.files[0], statusEl, document.getElementById('facPreview'), function (ret) {
            captura = ret;
            document.getElementById('facSalvar').disabled = !ret;
        });
    };
    document.getElementById('facSalvar').onclick = function () {
        if (!pessoa || !captura) {
            return;
        }
        var btn = this;
        btn.disabled = true;
        FacialApp.post('funcoes/facial_salvar.php', {
            tipo: pessoa.tipo,
            id_pessoa: pessoa.id_pessoa,
            descritor: JSON.stringify(captura.descritor),
            foto: captura.foto
        }, function (j) {
            FacialApp.log(statusEl, j.message || 'Concluído.', j.status === 'success' ? 'ok' : 'erro');
            if (j.status === 'success') {
                carregarFamilia(pessoa.id_proprietario);
            } else {
                btn.disabled = false;
            }
        });
    };
</script>
<?php include "rodape.php"; ?>

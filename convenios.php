<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<script type="text/javascript" src="js/componentes.js"></script>
<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "topo.php";

$parceiros = array(
    array(
        'id' => 'gas',
        'categoria' => 'Serviços',
        'titulo' => 'Revendedores de Gás',
        'local' => 'Caldas Novas',
        'imagem' => 'images/parceiros/gas.gif',
        'itens' => array('Entrega de gás de cozinha e industrial', 'Atendimento por WhatsApp'),
        'contatos' => array(
            array('nome' => 'SETA GÁS', 'tel' => '(64) 9431-5995', 'wa' => '556494315995'),
            array('nome' => 'Gás 7000', 'tel' => '(64) 3455-7000', 'wa' => '556434557000'),
            array('nome' => 'Gás Vitória', 'tel' => '(64) 99240-2285', 'wa' => '5564992402285'),
            array('nome' => 'FORTEGÁS', 'tel' => '(64) 99269-2213', 'wa' => '5564992692213'),
        ),
        'detalhes' => 'Revendedores de gás em Caldas Novas, com atendimento rápido pelo WhatsApp.'
    ),
    array(
        'id' => 'marido',
        'categoria' => 'Serviços',
        'titulo' => 'Marido de Aluguel',
        'local' => 'Etapa Pitangueiras, casa 81',
        'imagem' => 'images/parceiros/faztudo.jpg',
        'itens' => array('Serviços em geral', 'Eletricista', 'Reparos hidráulicos', 'Pintura', 'Iluminação decorativa'),
        'contatos' => array(
            array('nome' => 'WhatsApp', 'tel' => '(35) 90135-653', 'wa' => '5535991035653'),
        ),
        'detalhes' => 'Pequenos reparos e manutenção residencial no próprio Residencial Village.'
    ),
    array(
        'id' => 'mimone',
        'categoria' => 'Artesanato',
        'titulo' => 'MIMONE Artesanato',
        'local' => 'Residencial Village',
        'imagem' => 'images/parceiros/mimone.jpg',
        'itens' => array('Colares de mesa decorativos', 'Enfeite de porta', 'Envio para todo o Brasil', 'Atendimento de segunda a sexta'),
        'instagram' => 'https://www.instagram.com/mimoneartesanato/?igsh=dHRlb3FjZTdiMmJl',
        'contatos' => array(),
        'detalhes' => 'Peças artesanais feitas no condomínio. Pedidos pelo Direct do Instagram.'
    ),
    array(
        'id' => 'pizza',
        'categoria' => 'Alimentação',
        'titulo' => 'Pizzas da Marli',
        'local' => 'Etapa Bougainville, casa 11',
        'imagem' => 'images/parceiros/pizza.jpg',
        'itens' => array('Pizzas de diversos sabores', 'Pães de queijo', 'Biscoitos de queijo'),
        'contatos' => array(
            array('nome' => 'Marli', 'tel' => '(62) 98173-7397', 'wa' => '5562981737397'),
        ),
        'detalhes' => 'Mini pizzas de frango, calabresa e presunto: R$ 5,00 cada. Biscoito de queijo (pacote): R$ 23,00. Pão de queijo (pacote): R$ 23,00.'
    ),
    array(
        'id' => 'restaurar',
        'categoria' => 'Serviços',
        'titulo' => 'Restaurações e terapia',
        'local' => 'Etapa Orquídeas, casa 47',
        'imagem' => 'images/parceiros/restaurar.jpeg',
        'itens' => array('Restauração de peças ornamentais', 'Peças em gesso e cimento', 'Acompanhamento escolar', 'Terapia para a terceira idade'),
        'contatos' => array(
            array('nome' => 'WhatsApp', 'tel' => '(64) 99200-1932', 'wa' => '5564992001932'),
        ),
        'detalhes' => 'Restaurações ornamentais e atendimento psicopedagógico no Residencial Village.'
    ),
    array(
        'id' => 'empadas',
        'categoria' => 'Alimentação',
        'titulo' => 'Empadas da Cleia',
        'local' => 'Residencial Village',
        'imagem' => 'images/parceiros/empadas.jpeg',
        'itens' => array('Empadas', 'Coxinhas', 'Encomendas'),
        'contatos' => array(
            array('nome' => 'Cleia', 'tel' => '(62) 98173-7397', 'wa' => '5561983078521'),
        ),
        'detalhes' => 'Sabores: frango com requeijão, carne de sol na nata, calabresa com requeijão, palmito e chocolate. Reserve pelo WhatsApp.'
    ),
    array(
        'id' => 'faxina',
        'categoria' => 'Serviços',
        'titulo' => 'Faxina residencial',
        'local' => 'Etapa Pitangueiras, casa 35',
        'imagem' => 'images/parceiros/faxina.jpg',
        'itens' => array('Limpeza de quartos, sala e cozinha', 'Banheiros, varanda e área de serviço', 'Garagem'),
        'contatos' => array(
            array('nome' => 'Daniele', 'tel' => '(62) 99968-9135', 'wa' => '5562999689135'),
        ),
        'detalhes' => 'Faxina/limpeza residencial. Valor de referência: R$ 150,00.'
    ),
    array(
        'id' => 'boticario',
        'categoria' => 'Beleza',
        'titulo' => 'Boticário, Eudora e O.U.i',
        'local' => 'Etapa Pitangueiras, casa 35',
        'imagem' => 'images/parceiros/revenda.jpg',
        'itens' => array('Perfumaria', 'Cuidados pessoais', 'Cabelo e maquiagem', 'Kits presente'),
        'contatos' => array(
            array('nome' => 'Daniele', 'tel' => '(62) 99968-9135', 'wa' => '5562999689135'),
        ),
        'detalhes' => 'Revenda de perfumaria e cuidados pessoais, com descontos. Peça pelo WhatsApp.'
    ),
);

$categorias = array();
foreach ($parceiros as $p) {
    $categorias[$p['categoria']] = true;
}
$categorias = array_keys($categorias);
sort($categorias);
?>
<style>
    .cv-wrap { padding: 8px 6px 20px; }
    .cv-intro {
        color: #333;
        font-size: 15px;
        line-height: 1.5;
        margin: 8px 0 16px;
    }
    .cv-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        margin: 12px 0 18px;
    }
    .cv-busca {
        flex: 1 1 260px;
        min-width: 220px;
        padding: 9px 12px;
        border: 1px solid #c5c5c5;
        border-radius: 6px;
        font-size: 14px;
    }
    .cv-chip {
        border: 1px solid #191970 !important;
        background: #fff !important;
        color: #191970 !important;
        border-radius: 16px;
        padding: 6px 12px;
        cursor: pointer;
        font-size: 13px;
        box-shadow: none;
        transform: none !important;
    }
    .cv-chip:hover {
        background: #eef0fb !important;
        color: #191970 !important;
        transform: none !important;
    }
    .cv-chip.ativo,
    .cv-chip.ativo:hover {
        background: #191970 !important;
        color: #fff !important;
    }
    .cv-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
    }
    .cv-card {
        width: 318px;
        max-width: 100%;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .cv-card:hover {
        box-shadow: 0 6px 16px rgba(25,25,112,0.18);
        transform: translateY(-2px);
    }
    .cv-card img.foto {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background: #f3f3f3;
        cursor: pointer;
    }
    .cv-card-body { padding: 12px 14px 14px; }
    .cv-cat {
        display: inline-block;
        background: #FC0;
        color: #191970;
        font-size: 11px;
        font-weight: bold;
        padding: 2px 8px;
        border-radius: 10px;
        margin-bottom: 6px;
    }
    .cv-card h3 {
        margin: 4px 0 6px;
        color: #191970;
        font-size: 18px;
    }
    .cv-local {
        color: #555;
        font-size: 13px;
        margin-bottom: 8px;
    }
    .cv-card ul {
        margin: 0 0 10px 18px;
        padding: 0;
        color: #333;
        font-size: 13px;
    }
    .cv-acoes {
        margin-top: auto;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .cv-btn {
        display: inline-block;
        text-decoration: none;
        padding: 7px 10px;
        border-radius: 5px;
        font-size: 13px;
        font-weight: bold;
    }
    .cv-btn-wa { background: #25D366; color: #fff; }
    .cv-btn-ig { background: #C13584; color: #fff; }
    .cv-btn-info {
        background: #191970 !important;
        color: #fff !important;
        border: 0;
        cursor: pointer;
        transform: none !important;
    }
    .cv-btn-info:hover { background: #2a2a9a !important; }
    .cv-vazio {
        display: none;
        width: 100%;
        text-align: center;
        padding: 30px 10px;
        color: #666;
        font-size: 15px;
    }
    .cv-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0; top: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.55);
    }
    .cv-modal-box {
        background: #fff;
        width: 92%;
        max-width: 520px;
        margin: 6% auto;
        padding: 18px 20px 20px;
        border-radius: 10px;
        position: relative;
    }
    .cv-modal-box h3 { color: #191970; margin-top: 0; }
    .cv-close {
        position: absolute;
        right: 12px; top: 6px;
        font-size: 28px;
        cursor: pointer;
        color: #666;
    }
    @media (max-width: 720px) {
        .cv-card { width: 100%; }
    }
</style>

<div id="conteudo">
    <div id="cont">
        <div class="cv-wrap">
            <h2>Convênios e Serviços</h2>
            <hr>
            <p class="cv-intro">
                Uma vantagem exclusiva do Residencial Village é reunir serviços e parcerias de moradores
                e da cidade, incentivando a colaboração entre vizinhos e visitantes. Use a busca ou o filtro
                para encontrar o que precisa e fale direto pelo WhatsApp.
            </p>

            <div class="cv-toolbar">
                <input id="cvBusca" class="cv-busca" type="text" placeholder="Buscar por nome, serviço ou local..." onkeyup="filtrarConvenios()">
                <button type="button" class="cv-chip ativo" data-cat="todos" onclick="filtrarCategoria(this)">Todos</button>
                <?php foreach ($categorias as $cat) { ?>
                    <button type="button" class="cv-chip" data-cat="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>" onclick="filtrarCategoria(this)"><?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?></button>
                <?php } ?>
            </div>

            <div class="cv-grid" id="cvGrid">
                <?php foreach ($parceiros as $p) {
                    $busca = $p['titulo'] . ' ' . $p['categoria'] . ' ' . $p['local'] . ' ' . $p['detalhes'] . ' ' . implode(' ', $p['itens']);
                    foreach ($p['contatos'] as $c) {
                        $busca .= ' ' . $c['nome'] . ' ' . $c['tel'];
                    }
                    ?>
                    <article class="cv-card" data-cat="<?= htmlspecialchars($p['categoria'], ENT_QUOTES, 'UTF-8') ?>" data-busca="<?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?>">
                        <img class="foto" src="<?= htmlspecialchars($p['imagem'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($p['titulo'], ENT_QUOTES, 'UTF-8') ?>" onclick="abrirModal('modal-<?= $p['id'] ?>')" onerror="this.onerror=null;this.style.objectFit='contain';this.src='images/logo.jpg';">
                        <div class="cv-card-body">
                            <span class="cv-cat"><?= htmlspecialchars($p['categoria'], ENT_QUOTES, 'UTF-8') ?></span>
                            <h3><?= htmlspecialchars($p['titulo'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <div class="cv-local"><?= htmlspecialchars($p['local'], ENT_QUOTES, 'UTF-8') ?></div>
                            <ul>
                                <?php foreach ($p['itens'] as $item) { ?>
                                    <li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li>
                                <?php } ?>
                            </ul>
                            <div class="cv-acoes">
                                <?php foreach ($p['contatos'] as $c) {
                                    $rotulo = (count($p['contatos']) > 1) ? $c['nome'] : 'WhatsApp';
                                    ?>
                                    <a class="cv-btn cv-btn-wa" href="https://api.whatsapp.com/send?phone=<?= htmlspecialchars($c['wa'], ENT_QUOTES, 'UTF-8') ?>" target="_blank"><?= htmlspecialchars($rotulo, ENT_QUOTES, 'UTF-8') ?></a>
                                <?php } ?>
                                <?php if (!empty($p['instagram'])) { ?>
                                    <a class="cv-btn cv-btn-ig" href="<?= htmlspecialchars($p['instagram'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">Instagram</a>
                                <?php } ?>
                                <button type="button" class="cv-btn cv-btn-info" onclick="abrirModal('modal-<?= $p['id'] ?>')">Detalhes</button>
                            </div>
                        </div>
                    </article>
                <?php } ?>
                <div id="cvVazio" class="cv-vazio">Nenhum convênio encontrado para essa busca.</div>
            </div>
        </div>
    </div>
</div>

<?php foreach ($parceiros as $p) { ?>
    <div id="modal-<?= $p['id'] ?>" class="cv-modal" onclick="fecharFundo(event, 'modal-<?= $p['id'] ?>')">
        <div class="cv-modal-box">
            <span class="cv-close" onclick="fecharModal('modal-<?= $p['id'] ?>')">&times;</span>
            <h3><?= htmlspecialchars($p['titulo'], ENT_QUOTES, 'UTF-8') ?></h3>
            <p><b>Local:</b> <?= htmlspecialchars($p['local'], ENT_QUOTES, 'UTF-8') ?></p>
            <p><?= htmlspecialchars($p['detalhes'], ENT_QUOTES, 'UTF-8') ?></p>
            <p><b>Serviços:</b></p>
            <ul>
                <?php foreach ($p['itens'] as $item) { ?>
                    <li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li>
                <?php } ?>
            </ul>
            <?php if (!empty($p['contatos'])) { ?>
                <p><b>Contatos:</b></p>
                <?php foreach ($p['contatos'] as $c) { ?>
                    <p>
                        <?= htmlspecialchars($c['nome'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars($c['tel'], ENT_QUOTES, 'UTF-8') ?>
                        <a class="cv-btn cv-btn-wa" href="https://api.whatsapp.com/send?phone=<?= $c['wa'] ?>" target="_blank">WhatsApp</a>
                    </p>
                <?php } ?>
            <?php } ?>
            <?php if (!empty($p['instagram'])) { ?>
                <p><a class="cv-btn cv-btn-ig" href="<?= htmlspecialchars($p['instagram'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">Abrir Instagram</a></p>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<script>
    var categoriaAtiva = 'todos';

    function filtrarCategoria(botao) {
        categoriaAtiva = botao.getAttribute('data-cat');
        var chips = document.querySelectorAll('.cv-chip');
        for (var i = 0; i < chips.length; i++) {
            chips[i].className = 'cv-chip';
        }
        botao.className = 'cv-chip ativo';
        filtrarConvenios();
    }

    function filtrarConvenios() {
        var busca = document.getElementById('cvBusca').value.toLowerCase();
        var cards = document.querySelectorAll('.cv-card');
        var visiveis = 0;
        for (var i = 0; i < cards.length; i++) {
            var cat = cards[i].getAttribute('data-cat');
            var texto = (cards[i].getAttribute('data-busca') || '').toLowerCase();
            var okCat = (categoriaAtiva === 'todos' || cat === categoriaAtiva);
            var okBusca = (busca === '' || texto.indexOf(busca) !== -1);
            var mostrar = okCat && okBusca;
            cards[i].style.display = mostrar ? '' : 'none';
            if (mostrar) visiveis++;
        }
        document.getElementById('cvVazio').style.display = visiveis ? 'none' : 'block';
    }

    function abrirModal(id) {
        document.getElementById(id).style.display = 'block';
    }
    function fecharModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    function fecharFundo(ev, id) {
        if (ev.target.id === id) {
            fecharModal(id);
        }
    }
    document.onkeydown = function (ev) {
        ev = ev || window.event;
        if (ev.keyCode === 27) {
            var modais = document.querySelectorAll('.cv-modal');
            for (var i = 0; i < modais.length; i++) {
                modais[i].style.display = 'none';
            }
        }
    };
</script>
<?php
include "rodape.php";
?>

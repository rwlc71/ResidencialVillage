<?php
// leitorqrcode.php - versão flexível (opção 3)
// Recebe '?local=...' ou abre a câmera para leitura, coleta localização rápida + GPS,
// espera por posição precisa até timeout antes de gravar, solicita apenas usuário.
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Leitor QRCode + Autenticação</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Biblioteca html5-qrcode (use local se preferir) -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<style>
    body, html { margin:0; padding:0; height:100vh; font-family:Arial, sans-serif; background:#000000c0; }
    #modal{ display:flex; align-items:center; justify-content:center; height:100%; }
    .modal-box{ background:white; padding:22px; border-radius:10px; text-align:center; width:92%; max-width:420px; box-shadow:0 6px 24px rgba(0,0,0,0.35); }
    h2{ color:#003d66; margin-bottom:8px; }
    p{ color:#333; margin:6px 0; }
    input[type="text"]{ width:92%; padding:10px; font-size:15px; border-radius:6px; border:1px solid #ccc; margin:8px 0; }
    button{ padding:10px 12px; margin:6px; border-radius:6px; border:none; cursor:pointer; }
    .btn-primary{ background:#0074b7; color:#fff; }
    .btn-danger{ background:#dc3545; color:#fff; }
    #mensagemErro{ color:#b30000; font-size:14px; min-height:20px; }
    #statusLocalizacao{ color:gray; font-size:13px; margin-top:6px; min-height:18px; }
    #debug{ display:none; color:blue; font-size:12px; white-space:pre-line; max-height:160px; overflow:auto; margin-top:8px; }
    @media(max-width:480px){ .modal-box{ padding:16px; } input[type="text"]{ width:95%; } }
</style>
</head>
<body>

<div id="modal">
    <div id="Modalmsg" class="modal-box">
        <h2 id="tituloPrincipal">Leitura de QRCode + Autenticação</h2>
        <p id="instrucoes">Aponte a câmera para o QR Code</p>

        <!-- Leitor (oculto automaticamente se houver ?local=) -->
        <div id="reader" style="width:260px; margin:8px auto;"></div>

        <!-- Área de autenticação -->
        <div id="areaUsuario" style="display:none; margin-top:8px;">
            <div id="mensagemErro"></div>
            <input type="text" id="login" placeholder="Digite seu login" autocomplete="username">
            <div style="display:flex; justify-content:center; gap:8px; margin-top:8px;">
                <button class="btn-primary" onclick="autenticarUsuario()">Autenticar</button>
                <button class="btn-danger" onclick="encerrarApp()">Cancelar</button>
            </div>
        </div>

        <p id="statusLocalizacao">Obtendo localização...</p>

        <!-- Debug (ativa setando DEBUG_ATIVO=true) -->
        <pre id="debug"></pre>
    </div>
</div>

<script>
/* =========================
   CONFIGURAÇÃO
   ========================= */
const DEBUG_ATIVO = false;            // coloque true para testes (exibe debug)
const DESIRED_ACCURACY_METERS = 10;   // precisão desejada (m)
const WAIT_FOR_PRECISE_MS = 4000;     // tempo máximo a esperar pela posição precisa (ms)

/* =========================
   HELPERS
   ========================= */
function debug(msg){
    if(!DEBUG_ATIVO) return;
    const el = document.getElementById('debug');
    el.style.display = 'block';
    el.textContent += msg + "\n";
}

/* =========================
   VARIÁVEIS GLOBAIS
   ========================= */
let codigoQR = null;
let html5QrScanner = null;

let lastCoarse = null;        // posição rápida (rede)
let lastPrecise = null;       // posição com alta precisão
let preciseObtained = false;
let watchId = null;
let localizacaoObtida = false;

/* =========================
   CAPTURA 'local' DA URL
   ========================= */
(function(){
    try {
        const params = new URLSearchParams(window.location.search);
        const p = params.get('local');
        if (p) {
            codigoQR = decodeURIComponent(p.trim());
            debug("QR via URL: " + codigoQR);
            // já mostrar a área de usuário (oculta leitor)
            document.getElementById('reader').style.display = 'none';
            document.getElementById('instrucoes').style.display = 'none';
            document.getElementById('areaUsuario').style.display = 'block';
        }
    } catch(e){
        debug("Erro ao processar URL: " + e.message);
    }
})();

/* =========================
   GEOLOCALIZAÇÃO RÁPIDA + WATCH PRECISO
   Estratégia:
   - getCurrentPosition (rápido, low accuracy) para exibir coords imediatamente
   - iniciar watchPosition com enableHighAccuracy:true para tentar melhorar
   - marcar lastPrecise quando accuracy <= DESIRED_ACCURACY_METERS
   ========================= */
function iniciarLocalizacao(){
    if (!navigator.geolocation) {
        document.getElementById('statusLocalizacao').textContent = 'Navegador não suporta geolocalização.';
        return;
    }

    // opções rápidas (rede/WiFi)
    const optsFast = { enableHighAccuracy: false, timeout: 3000, maximumAge: 15000 };
    const optsPrecise = { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 };

    // 1) posição rápida
    navigator.geolocation.getCurrentPosition(pos => {
        lastCoarse = { lat: pos.coords.latitude, lon: pos.coords.longitude, acc: pos.coords.accuracy, time: Date.now() };
        localizacaoObtida = true;
        document.getElementById('statusLocalizacao').textContent = `Localização rápida: ${lastCoarse.lat.toFixed(5)}, ${lastCoarse.lon.toFixed(5)} (±${Math.round(lastCoarse.acc)}m)`;
        debug("Coarse pos: " + JSON.stringify(lastCoarse));

        // 2) iniciar watch de alta precisão (tentativa de obter precise)
        tryStartHighAccuracyWatch();
    }, err => {
        // se falhar rápido, ainda tentamos watch de alta precisão
        debug("getCurrentPosition (fast) falhou: " + (err && err.message));
        tryStartHighAccuracyWatch();
    }, optsFast);

    // Função que inicia o watch (se já não iniciado)
    function tryStartHighAccuracyWatch() {
        if (watchId !== null) return;
        try {
            watchId = navigator.geolocation.watchPosition(pos => {
                const rec = { lat: pos.coords.latitude, lon: pos.coords.longitude, acc: pos.coords.accuracy, time: Date.now() };
                debug("watchPosition update: acc=" + rec.acc);
                // sempre atualiza ultima coarse se não existia
                if(!lastCoarse) lastCoarse = rec;
                // se precisão boa, guarda como precise
                if (rec.acc !== undefined && rec.acc <= DESIRED_ACCURACY_METERS) {
                    lastPrecise = rec;
                    preciseObtained = true;
                    localizacaoObtida = true;
                    document.getElementById('statusLocalizacao').textContent = `Localização precisa: ${rec.lat.toFixed(5)}, ${rec.lon.toFixed(5)} (±${Math.round(rec.acc)}m)`;
                    debug("Precise obtida: " + JSON.stringify(rec));
                    // se quiser, podemos parar o watch após obter precise
                    // navigator.geolocation.clearWatch(watchId);
                    // watchId = null;
                } else {
                    // atualiza lastCoarse como fallback
                    lastCoarse = rec;
                    document.getElementById('statusLocalizacao').textContent = `Melhor posição: ${rec.lat.toFixed(5)}, ${rec.lon.toFixed(5)} (±${Math.round(rec.acc||0)}m)`;
                }
            }, err => {
                debug("watchPosition erro: " + (err && err.message));
            }, optsPrecise);
        } catch(e) {
            debug("Erro ao iniciar watchPosition: " + e.message);
        }
    }
}

// iniciar geolocalização ao carregar DOM
document.addEventListener('DOMContentLoaded', function(){ iniciarLocalizacao(); });

/* =========================
   UTIL: Esperar precise até timeout
   Retorna uma Promise que resolve com o melhor objeto {lat,lon,acc}
   ========================= */
function awaitPreciseOrTimeout(timeoutMs){
    return new Promise(resolve => {
        if(preciseObtained && lastPrecise){
            debug("Precise já disponível - resolve imediado");
            resolve(lastPrecise);
            return;
        }

        const start = Date.now();
        const checkInterval = 200;
        const timer = setInterval(() => {
            if (preciseObtained && lastPrecise) {
                clearInterval(timer);
                resolve(lastPrecise);
                return;
            }
            if (Date.now() - start >= timeoutMs) {
                clearInterval(timer);
                // fallback: lastPrecise if exists else lastCoarse
                debug("Timeout aguardando precise - fallback");
                resolve(lastPrecise || lastCoarse || null);
            }
        }, checkInterval);
    });
}

/* =========================
   AUTENTICAÇÃO E SALVAMENTO
   - ao autenticar chamamos ensurePreciseThenSave()
   ========================= */
function autenticarUsuario(){
    const login = document.getElementById('login') ? document.getElementById('login').value.trim() : '';
    const msg = document.getElementById('mensagemErro');
    msg.textContent = '';

    if (!login) { msg.textContent = 'Informe o login.'; return; }
    if (!codigoQR) { msg.textContent = 'Código QR não identificado.'; return; }

    debug("Enviando login para login.php: " + login);

    fetch('loginQRCODE.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'login=' + encodeURIComponent(login)
    })
    .then(r => r.text())
    .then(txt => {
        debug("login.php raw: " + txt);
        let data = null;
        try { data = JSON.parse(txt); } catch(e){ msg.textContent = 'Resposta inválida do servidor.'; debug('parse login err: '+e.message); return; }
        if(!data || data.status !== 'ok'){ msg.textContent = data && data.mensagem ? data.mensagem : 'Acesso negado.'; return; }

        // OK, usuário validado - agora garantir posição precisa (até WAIT_FOR... ms)
        ensurePreciseThenSave(data.nome, data.id_empresa);
    })
    .catch(err => {
        msg.textContent = 'Erro de comunicação ao autenticar.';
        debug('fetch login err: ' + (err && err.message));
    });
}

/* =========================
   Garante posição precisa (espera até WAIT_FOR_PRECISE_MS), depois salva
   ========================= */
function ensurePreciseThenSave(nomeUsuario, id_empresa){
    // se já temos precise, salva imediatamente
    if (preciseObtained && lastPrecise){
        debug('Salvando com precise imediata');
        doSave(nomeUsuario, id_empresa, lastPrecise);
        return;
    }

    // caso contrário, aguarda até timeout por precise
    debug('Aguardando posição precisa por até ' + WAIT_FOR_PRECISE_MS + ' ms');
    // espera
    awaitPreciseOrTimeout(WAIT_FOR_PRECISE_MS).then(best => {
        if (!best){
            document.getElementById('mensagemErro').textContent = 'Não foi possível obter localização. Verifique o GPS.';
            debug('Nenhuma posição disponível para salvar');
            return;
        }
        debug('Melhor posição para salvar: ' + JSON.stringify(best));
        doSave(nomeUsuario, id_empresa, best);
    });
}

/* =========================
   Envia salvar.php com coordenadas fornecidas
   ========================= */
function doSave(nomeUsuario, id_empresa, coords){
    // montar hora local
    const agora = new Date();
    const dataHora = agora.getFullYear() + '-' +
        String(agora.getMonth()+1).padStart(2,'0') + '-' +
        String(agora.getDate()).padStart(2,'0') + ' ' +
        String(agora.getHours()).padStart(2,'0') + ':' +
        String(agora.getMinutes()).padStart(2,'0') + ':' +
        String(agora.getSeconds()).padStart(2,'0');

    const payload =
        'usuario=' + encodeURIComponent(nomeUsuario) +
        '&codigo=' + encodeURIComponent(codigoQR) +
        '&latitude=' + encodeURIComponent(coords.lat) +
        '&longitude=' + encodeURIComponent(coords.lon) +
        '&id_empresa=' + encodeURIComponent(id_empresa) +
        '&hora_usuario=' + encodeURIComponent(dataHora);

    debug('Enviar salvar.php payload: ' + payload);

    fetch('salvarQRCODE.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: payload
    })
    .then(r => r.text())
    .then(txt => {
        debug('salvar.php raw: ' + txt);
        let resp = null;
        try { resp = JSON.parse(txt); } catch(e){ document.getElementById('Modalmsg').innerHTML = '<h2 style="color:red">Erro</h2><p>Resposta inválida do servidor.</p>'; debug('parse salvar err: '+e.message); return; }

        if (resp.status === 'ok'){
            document.getElementById('Modalmsg').innerHTML = '<h2>✅ ' + (resp.mensagem||'Registro efetuado') + '</h2>' +
                '<p>Colaborador: <b>' + escapeHtml(nomeUsuario) + '</b></p>' +
                '<button class="btn-primary" onclick="encerrarApp()">Fechar</button>';
        } else {
            document.getElementById('Modalmsg').innerHTML = '<h2 style="color:red">Erro ao gravar</h2><p>' + (resp.mensagem||'Erro') + '</p>' +
                '<button class="btn-primary" onclick="encerrarApp()">Fechar</button>';
        }
    })
    .catch(err => {
        document.getElementById('Modalmsg').innerHTML = '<h2 style="color:red">Erro de comunicação</h2><p>Verifique a conexão.</p>';
        debug('fetch salvar error: ' + (err && err.message));
    });
}

/* =========================
   Iniciar leitor QR (caso não haja ?local=)
   ========================= */
function iniciarLeitorQr(){
    if (codigoQR){
        // já veio via URL: mostrar usuário (foi feito no server-side inicial)
        document.getElementById('areaUsuario').style.display = 'block';
        return;
    }

    if (typeof Html5Qrcode === 'undefined'){
        document.getElementById('instrucoes').textContent = 'Leitor indisponível.';
        debug('Html5Qrcode não carregado');
        return;
    }

    try {
        html5QrScanner = new Html5Qrcode('reader');
        html5QrScanner.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: 250 },
            qrCodeMessage => {
                debug('QR capturado raw: ' + qrCodeMessage);
                // tentar extrair param local de uma URL completa
                try {
                    const u = new URL(qrCodeMessage);
                    const p = u.searchParams.get('local');
                    codigoQR = p ? p.trim() : qrCodeMessage.trim();
                } catch(e) {
                    codigoQR = qrCodeMessage.trim();
                }
                // ocultar leitor e mostrar usuário
                document.getElementById('reader').style.display = 'none';
                document.getElementById('instrucoes').style.display = 'none';
                document.getElementById('areaUsuario').style.display = 'block';
                // start location if not yet started (should already be started)
                iniciarLocalizacao(); // safe to call again
                // stop scanner (best-effort)
                html5QrScanner.stop().catch(()=>{});
            },
            err => {
                // ignora erros pequenos
                debug('leitura QR erro: ' + err);
            }
        ).catch(err => {
            debug('Erro ao iniciar scanner: ' + err);
            document.getElementById('instrucoes').textContent = 'Não foi possível acessar a câmera.';
        });
    } catch(e){
        debug('Exceção iniciarLeitorQr: ' + e);
    }
}

/* =========================
   UTIL & ENCERRAMENTO
   ========================= */
function escapeHtml(s){ if(!s) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }

function stopWatchAndScanner(){
    try { if (watchId !== null) { navigator.geolocation.clearWatch(watchId); watchId = null; } } catch(e){}
    try { if (html5QrScanner) { html5QrScanner.stop().catch(()=>{}); } } catch(e){}
}

function encerrarApp(){
    debug('encerrarApp: tentar parar e fechar');
    stopWatchAndScanner();
    // try close window
    try { window.open('','_self'); window.close(); } catch(e){}
    setTimeout(()=>{ window.location.href = 'home.php'; }, 400);
}

/* =========================
   Inicialização
   ========================= */
document.addEventListener('DOMContentLoaded', function(){
    // iniciar localização (rápida + watch)
    iniciarLocalizacao();
    // iniciar leitor só se não veio param 'local'
    iniciarLeitorQr();
});
</script>
</body>
</html>

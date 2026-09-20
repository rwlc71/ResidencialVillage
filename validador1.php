<?php
include "conexao_validar.php";
include "valida/valida_cpf.php";
include "valida/mascaraCPF.php";
include "valida/mascaras.php";

// Função simples para detectar o tipo de dispositivo
function detectarDispositivo() {
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (strpos($userAgent, 'mobile') !== false || strpos($userAgent, 'android') !== false || strpos($userAgent, 'iphone') !== false) {
        return 'smartphone';
    } elseif (strpos($userAgent, 'tablet') !== false || strpos($userAgent, 'ipad') !== false) {
        return 'tablet';
    } else {
        return 'desktop';
    }
}

date_default_timezone_set('America/Sao_Paulo');

$dispositivo = detectarDispositivo();
$codigo = isset($_REQUEST['codigo']) ? $_REQUEST['codigo'] : '';
$msg = '';
$tipo_alerta = '';
$btns = '';
$dataHoje = date('Y-m-d');
//echo($codigo);
//exit();
// Consulta ao banco de dados
if ($codigo !== '') {
    $sqlBusca = "SELECT a.*, p.nome AS nome_proprietario, 
    u.etapa AS etapa,  u.numero_etapa AS numero_etapa
    FROM audita a
    LEFT JOIN proprietario p ON a.id_proprietario = p.id_proprietario
    LEFT JOIN unidade u ON a.id_unidade = u.id_unidade
    WHERE a.codvalidacao = :codigo";
//    $stmt = $pdo->prepare("SELECT * FROM audita WHERE codvalidacao = :codigo");
    $stmt = $pdo->prepare($sqlBusca);
    $stmt->bindParam(':codigo', $codigo);
    $stmt->execute();
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$registro) {
        $msg = "<b>CÓDIGO DE VALIDAÇÃO INVÁLIDO!</b>";
        $tipo_alerta = "";
        $btns = "<button onclick=\"fecharModal()\">Fechar</button>";
    } else {
        $entrada = $registro['dt_entrada'];
        $saida = $registro['dt_saida'];
        $id_audita = $registro['id_audita'];
        $id_locacao = $registro['id_audita'];
        $doc_idt_resp = $registro['doc_identificacao_resp'];

        $doc_idt_resp = str_replace(".", "", $doc_idt_resp);
        $doc_idt_resp = str_replace("-", "", $doc_idt_resp);
        $doc_idt_resp = str_replace("/", "", $doc_idt_resp);
        $tamanho = strlen($doc_idt_resp);
        if ($tamanho > 11) {
            $doc_idt_resp = mask($doc_idt_resp, '##.###.###/####-##');
        } else {
            $doc_idt_resp = mask($doc_idt_resp, '###.###.###-##');
        }
//        echo($tamanho."<BR>");
//        echo($doc_idt_resp);
//        exit();
        
        $vinculo = "Não informado";
        if ($registro['parentesco']) {
            $vinculo = $registro['parentesco'];
        }
        $nome_proprietario = $registro['nome_proprietario'];
        switch ($registro['etapa']) {
            case 'Azaléia - AZ':
                $etapa = 'AZ/' . $registro['numero_etapa'];
                break;
            case 'Bougainville - BO':
                $etapa = 'BO/' . $registro['numero_etapa'];
                break;
            case 'Gardênia - GA':
                $etapa = 'GA/' . $registro['numero_etapa'];
                ;
                break;
            case 'Jacarandás - JAC':
                $etapa = 'JAC/' . $registro['numero_etapa'];
                break;
            case 'Orquídeas - OR':
                $etapa = 'OR/' . $registro['numero_etapa'];
                break;
            case 'Pitangueiras - PIT':
                $etapa = 'PIT/' . $registro['numero_etapa'];
                break;
        }

//      Consulta hóspedes da reserva
        $sqlHospedes = "SELECT * FROM hospede WHERE id_locacao = :id";
        $stmtHosp = $pdo->prepare($sqlHospedes);
        $stmtHosp->bindParam(':id', $id_locacao, PDO::PARAM_INT);
        $stmtHosp->execute();
        $hospedes = $stmtHosp->fetchAll(PDO::FETCH_ASSOC);
        // Monta HTML da lista de hóspedes
        $listaHospedes = "<ol style='text-align: left; padding-left: 20px;'>";
        foreach ($hospedes as $hospede) {
            if ($hospede['parentesco_hospede'] == "Parente até 4º Grau") {
                $hospede['parentesco_hospede'] = "Familar";
            }
            if ($hospede['parentesco_hospede'] == "Locação por Temporada") {
                $hospede['parentesco_hospede'] = "Temporada";
            }
            $nome = strtoupper($hospede['nome_hospede']);
            $doc = htmlspecialchars($hospede['doc_hospede']);
            $parentesco = htmlspecialchars($hospede['parentesco_hospede']);
            $listaHospedes .= "<li><strong>$nome</strong> ($parentesco)</li>";
        }
        $listaHospedes .= "</ol>";

        $nome = strtoupper(htmlspecialchars($registro['resp_locacao'], ENT_QUOTES, 'UTF-8'));
        $doc_idt_resp = strtoupper(htmlspecialchars($doc_idt_resp, ENT_QUOTES, 'UTF-8'));
        $entrada_efetiva = $registro['dt_entrada_efetiva'];
//echo($saida. '<br>');
//echo($dataHoje. '<br>');
//echo($entrada_efetiva. '<br>');
//exit();
        if (!empty($entrada_efetiva) && $saida >= $dataHoje) {
            // ENTRADA JÁ REGISTRADA - Já foi registrada a entrada
            $dataEfetiva = date('d/m/Y', strtotime($entrada_efetiva));
            $horaEfetiva = date('H:i', strtotime($entrada_efetiva));
            $tipo_alerta = "<b>ENTRADA JÁ REGISTRADA!</b>";
            $msg = "<ul style='text-align: left; padding-left: 20px;'>Proprietário:<strong> {$nome_proprietario} </strong> ";
            $msg = $msg . "Etapa/Unidade:<strong> {$etapa} </strong> ";
            $msg = $msg . "<hr>";
            $msg = $msg . "<li style='text-align: left; padding-left: 10px;'>Período de validade de <b>" . date('d/m/Y', strtotime($entrada)) .
                    " à " . date('d/m/Y', strtotime($saida)) . "</b></li>";
            $msg = $msg . "<li style='text-align: left; padding-left: 10px;'>Data do registro: <b>{$dataEfetiva}</b> às <b>{$horaEfetiva}</b></li>";
            $msg = $msg . "<li style='text-align: left; padding-left: 10px;'>Responsável:<strong> {$nome} </strong> </li>";
            $msg = $msg . "<li style='text-align: left; padding-left: 10px;'>Vínculo:<b> " . $vinculo . "</b></li>";
            $msg = $msg . "<li style='text-align: left; padding-left: 10px;'>RG/CPF::<strong> {$doc_idt_resp} </strong> </li></ul>";
            $msg = $msg . "<b><ul style='text-align: left; padding-left: 20px;'>Hóspedes da reserva:</b></ul> {$listaHospedes}";
            $btns = "<button onclick=\"fecharModal()\">Fechar</button>";
        } elseif ($entrada <= $dataHoje && $saida >= $dataHoje) {
            // AUTORIZAÇÃO VÁLIDA - Está dentro do período permitido
            $msg = "<ul style='text-align: left; padding-left: 20px;'>Proprietário:<strong> {$nome_proprietario} </strong> ";
            $msg = $msg . "Etapa/Unidade:<strong> {$etapa} </strong> </ul>";
            $msg = $msg . "<hr>";
            $msg = $msg . "<li style='text-align: left; padding-left: 10px;'>Hóspede responsável:<strong> {$nome}</strong></li>";
            $msg = $msg . "<li style='text-align: left; padding-left: 10px;'>RG/CPF: <b>" . $doc_idt_resp . "</b></li>";
            $msg = $msg . "<li style='text-align: left; padding-left: 10px;'>Vínculo:<b> " . $vinculo . "</b></li>";
            $msg = $msg . "<li style='text-align: left; padding-left: 10px;'>Período válido de<b> " . date('d/m/Y', strtotime($entrada)) .
                    "</b> à <b>" . date('d/m/Y', strtotime($saida)) . "</b></li></ul>";
            $msg = $msg . "<b><ul style='text-align: left; padding-left: 20px;'>Hóspedes da reserva:</b></ul> {$listaHospedes}";
            $tipo_alerta = "<b>AUTORIZAÇÃO VÁLIDA</b>";
            $btns = "<button onclick=\"registrarEntrada({$id_locacao})\">Registrar Entrada</button>
             <button onclick=\"cancelar()\">Cancelar</button>";
        } elseif ($dataHoje > $saida) {
// A AUTORIZAÇÃO EXPIROU-  Já passou da data de saída
            $msg = "<b>A AUTORIZAÇÃO EXPIROU</b><p>Período válido de<b> " . date('d/m/Y', strtotime($entrada)) .
                    "</b> à <b>" . date('d/m/Y', strtotime($saida)) . "</b>";
            $tipo_alerta = "";
            $btns = "<button onclick=\"fecharModal()\">Fechar</button>";
        } elseif ($dataHoje < $entrada) {
// ENTRADA ANTECIPADA - Ainda não começou a vigência
            $msg = "<ul style='text-align: left; padding-left: 20px;'>Proprietário:<strong> {$nome_proprietario} </strong> ";
            $msg = $msg . "Etapa/Unidade:<strong> {$etapa} </strong> </ul>";
            $msg = $msg . "<hr>";
            $msg = $msg . "<li style='text-align: left; padding-left: 10px;'>Período válido de<b> " . date('d/m/Y', strtotime($entrada)) .
                    "</b> à <b>" . date('d/m/Y', strtotime($saida)) . "</b></li>";
            $msg = $msg . "<li style='text-align: left; padding-left: 10px;'>Responsável:<strong> {$nome} </strong> </li>";
            $msg = $msg . "<li style='text-align: left; padding-left: 10px;'>Vínculo:<strong> {$vinculo} </strong> </li>";
            $msg = $msg . "<li style='text-align: left; padding-left: 10px;'>RG/CPF:<strong> {$doc_idt_resp} </strong> </li></ul>";
//            $msg = $msg . "<br>Período de validade de <b>" . date('d/m/Y', strtotime($entrada)) .
//                    " à " . date('d/m/Y', strtotime($saida)) . "</b></ul>";
            $msg = $msg . "<b><ul style='text-align: left; padding-left: 1px;'>Hóspede(s) da reserva:</b></ul> {$listaHospedes}";
            $tipo_alerta = "<b>ENTRADA ANTECIPADA</b>";
            $btns = "<button onclick=\"fecharModal()\">Fechar</button>";
        }
    }
} else {
    $msg = "<b>CÓDIGO DE VALIDAÇÃO INVÁLIDO!</b>";
    $tipo_alerta = "";
    $btns = "<button onclick=\"fecharModal()\">Fechar</button>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Validação</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body, html {
                margin: 0;
                padding: 0;
                background: #000000c0;
                font-family: Arial, sans-serif;
                height: 100vh;
            }
            #modal {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100%;
            }
            .modal-box {
                background: white;
                padding: 30px 20px;
                border-radius: 10px;
                text-align: center;
                width: 90%;
                max-width: 400px;
                box-shadow: 0 0 20px #000;
            }
            .modal-box h2 {
                font-size: 20px;
                margin-bottom: 15px;
            }
            .modal-box p {
                font-size: 16px;
                margin-bottom: 25px;
            }
            .modal-box button {
                padding: 12px;
                margin: 5px;
                font-size: 15px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                width: 45%;
            }
            .modal-box button:first-child {
                background-color: #28a745;
                color: #fff;
            }
            .modal-box button:last-child {
                background-color: #dc3545;
                color: #fff;
            }
            @media (max-width: 480px) {
                .modal-box {
                    padding: 20px 10px;
                }
                .modal-box h2, .modal-box p {
                    font-size: 16px;
                }
                .modal-box button {
                    font-size: 14px;
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        <div id="modal">
            <div id="Modalmsg" class="modal-box">
                <h2><?= strtoupper($tipo_alerta) ?></h2>
                <p><?= $msg ?></p>
                <?= $btns ?>
            </div>
        </div>

        <!--Modal de login-->
        <div id="modal" style="display:none;">
            <div id="Modalmsg" class="modal-box">
                <h2>IDENTIFICAÇÃO</h2>
                <p>Informe seu login para registrar a entrada:</p>
                <input type="text" id="loginUsuario" placeholder="Digite seu login"
                       style="width: 100%; padding: 8px; font-size: 16px;" />
                <br><br>
                <button onclick="enviarLogin()">Confirmar</button>
                <button onclick="fecharLoginModal()">Cancelar</button>
            </div>
        </div>
        <script>
            function fecharModal() {
                window.location.href = "home.php";
            }

            function cancelar() {
                window.location.href = "home.php";
            }

            function registrarEntrada(idLocacao) {
                console.log(idLocacao);
                // Armazena ID para uso posterior
                window.idLocacaoParaRegistro = idLocacao;

                // Renderiza o modal de login dentro da mesma estrutura Modalmsg
                const modal = document.getElementById('modal');
                const modalMsg = document.getElementById('Modalmsg');

                modalMsg.innerHTML = `
                    <h2>IDENTIFICAÇÃO</h2>
                    <p>Informe seu login para registrar a entrada:</p>
                    <input type="text" id="loginUsuario" placeholder="Digite seu login"
                           style="width: 90%; padding: 8px; font-size: 16px;" />
                    <br><br>
                    <button onclick="enviarLogin()">Confirmar</button>
                    <button onclick="fecharLoginModal()">Cancelar</button>
                `;
                modal.style.display = 'flex';
            }

            function fecharLoginModal() {
                const modal = document.getElementById('modal');
                modal.style.display = 'none';
                window.location.href = "home.php";
            }

            function enviarLogin() {
                const login = document.getElementById('loginUsuario').value.trim();
                if (!login) {
                    alert("Por favor, informe seu login.");
                    return;
                }

                // Envia via AJAX para PHP
                fetch("registrar_entrada.php", {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({
                        id: window.idLocacaoParaRegistro,
                        login: login
                    })
                })
                        .then(response => response.json())
                        .then(data => {
                            fecharLoginModal();
                            if (data.status === 'ok') {
                                alert("Entrada registrada com sucesso!");
                            } else if (data.status === 'ja_registrado') {
                                alert(data.mensagem);
                            } else {
                                alert("Erro: " + data.mensagem);
                            }
                            window.location.href = "home.php";
                        })
                        .catch(err => {
                            alert(error(err));
                            alert("Erro ao registrar entrada.");
                            console.error(err);
                        });
            }
//            function registrarEntrada(id) {
//                console.log(id);
////                window.idLocacaoParaRegistro = id;
//                document.getElementById('loginModal').style.display = 'flex';
//                fetch('registrar_entrada.php?id=' + id)
//                        .then(response => response.json())
//                        .then(data => {
//                            if (data.status === 'ok') {
//                                alert("Entrada registrada com sucesso!");
//                                window.location.href = "home.php";
//                            } else if (data.status === 'ja_registrado') {
//                                alert(data.mensagem);
//                                window.location.href = "home.php";
//                            } else {
//                                alert("Erro: " + data.mensagem);
////                                window.location.href = "home.php";
//                            }
//                        })
//                        .catch(error => {
//                            alert("Erro de comunicação com o servidor.");
//                            console.error(error);
//                        });
//            }
        </script> 
    </body>


</html>

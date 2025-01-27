<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/style.css" type="text/css" rel="stylesheet" />
        <title>Consentimento LGPD</title>
    </head>
    <body>
        <?php
        $usuario = $_REQUEST['dado'];
        ?>
        <!-- Estrutura da Modal -->
        <div id="lgpdModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    Termos de Consentimento LGPD
                </div>
                <div class="modal-body">
                    <p>
                        Ao prosseguir com a autenticação, você concorda com a coleta e uso de seus dados pessoais de acordo com a nossa
                        <strong>Política de Privacidade</strong>. Seus dados serão utilizados exclusivamente para os fins específicos da solução, em conformidade com a Lei Geral de Proteção de Dados (LGPD).
                    </p>
                    <p>
                        Para continuar utilizando a nossa plataforma, é necessário consentir com esses termos. Caso não concorde, o acesso à plataforma será interrompido.
                    </p>
                    <div class="checkbox-container" >
                        <input type="checkbox" id="dontShowAgain" hidden="">
                        <label for="dontShowAgain" hidden>Não mostrar essa mensagem novamente</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="naoaceita()">Não Concordo</button>
                    <button class="btn btn-primary" onclick="aceita('<?= $usuario ?>')">Concordo</button>
                </div>
            </div>
        </div>
        <script src="./js/componentes.js"></script>

        <script>
                        // Mostrar a modal com base no estado salvo no localStorage
                        window.onload = function () {
                            const dontShowAgain = localStorage.getItem('dontShowConsent');
                            if (!dontShowAgain) {
                                document.getElementById('lgpdModal').style.display = 'block';
                            }
                        };
                        async function aceita(dado) {
                            if (!dado) {
                                alert("Você deve se autenticar para ter acesso ao conteúdo da plataforma!");
                                // Redireciona para a página proprietarios.php
                                window.location.href = 'autentica.php';
                            } else {
                                const caminhoCompleto = window.location.pathname;
                                const site = '/' + caminhoCompleto.split('/')[1];
                                let url = site + '/funcoes/gravar_consentimento.php?dado=' + dado;
                                try {
                                    const ret = await fetch(url);
                                    const r = await ret.json();

                                    if (r.status === 'success') {
                                        alert("Você consentiu com nossa Política de Privacidade. Obrigado!");
                                        alert("Agora, você será redirecionado a nossa página de autenticação para utilizar plenamente a solução! Muito obrigado!");
                                        // Redireciona para a página proprietarios.php
//                                        window.location.href = 'proprietarios.php?dado=' + dado;
                                          window.location.href = 'autentica.php?inf=t' ;

                                    } else {
                                        alert("Houve um erro ao processar o consentimento.");
                                    }
                                } catch (error) {
                                    alert("Ocorreu um erro. Por favor, tente novamente.");
                                }
                            }
                        }

                        async function naoaceita() {
                            alert("Você recusou o consentimento. O acesso será interrompido.");
                            // Redireciona para a página autentica.php
                            window.location.href = 'autentica.php';
                        }

        </script>
        <script src=".js/componente.js"></script>

    </body>
</html>
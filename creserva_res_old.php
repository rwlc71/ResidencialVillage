<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link href="css/style.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="css/menu.css" type="text/css" />
    <script type="text/javascript" src="js/componentes.js"></script>
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilo responsivo para o layout principal */
        #conteudo {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }

        #cont {
            margin-top: 20px;
        }

        /* Responsividade da tabela e campos de entrada */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 8px;
            text-align: left;
            font-size: 1rem;
            border-bottom: 1px solid #ddd;
        }

        input[type="text"], input[type="data"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        /* Cores para o cabeçalho da tabela */
        table tr th {
            background-color: #191970;
            color: #F5FFFA;
        }

        /* Estilo para dispositivos menores */
        @media (max-width: 768px) {
            #conteudo {
                padding: 10px;
            }

            table th, table td {
                font-size: 0.9rem;
                padding: 6px;
            }

            input[type="text"], input[type="data"], textarea {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <?php
    session_name('SESSAO_PHP');
    include "conexao.php";
    include "valida/verifica_autenticacao.php";
    include "valida/valida_cpf.php";
    include "valida/mascaraCPF.php";
    include "valida/mascaras.php";
    include "topo.php";

    if ($_REQUEST['dado']) {
        $id_audita = $_REQUEST['dado'];
        $id_locacao = $_REQUEST['dado'];
    } else {
        if ($_REQUEST['codigo']) {
            $consulta = "SELECT * FROM locacao WHERE codvalidacao =  '" . $_REQUEST['codigo'] . "'";
            $consulta = mysql_query($consulta);
            $ln = mysql_fetch_array($consulta);
            if (mysql_num_rows($consulta) != true) {
                echo "<meta http-equiv='refresh' content='0; URL=validar.php'>
                <script type=\"text/javascript\">
                alert(\"CÓDIGO DE VALIDAÇÃO INVÁLIDO!\");
                </script> ";
                return die;
            } else {
                $id_audita = $ln['id_locacao'];
                $id_locacao = $ln['id_locacao'];
            }
        } else {
            echo "<meta http-equiv='refresh' content='0; URL=validar.php'>
                <script type=\"text/javascript\">
                alert(\"CÓDIGO DE VALIDAÇÃO INVÁLIDO!\");
                </script> ";
            return die;
        }
    }

    $consulta = "SELECT aud.*, uni.*, prop.id_proprietario, prop.nome, prop.CPF FROM audita aud "
            . "JOIN proprietario prop ON aud.id_proprietario = prop.id_proprietario"
            . " JOIN unidade uni on aud.id_unidade = uni.id_unidade  "
            . " WHERE aud.id_audita = " . $id_audita . " order by aud.dt_entrada";
    $consulta = mysql_query($consulta);
    $ln = mysql_fetch_array($consulta);

    if (mysql_num_rows($consulta) != true) {
        echo "<meta http-equiv='refresh' content='0; URL=seguranca.php'>
        <script type=\"text/javascript\">
        alert(\"Proprietário não possui reservas cadastradas!\");
        alert(\"Cadastre-as primeiramente!\");
        </script>
      ";
        return die;
    }

    $tamanho = strlen($ln['CPF']);
    $cpf = $tamanho > 11 ? mask($ln['CPF'], '##.###.###/####-##') : mask($ln['CPF'], '###.###.###-##');
    $dt_entrada = DateTime::createFromFormat('Y-m-d', $ln['dt_entrada'])->format('d/m/Y');
    $dt_saida = DateTime::createFromFormat('Y-m-d', $ln['dt_saida'])->format('d/m/Y');
    $telefone = mask(preg_replace('/\D/', '', $ln['contato_resp']), '(##)#####-#####');
    $etapa = match ($ln['etapa']) {
        'Azaléia - AZ' => 'AZ/' . $ln['numero_etapa'],
        'Bougainville - BO' => 'BO/' . $ln['numero_etapa'],
        'Gardênia - GA' => 'GA/' . $ln['numero_etapa'],
        'Jacarandás - JAC' => 'JAC/' . $ln['numero_etapa'],
        'Orquídeas - OR' => 'OR/' . $ln['numero_etapa'],
        'Pitangueiras - PIT' => 'PIT/' . $ln['numero_etapa'],
        default => '',
    };
    ?>
    <div id="conteudo">
        <div id="cont">
            <h2>Dados da Reserva
                <a href="autorizacao.php?id=<?= $id_locacao ?>" target="_blank" title="Visualizar Autorização de hospedagem">
                    <img src="images/ver.jpg" height=20 width=20 align="middle" border="0">
                </a>
            </h2>
            <hr>
            <form method="post" action="funcoes/salvar_complemento.php" enctype="multipart/form-data">
                <table>
                    <tr>
                        <th align="left">Proprietário:</th>
                        <td><input type="text" value="<?= $ln['nome'] ?>" name="nome" disabled /></td>
                    </tr>
                    <tr>
                        <th align="left">Unidade:</th>
                        <td><input type="text" value="<?= $etapa ?>" name="unidade" disabled />
                            Qtde Hóspedes: <input type="data" value="<?= $ln['qtde_hospedes'] ?>" name="qtde_hosp" disabled />
                        </td>
                    </tr> 
                    <tr>
                        <th align="left">Data de entrada:</th>
                        <td><input type="text" value="<?= $dt_entrada ?>" name="dt_entrada" disabled />
                            Data de saída: <input type="data" value="<?= $dt_saida ?>" name="dt_saida" disabled />
                        </td>
                    </tr>
                    <tr>
                        <th align="left">Responsável pela locação:</th>
                        <td><input type="text" value="<?= $ln['resp_locacao'] ?>" name="resp_loc" disabled /></td>
                    </tr>
                    <tr>
                        <th align="left">CPF/RG:</th>
                        <td><input type="text" value="<?= $ln['doc_identificacao_resp'] ?>" name="doc_resp" disabled /></td>
                    </tr>
                    <tr>
                        <th align="left">Telefone do responsável:</th>
                        <td><input type="text" name="telefone" value="<?= $telefone ?>" disabled /></td>
                    </tr>
                    <tr>
                        <th align="left">Informações Complementares:</th>
                        <td><textarea name="ocorrencia" placeholder="Exemplo: placa do carro, marca e modelo..."><?= $ln['complementares'] ?></textarea></td> 
                    </tr>
                </table>
                <br>
                <center>
                    <input type="button" value="Voltar" onclick="JavaScript: window.history.back();">
                    <input type="submit" name="botao" value="Salvar" />
                </center>    
            </form>
            <br />
            <hr/>          
            <br>
            <div class="table-responsive">
                <h3>Hóspedes da reserva</h3>
                <table border="2">
                    <tr>
                        <th>Nome</th>
                        <th>CPF/RG</th>
                        <th>Parentesco</th>
                    </tr>
                    <?php
                    $sql = "SELECT * from hospede where id_locacao = '{$id_locacao}'";
                    $filtro = mysql_query($sql);
                    while ($ln = mysql_fetch_array($filtro)) {
                    ?>
                    <tr>
                        <td><?= strtoupper($ln['nome_hospede']) ?></td>
                        <td><?= strtoupper($ln['doc_hospede']) ?></td>
                        <td><?= strtoupper($ln['parentesco_hospede']) ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <?php include "rodape.php"; ?>
</body>
</html>

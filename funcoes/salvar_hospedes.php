
<?php

session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
include "../valida/verifica_autenticacao.php";
include "senha.php";
include "geraCodigo.php";

$totalHospedes = count($_POST['hospedes']['nome']);

// Itera sobre cada índice
for ($i = 0; $i < $totalHospedes; $i++) {
    // Verifica se todos os campos estão vazios
    if (empty($_POST['hospedes']['nome'][$i]) &&
            empty($_POST['hospedes']['identificacao'][$i]) &&
            empty($_POST['hospedes']['parentesco'][$i])) {

        // Remove o índice de todos os subarrays
        unset($_POST['hospedes']['nome'][$i]);
        unset($_POST['hospedes']['identificacao'][$i]);
        unset($_POST['hospedes']['parentesco'][$i]);
    }
}

$id_locacao = $_POST['id_locacao'];
for ($i = 0; $i < count($_POST['hospedes']['nome']); $i++) {
    if (trim($_POST['hospedes']['parentesco'][$i]) === '') {
        $branco = true;
    }
}

If (!$_POST['hospedes']['nome']) {
    echo json_encode(['status' => 'error', 'message' => 'Atenção: não há informações de hóspedes para serem salvas!']);
    return;
}
$branco = false;
for ($i = 0; $i < count($_POST['hospedes']['nome']); $i++) {
    if (trim($_POST['hospedes']['nome'][$i]) === '') {
        $branco = true;
    }
}
for ($i = 0; $i < count($_POST['hospedes']['identificacao']); $i++) {
    if (trim($_POST['hospedes']['identificacao'][$i]) === '') {
        $branco = true;
    }
}
for ($i = 0; $i < count($_POST['hospedes']['parentesco']); $i++) {
    if (trim($_POST['hospedes']['parentesco'][$i]) === '') {
        $branco = true;
    }
}

if ($branco == true) {
    echo json_encode(['status' => 'error', 'message' => 'Todos os campos são obrigatórios: preencha todos os dados dos hóspedes corretamente para serem salvas!']);
    return;
}
//================================= Gravar no banco - Inclusao de hospedes
$gravou = false;

// Verifica dados  duplicados

for ($z = 0; $z < count($_POST['hospedes']['nome']); $z++) {
    $ident_z = trim(strtoupper($_POST['hospedes']['identificacao'][$z]));
    $nome_z = trim(strtoupper($_POST['hospedes']['nome'][$z]));

    for ($i = 0; $i < count($_POST['hospedes']['nome']); $i++) {
        if ($i !== $z) {
            $nome_i = trim(strtoupper($_POST['hospedes']['nome'][$i]));
            if ($nome_i === $nome_z) {
                $gravou = false;
                echo json_encode(['status' => 'error', 'message' => 'Nome dos hóspedes devem ser diferentes.']);
                return;
            }
            $ident_i = trim(strtoupper($_POST['hospedes']['identificacao'][$i]));
            if ($ident_i === $ident_z) {
                $gravou = false;
                echo json_encode(['status' => 'error', 'message' => 'Documento de identificação dos hóspedes devem ser diferentes.']);
                return;
            }
        }
    }
}

//================================
$consulta = "SELECT * FROM hospede loc WHERE loc.id_locacao = " . $id_locacao;
$consulta = mysql_query($consulta);
$num_rows = mysql_num_rows($consulta);

//echo('TOTAL POST --> ' . count($_POST['hospedes']['nome']));

while ($ln_consulta = mysql_fetch_array($consulta)) {
    $ident_z = trim(strtoupper($ln_consulta['doc_hospede']));
    $nome_z = trim(strtoupper($ln_consulta['nome_hospede']));

    for ($i = 0; $i < count($_POST['hospedes']['nome']); $i++) {
//        echo('NOME: ' . $nome_z);
//        echo("IDET: " . $ident_z);

        $nome_i = trim(strtoupper($_POST['hospedes']['nome'][$i]));
        if ($nome_i === $nome_z) {
            $gravou = false;
            echo json_encode(['status' => 'error', 'message' => 'Refaça a transação: Hóspede já cadastrado.']);
            return;
        }
        $ident_i = trim(strtoupper($_POST['hospedes']['identificacao'][$i]));
        if ($ident_i === $ident_z) {
            $gravou = false;
            echo json_encode(['status' => 'error', 'message' => 'Refaça a transação: já existem hóspedes cadastrados com a mesma documentação.']);
            return;
        }
//        echo('nome_i: ' . $nome_i);
//        echo("ident_i: " . $ident_i);
    }
//    echo(($ln_consulta['nome_hospede']));
//    echo(('<p>'));
}
//echo('Passou');
//exit();
//================================
for ($i = 0; $i < count($_POST['hospedes']['nome']); $i++) {
    $sql2 = "INSERT INTO hospede (id_hospede, nome_hospede, doc_hospede, parentesco_hospede, id_locacao)
             VALUES (NULL,'" . $_POST['hospedes']['nome'][$i] . "','" . $_POST['hospedes']['identificacao'][$i] . "','" . $_POST['hospedes']['parentesco'][$i] . "','" . $id_locacao . "')";
    $result = mysql_query($sql2);
    if ($result) {
        $gravou = true;
    } else {
        $erro = mysql_error();
        $gravou = false;
        echo json_encode(['status' => 'error', 'message' => 'Ação falhou: ' . $erro]);
        return;
    }
}
if ($gravou == true) {

    $codvalidacao = gerarCodigo();
    $codvalidacao = 'R' . $codvalidacao . $id_locacao . '_n';

    $sql2 = "UPDATE locacao SET codvalidacao = '" . $codvalidacao . "' WHERE id_locacao = '" . $id_locacao . "'";
    $result = mysql_query($sql2);
    if ($result) {
        echo json_encode(['id' => $id_locacao, 'status' => 'success', 'message' => 'Hóspedes salvos com sucesso!']);
        return;
    } else {
        $erro = mysql_error();
        $gravou = false;
        echo json_encode(['status' => 'error', 'message' => 'Ação falhou: ' . $erro]);
        return;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Atenção: falha ao gravar hóspedes. Refaça a transação!']);
}
?>

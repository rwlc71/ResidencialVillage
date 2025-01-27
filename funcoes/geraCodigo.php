<?php
function gerarCodigo($tamanho = 9) {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $codigo = '';
    $comprimento = strlen($caracteres);
    
    for ($i = 0; $i < $tamanho; $i++) {
        $codigo .= $caracteres[rand(0, $comprimento - 1)];
    }
    
    return $codigo;
}

// Exemplo de uso
//echo gerarCodigoAleatorio(); // Gera um código aleatório de até 8 caracteres
?>

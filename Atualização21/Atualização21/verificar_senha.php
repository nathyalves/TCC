<?php
$senha = '123456'; // Senha que você está usando para fazer login
$hash_no_banco = '$2y$10$X9Z8Y7W6V5U4T3S2R1Q0P1O2N3M4L5K6J7I8H9G0F1E2D3C4B5A6'; // Hash armazenado no banco de dados

if (password_verify($senha, $hash_no_banco)) {
    echo "A senha está correta!";
} else {
    echo "A senha está incorreta.";
}
?>
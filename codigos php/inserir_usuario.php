<?php
include('conexao.php');

// Criptografa a senha
$senha_hash = password_hash("123456", PASSWORD_DEFAULT);

// Insere um usuário na tabela 'usuarios'
$sql = "INSERT INTO usuarios (usuario, senha, tipo) VALUES ('admin', '$senha_hash', 'recepcionista')";
try {
    $pdo->exec($sql);
    echo "Usuário inserido com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao inserir usuário: " . $e->getMessage();
}
?>

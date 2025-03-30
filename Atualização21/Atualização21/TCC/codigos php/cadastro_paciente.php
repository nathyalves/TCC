<?php
session_start();
if ($_SESSION['tipo'] != 'recepcionista') {
    echo "Acesso negado!";
    exit();
}

include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];

    $sql = "INSERT INTO pacientes (nome, cpf, endereco) VALUES (:nome, :cpf, :endereco)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':endereco', $endereco);
    
    if ($stmt->execute()) {
        echo "Paciente cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar paciente.";
    }
}
?>
<?php ?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Paciente</title>
</head>
<body>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="text" name="cpf" placeholder="CPF" required>
        <textarea name="endereco" placeholder="Endereço" required></textarea>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>

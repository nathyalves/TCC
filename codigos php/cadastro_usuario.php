<?php
require_once('cabecalho.php'); 
session_start();
include('conexao.php'); // Incluindo a conexão com o banco de dados

// Verifica se o usuário é um administrador
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'recepcionista') {
    header('Location: login.php'); // Redireciona se não for admin
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografando a senha
    $tipo = $_POST['tipo'];

    // SQL para inserir o novo usuário
    $sql = "INSERT INTO usuarios (usuario, senha, tipo) VALUES (:usuario, :senha, :tipo)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':tipo', $tipo);

    try {
        $stmt->execute();
        echo "Usuário cadastrado com sucesso!";
        
        // Redirecionar para a página de login após o cadastro
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        echo "Erro ao cadastrar usuário: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário - Clínica</title>
</head>
<body>
    <div class="cadastrar-container">
        <h2>Cadastrar Novo Usuário</h2>
        <form method="POST" action="cadastro_usuario.php">
            <div>
                <label for="usuario">Usuário</label>
                <input type="text" id="usuario" name="usuario" placeholder="Usuário" required>
            </div>
            <div>
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Senha" required>
            </div>
            <div>
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo" required>
                    <option value="recepcionista">Recepcionista</option>
                    <option value="enfermeira">Enfermeira</option>
                    <option value="medico">Médico</option>
                </select>
            </div>
            <div>
                <button type="submit">Cadastrar</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php require_once('rodape.php'); ?>

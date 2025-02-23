<?php 
require_once ('cabecalho.php'); 
session_start();
include ('conexao.php'); // Certifique-se de que esse arquivo está correto

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['tipo'] = $user['tipo'];

        // Redirecionar com base no tipo de usuário
        if ($user['tipo'] === 'recepcionista') {
            header('Location: cadastro_paciente.php'); // Redireciona para cadastro de pacientes
        } elseif ($user['tipo'] === 'enfermeira') {
            header('Location: cadastro_enfermeira.php'); // Redireciona para cadastro de enfermeiras
        } elseif ($user['tipo'] === 'medico') {
            header('Location: cadastro_medico.php'); // Redireciona para cadastro de médicos
        }
        exit();
    } else {
        echo 'Usuário ou senha inválidos';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Clínica</title>
    <link rel="stylesheet" href="estilo.css"> <!-- Inclua seu arquivo CSS, se houver -->
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <div>
                <label for="usuario">Usuário</label>
                <input type="text" id="usuario" name="usuario" placeholder="Usuário" required>
            </div>
            <div>
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Senha" required>
            </div>
            <div>
                <button type="submit">Entrar</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php require_once ('rodape.php'); ?>

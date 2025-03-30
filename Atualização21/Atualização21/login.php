<?php
session_start();
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    // Verifica se o usuário existe
    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica a senha
    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['tipo'] = $user['tipo'];

        // Redireciona com base no tipo de usuário
        switch ($user['tipo']) {
            case 'admin':
                header('Location: cadastro_usuario.php');
                break;
            case 'recepcionista':
                header('Location: cadastro_paciente.php');
                break;
            case 'enfermeira':
                header('Location: cadastro_triagem.php');
                break;
            case 'medico':
                header('Location: cadastro_consulta.php');
                break;
            default:
                header('Location: index.php'); // Página padrão
                break;
        }
        exit();
    } else {
        echo "<p style='color: red;'>Credenciais inválidas.</p>";
    }
}

?>

<?php include('cabecalho.php'); ?>
<div class="form-container">
            <div class="logo">
                <!-- Adicionando um GIF relacionado à saúde -->
                <img src="imagem/logo.jpg" alt="Saúde GIF">
                <h1>Sistema de Atendimento Integrado</h1>
            </div>
           <h2>Login</h2>
         <form method="POST" action="login.php">
    
        <div>
            <label for="usuario">Usuário</label>
            <input type="text" id="usuario" name="usuario" required>
        </div>
        <div>
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <div>
            <button type="submit">Entrar</button>
        </div>
    </form>
</div>
<?php include('rodape.php'); ?>
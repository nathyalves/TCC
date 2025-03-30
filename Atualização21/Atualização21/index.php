<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Redireciona com base no tipo de usuário
switch ($_SESSION['tipo']) {
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
        echo "Bem-vindo!";
        break;
}
exit();
?>
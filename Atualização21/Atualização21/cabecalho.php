<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAUDE CARE</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        /* Estilos adicionais para o cabeçalho */
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px; /* Espaço entre o GIF e o texto */
        }
        .logo img {
            width: 50px;
            height: 50px;
        }
        .welcome-message {
            font-size: 18px;
            font-weight: bold;
            color: #fff; /* Cor branca para combinar com o cabeçalho */
        }
        .date-time {
            font-size: 14px;
            color: #fff; /* Cor branca para combinar com o cabeçalho */
        }
        
    </style>
</head>
<script>
        setInterval(function() {
            document.querySelector('.date-time').textContent = new Date().toLocaleString('pt-BR');
        }, 1000);
    </script>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <!-- Adicionando um GIF relacionado à saúde -->
                <img src="imagem/saude.png" alt="Saúde GIF">
                <h1>Sistema de Atendimento Integrado </h1>
            </div>
            <div class="welcome-message">
                <?php if (isset($_SESSION['usuario'])): ?>
                    Bem-vindo, <?php echo $_SESSION['usuario']; ?>!
                <?php else: ?>
                    Bem-vindo!
                <?php endif; ?>
                <div style="margin-top: 20px;">
    <a href="adicionar_medicamento.php">Gerenciar Medicamentos</a>
</div>
            </div>
            <div class="date-time">
                <!-- Exibindo a data e hora atual -->
                <?php
                date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário
                echo date('d/m/Y H:i:s'); // Formato: Dia/Mês/Ano Hora:Minuto:Segundo
                ?>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['usuario'])): ?>
                    <li><a href="logout.php">Sair</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
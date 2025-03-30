<?php
session_start();
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'medico') {
    header('Location: index.php');
    exit();
}

include 'conexao.php';

// Variáveis para armazenar os dados da consulta e da triagem
$dados_consulta = [];
$dados_triagem = [];

// Verifica se um paciente foi selecionado para exibir os dados
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['paciente_id'])) {
    $paciente_id = filter_input(INPUT_GET, 'paciente_id', FILTER_VALIDATE_INT);

    if ($paciente_id) {
        // Busca os dados da consulta do paciente selecionado
        $sql_consulta = "SELECT id, paciente_id, observacoes, diagnostico, prescricao 
                         FROM consultas 
                         WHERE paciente_id = :paciente_id";
        $stmt_consulta = $pdo->prepare($sql_consulta);
        $stmt_consulta->bindParam(':paciente_id', $paciente_id);
        $stmt_consulta->execute();
        $dados_consulta = $stmt_consulta->fetch(PDO::FETCH_ASSOC);

        // Busca os dados da triagem do paciente selecionado
        $sql_triagem = "SELECT pressao, glicemia, peso, altura, imc, tipo_sanguineo, historico_doencas, alergia, onde_sente_dor, data_que_comecou 
                        FROM triagens 
                        WHERE paciente_id = :paciente_id";
        $stmt_triagem = $pdo->prepare($sql_triagem);
        $stmt_triagem->bindParam(':paciente_id', $paciente_id);
        $stmt_triagem->execute();
        $dados_triagem = $stmt_triagem->fetch(PDO::FETCH_ASSOC);
    }
}

// Processa o formulário de atualização da consulta
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paciente_id = filter_input(INPUT_POST, 'paciente_id', FILTER_VALIDATE_INT);
    $observacoes = filter_input(INPUT_POST, 'observacoes', FILTER_SANITIZE_STRING);
    $diagnostico = filter_input(INPUT_POST, 'diagnostico', FILTER_SANITIZE_STRING);
    $prescricao = filter_input(INPUT_POST, 'prescricao', FILTER_SANITIZE_STRING);

    if ($paciente_id && $observacoes && $diagnostico && $prescricao) {
        // Verifica se já existe uma consulta para o paciente
        $sql_check = "SELECT id FROM consultas WHERE paciente_id = :paciente_id";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':paciente_id', $paciente_id);
        $stmt_check->execute();

        if ($stmt_check->fetch()) {
            // Atualiza a consulta existente
            $sql = "UPDATE consultas 
                    SET observacoes = :observacoes, 
                        diagnostico = :diagnostico, 
                        prescricao = :prescricao 
                    WHERE paciente_id = :paciente_id";
        } else {
            // Insere uma nova consulta
            $sql = "INSERT INTO consultas (paciente_id, observacoes, diagnostico, prescricao) 
                    VALUES (:paciente_id, :observacoes, :diagnostico, :prescricao)";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->bindParam(':observacoes', $observacoes);
        $stmt->bindParam(':diagnostico', $diagnostico);
        $stmt->bindParam(':prescricao', $prescricao);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "Dados da consulta salvos com sucesso!";
        } else {
            $_SESSION['msg'] = "Erro ao salvar dados da consulta.";
        }
    } else {
        $_SESSION['msg'] = "Preencha todos os campos corretamente!";
    }
    header("Location: ".$_SERVER['PHP_SELF'] . "?paciente_id=" . $paciente_id); // Mantém o paciente selecionado
    exit();
}
?>

<?php include 'cabecalho.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Consulta</title>
    <style>
        .dados-container {
            margin-bottom: 20px;
        }
        .dados-container h3 {
            margin-top: 0;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <?php if (isset($_SESSION['msg'])): ?>
        <p><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></p>
    <?php endif; ?>

    <h2>Cadastro de Consulta</h2>

    <!-- Formulário para selecionar o paciente -->
    <form method="GET" action="">
        <label for="paciente_id">Selecione o paciente:</label>
        <select name="paciente_id" required onchange="this.form.submit()">
            <option value="">Escolha um paciente</option>
            <?php
            $sql = "SELECT id, nome FROM pacientes";
            foreach ($pdo->query($sql) as $row) {
                $selected = ($_GET['paciente_id'] ?? '') == $row['id'] ? 'selected' : '';
                echo "<option value='{$row['id']}' $selected>{$row['nome']}</option>";
            }
            ?>
        </select>
    </form>

    <!-- Exibe os dados da triagem do paciente selecionado -->
    <?php if (!empty($dados_triagem)): ?>
        <div class="dados-container">
            <h3>Dados da Triagem</h3>
            <p><strong>Pressão:</strong> <?php echo $dados_triagem['pressao']; ?></p>
            <p><strong>Glicemia:</strong> <?php echo $dados_triagem['glicemia']; ?></p>
            <p><strong>Peso:</strong> <?php echo $dados_triagem['peso']; ?> kg</p>
            <p><strong>Altura:</strong> <?php echo $dados_triagem['altura']; ?> m</p>
            <p><strong>IMC:</strong> <?php echo $dados_triagem['imc']; ?></p>
            <p><strong>Tipo Sanguíneo:</strong> <?php echo $dados_triagem['tipo_sanguineo']; ?></p>
            <p><strong>Histórico de Doenças:</strong> <?php echo $dados_triagem['historico_doencas']; ?></p>
            <p><strong>Alergias:</strong> <?php echo $dados_triagem['alergia']; ?></p>
            <p><strong>Onde sente dor:</strong> <?php echo $dados_triagem['onde_sente_dor']; ?></p>
            <p><strong>Data que começou:</strong> <?php echo $dados_triagem['data_que_comecou']; ?></p>
        </div>
    <?php endif; ?>

    <!-- Exibe os dados da consulta do paciente selecionado -->
    <?php if (!empty($dados_consulta)): ?>
        <div class="dados-container">
            <h3>Dados da Consulta</h3>
            <form method="POST" action="">
                <input type="hidden" name="paciente_id" value="<?php echo $dados_consulta['paciente_id']; ?>">

                <label for="observacoes">Observações:</label>
                <textarea name="observacoes" id="observacoes" required><?php echo $dados_consulta['observacoes']; ?></textarea>

                <label for="diagnostico">Diagnóstico:</label>
                <textarea name="diagnostico" id="diagnostico" required><?php echo $dados_consulta['diagnostico']; ?></textarea>

                <label for="prescricao">Prescrição:</label>
                <textarea name="prescricao" id="prescricao" required><?php echo $dados_consulta['prescricao']; ?></textarea>

                <button type="submit">Salvar</button>
            </form>
        </div>
    <?php elseif (isset($_GET['paciente_id'])): ?>
        <!-- Formulário para criar uma nova consulta -->
        <div class="dados-container">
            <h3>Nova Consulta</h3>
            <form method="POST" action="">
                <input type="hidden" name="paciente_id" value="<?php echo $_GET['paciente_id']; ?>">

                <label for="observacoes">Observações:</label>
                <textarea name="observacoes" id="observacoes" required></textarea>

                <label for="diagnostico">Diagnóstico:</label>
                <textarea name="diagnostico" id="diagnostico" required></textarea>

                <label for="prescricao">Prescrição:</label>
                <textarea name="prescricao" id="prescricao" required></textarea>

                <button type="submit">Salvar</button>
            </form>
        </div>
    <?php endif; ?>

</body>
</html>

<?php include 'rodape.php'; ?>
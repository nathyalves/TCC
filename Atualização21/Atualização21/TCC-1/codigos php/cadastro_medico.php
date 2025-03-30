<?php
session_start();
if ($_SESSION['tipo'] != 'medico') {
    echo "Acesso negado!";
    exit();
}

include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paciente_id = $_POST['paciente_id'];
    $tipo_sanguineo = $_POST['tipo_sanguineo'];
    $historico_doencas = $_POST['historico_doencas'];
    $exames = $_POST['exames'];

    $sql = "UPDATE dados_clinicos SET tipo_sanguineo = :tipo_sanguineo, historico_doencas = :historico_doencas, exames = :exames WHERE paciente_id = :paciente_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':paciente_id', $paciente_id);
    $stmt->bindParam(':tipo_sanguineo', $tipo_sanguineo);
    $stmt->bindParam(':historico_doencas', $historico_doencas);
    $stmt->bindParam(':exames', $exames);
    
    if ($stmt->execute()) {
        echo "Dados médicos cadastrados com sucesso!";
    } else {
        echo "Erro ao cadastrar dados médicos.";
    }
}
?>
<?php include 'header.php'; ?>
<!-- Conteúdo da página -->
<?php include 'footer.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Dados Médicos</title>
</head>
<body>
    <form method="POST">
        <select name="paciente_id">
            <?php
            $sql = "SELECT id, nome FROM pacientes";
            foreach ($pdo->query($sql) as $row) {
                echo "<option value='{$row['id']}'>{$row['nome']}</option>";
            }
            ?>
        </select>
        <input type="text" name="tipo_sanguineo" placeholder="Tipo Sanguíneo" required>
        <textarea name="historico_doencas" placeholder="Histórico de Doenças" required></textarea>
        <textarea name="exames" placeholder="Exames" required></textarea>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>

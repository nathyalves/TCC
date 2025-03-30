<?php require_once ('cabecalho.php'); ?>
<?php
session_start();
if ($_SESSION['tipo'] != 'enfermeira') {
    echo "Acesso negado!";
    exit();
}

include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paciente_id = $_POST['paciente_id'];
    $pressao = $_POST['pressao'];
    $glicemia = $_POST['glicemia'];
    $peso = $_POST['peso'];
    $altura = $_POST['altura'];
    $imc = $peso / ($altura * $altura);

    $sql = "INSERT INTO dados_clinicos (paciente_id, pressao, glicemia, peso, altura, imc) VALUES (:paciente_id, :pressao, :glicemia, :peso, :altura, :imc)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':paciente_id', $paciente_id);
    $stmt->bindParam(':pressao', $pressao);
    $stmt->bindParam(':glicemia', $glicemia);
    $stmt->bindParam(':peso', $peso);
    $stmt->bindParam(':altura', $altura);
    $stmt->bindParam(':imc', $imc);
    
    if ($stmt->execute()) {
        echo "Dados clínicos cadastrados com sucesso!";
    } else {
        echo "Erro ao cadastrar dados clínicos.";
    }
}
?>
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
        <input type="text" name="pressao" placeholder="Pressão" required>
        <input type="text" name="glicemia" placeholder="Glicemia" required>
        <input type="number" name="peso" placeholder="Peso" step="0.01" required>
        <input type="number" name="altura" placeholder="Altura" step="0.01" required>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
<?php require_once ('rodape.php'); ?>
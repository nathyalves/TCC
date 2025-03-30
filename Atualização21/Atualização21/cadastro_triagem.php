<?php
session_start();
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'enfermeira') {
    header('Location: index.php');
    exit();
}

include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paciente_id = filter_input(INPUT_POST, 'paciente_id', FILTER_VALIDATE_INT);
    $pressao = filter_input(INPUT_POST, 'pressao', FILTER_SANITIZE_STRING);
    $glicemia = filter_input(INPUT_POST, 'glicemia', FILTER_SANITIZE_STRING);
    $peso = filter_input(INPUT_POST, 'peso', FILTER_VALIDATE_FLOAT);
    $altura = filter_input(INPUT_POST, 'altura', FILTER_VALIDATE_FLOAT);
    $tipo_sanguineo = filter_input(INPUT_POST, 'tipo_sanguineo', FILTER_SANITIZE_STRING);
    $historico_doencas = filter_input(INPUT_POST, 'historico_doencas', FILTER_SANITIZE_STRING);
    $alergia = filter_input(INPUT_POST, 'alergia', FILTER_SANITIZE_STRING);
    $onde_sente_dor = filter_input(INPUT_POST, 'onde_sente_dor', FILTER_SANITIZE_STRING);
    $data_que_comecou = filter_input(INPUT_POST, 'data_que_comecou', FILTER_SANITIZE_STRING);

    if ($paciente_id && $pressao && $glicemia && $peso && $altura && $altura > 0 && $tipo_sanguineo) {
        // Calcula o IMC automaticamente
        $imc = round($peso / ($altura * $altura), 2);

        $sql = "INSERT INTO triagens (paciente_id, pressao, glicemia, peso, altura, imc, tipo_sanguineo, historico_doencas, alergia, onde_sente_dor, data_que_comecou) 
                VALUES (:paciente_id, :pressao, :glicemia, :peso, :altura, :imc, :tipo_sanguineo, :historico_doencas, :alergia, :onde_sente_dor, :data_que_comecou)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->bindParam(':pressao', $pressao);
        $stmt->bindParam(':glicemia', $glicemia);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':altura', $altura);
        $stmt->bindParam(':imc', $imc);
        $stmt->bindParam(':tipo_sanguineo', $tipo_sanguineo);
        $stmt->bindParam(':historico_doencas', $historico_doencas);
        $stmt->bindParam(':alergia', $alergia);
        $stmt->bindParam(':onde_sente_dor', $onde_sente_dor);
        $stmt->bindParam(':data_que_comecou', $data_que_comecou);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "Triagem cadastrada com sucesso!";
        } else {
            $_SESSION['msg'] = "Erro ao cadastrar triagem.";
        }
    } else {
        $_SESSION['msg'] = "Preencha todos os campos corretamente!";
    }
    header("Location: ".$_SERVER['PHP_SELF']); // Redireciona para evitar reenvio do formulário
    exit();
}
?>

<?php include 'cabecalho.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Triagem</title>
    <style>
        .imc-result {
            font-weight: bold;
            color: #28a745;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
    <script>
        function calcularIMC() {
            const peso = parseFloat(document.getElementById('peso').value);
            const altura = parseFloat(document.getElementById('altura').value);

            if (peso && altura && altura > 0) {
                const imc = (peso / (altura * altura)).toFixed(2);
                document.getElementById('imc-result').innerText = `IMC: ${imc}`;
            } else {
                document.getElementById('imc-result').innerText = '';
            }
        }
    </script>
</head>
<body>

    <?php if (isset($_SESSION['msg'])): ?>
        <p><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></p>
    <?php endif; ?>

    <h2>Cadastro de Triagem</h2>
    <form method="POST">
        <label for="paciente_id">Selecione o paciente:</label>
        <select name="paciente_id" required>
            <option value="">Escolha um paciente</option>
            <?php
            $sql = "SELECT id, nome FROM pacientes";
            foreach ($pdo->query($sql) as $row) {
                echo "<option value='{$row['id']}'>{$row['nome']}</option>";
            }
            ?>
        </select>

        <label for="pressao">Pressão:</label>
        <input type="text" name="pressao" id="pressao" placeholder="Ex: 120/80" required>

        <label for="glicemia">Glicemia:</label>
        <input type="text" name="glicemia" id="glicemia" placeholder="Ex: 90 mg/dL" required>

        <label for="peso">Peso (kg):</label>
        <input type="number" name="peso" id="peso" placeholder="Ex: 70" step="0.01" required oninput="calcularIMC()">

        <label for="altura">Altura (m):</label>
        <input type="number" name="altura" id="altura" placeholder="Ex: 1.75" step="0.01" required oninput="calcularIMC()">

        <p id="imc-result" class="imc-result"></p>

        <label for="tipo_sanguineo">Tipo Sanguíneo:</label>
        <select name="tipo_sanguineo" required>
            <option value="">Escolha um tipo sanguíneo</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
        </select>

        <label for="historico_doencas">Histórico de Doenças:</label>
        <textarea name="historico_doencas" id="historico_doencas" placeholder="Descreva o histórico de doenças"></textarea>

        <label for="alergia">Alergias:</label>
        <textarea name="alergia" id="alergia" placeholder="Descreva as alergias"></textarea>

        <label for="onde_sente_dor">Onde sente dor?</label>
        <textarea name="onde_sente_dor" id="onde_sente_dor" placeholder="Descreva onde sente dor"></textarea>

        <label for="data_que_comecou">Data que começou:</label>
        <input type="date" name="data_que_comecou" id="data_que_comecou" required>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>

<?php include 'rodape.php'; ?>
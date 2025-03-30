<?php
session_start();
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'recepcionista') {
    header('Location: index.php');
    exit();
}

include 'conexao.php';

// Função para validar CPF
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf); // Remove caracteres não numéricos
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;

    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }
    return true;
}

// Função para validar telefone
function validarTelefone($telefone) {
    $telefone = preg_replace('/[^0-9]/', '', $telefone); // Remove caracteres não numéricos
    return strlen($telefone) >= 10 && strlen($telefone) <= 11; // Telefone deve ter 10 ou 11 dígitos
}

// Função para validar CEP
function validarCEP($cep) {
    $cep = preg_replace('/[^0-9]/', '', $cep); // Remove caracteres não numéricos
    return strlen($cep) == 8; // CEP deve ter 8 dígitos
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
    $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
    $cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_STRING);
    $cidade = filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_STRING);
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);

    if (!$nome || !$cpf || !$endereco || !$telefone || !$cep || !$cidade || !$estado) {
        $_SESSION['msg'] = "Preencha todos os campos!";
    } elseif (!validarCPF($cpf)) {
        $_SESSION['msg'] = "CPF inválido!";
    } elseif (!validarTelefone($telefone)) {
        $_SESSION['msg'] = "Telefone inválido!";
    } elseif (!validarCEP($cep)) {
        $_SESSION['msg'] = "CEP inválido!";
    } else {
        // Verifica se CPF já existe
        $sql = "SELECT COUNT(*) FROM pacientes WHERE cpf = :cpf";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['msg'] = "Este CPF já está cadastrado!";
        } else {
            // Insere novo paciente
            $sql = "INSERT INTO pacientes (nome, cpf, endereco, telefone, cep, cidade, estado) 
                    VALUES (:nome, :cpf, :endereco, :telefone, :cep, :cidade, :estado)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':endereco', $endereco);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':cep', $cep);
            $stmt->bindParam(':cidade', $cidade);
            $stmt->bindParam(':estado', $estado);

            if ($stmt->execute()) {
                $_SESSION['msg'] = "Paciente cadastrado com sucesso!";
            } else {
                $_SESSION['msg'] = "Erro ao cadastrar paciente.";
            }
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']); // Redireciona para evitar reenvio
    exit();
}
?>

<?php include 'cabecalho.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Paciente</title>
    <style>
        label {
            display: block;
            margin-top: 10px;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <?php if (isset($_SESSION['msg'])): ?>
        <p><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></p>
    <?php endif; ?>

    <h2>Cadastro de Paciente</h2>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" placeholder="Nome" required>

        <label for="cpf">CPF:</label>
        <input type="text" name="cpf" id="cpf" placeholder="CPF (somente números)" required pattern="\d{11}">

        <label for="endereco">Endereço:</label>
        <textarea name="endereco" id="endereco" placeholder="Endereço" required></textarea>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" placeholder="Telefone (somente números)" required pattern="\d{10,11}">

        <label for="cep">CEP:</label>
        <input type="text" name="cep" id="cep" placeholder="CEP (somente números)" required pattern="\d{8}">

        <label for="cidade">Cidade:</label>
        <input type="text" name="cidade" id="cidade" placeholder="Cidade" required>

        <label for="estado">Estado:</label>
        <select name="estado" id="estado" required>
            <option value="">Selecione o estado</option>
            <option value="AC">Acre</option>
            <option value="AL">Alagoas</option>
            <option value="AP">Amapá</option>
            <option value="AM">Amazonas</option>
            <option value="BA">Bahia</option>
            <option value="CE">Ceará</option>
            <option value="DF">Distrito Federal</option>
            <option value="ES">Espírito Santo</option>
            <option value="GO">Goiás</option>
            <option value="MA">Maranhão</option>
            <option value="MT">Mato Grosso</option>
            <option value="MS">Mato Grosso do Sul</option>
            <option value="MG">Minas Gerais</option>
            <option value="PA">Pará</option>
            <option value="PB">Paraíba</option>
            <option value="PR">Paraná</option>
            <option value="PE">Pernambuco</option>
            <option value="PI">Piauí</option>
            <option value="RJ">Rio de Janeiro</option>
            <option value="RN">Rio Grande do Norte</option>
            <option value="RS">Rio Grande do Sul</option>
            <option value="RO">Rondônia</option>
            <option value="RR">Roraima</option>
            <option value="SC">Santa Catarina</option>
            <option value="SP">São Paulo</option>
            <option value="SE">Sergipe</option>
            <option value="TO">Tocantins</option>
        </select>

        <button type="submit">Cadastrar</button>
    </form>

</body>
</html>

<?php include 'rodape.php'; ?>
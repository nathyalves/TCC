<?php
session_start();
include('conexao.php');

// Verifica se o usuário é admin
if ($_SESSION['tipo'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Adicionar medicamento
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $fabricante = trim($_POST['fabricante']);
    $quantidade = intval($_POST['quantidade']);
    $data_validade = $_POST['data_validade'];

    $sql = "INSERT INTO medicamento (nome, descricao, fabricante, quantidade, data_validade) 
            VALUES (:nome, :descricao, :fabricante, :quantidade, :data_validade)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':fabricante', $fabricante);
    $stmt->bindParam(':quantidade', $quantidade);
    $stmt->bindParam(':data_validade', $data_validade);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Medicamento adicionado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao adicionar medicamento.</p>";
    }
}

// Recuperar medicamentos cadastrados
$sql = "SELECT * FROM medicamento";
$stmt = $pdo->query($sql);
$medicamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('cabecalho.php'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Medicamentos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        .form-container {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-container input, .form-container textarea, .form-container select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #218838;
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .actions {
            white-space: nowrap;
        }
        .actions a {
            margin-right: 5px;
            color: #007bff;
            text-decoration: none;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Adicionar Medicamento</h2>
    <div class="form-container">
        <form method="POST" action="">
            <div>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div>
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" required></textarea>
            </div>
            <div>
                <label for="fabricante">Fabricante:</label>
                <input type="text" id="fabricante" name="fabricante" required>
            </div>
            <div>
                <label for="quantidade">Quantidade:</label>
                <input type="number" id="quantidade" name="quantidade" required>
            </div>
            <div>
                <label for="data_validade">Data de Validade:</label>
                <input type="date" id="data_validade" name="data_validade" required>
            </div>
            <div>
                <button type="submit">Adicionar</button>
            </div>
        </form>
    </div>

    <h2>Medicamentos Cadastrados</h2>
    <div class="table-container">
        <?php if (count($medicamentos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Fabricante</th>
                        <th>Quantidade</th>
                       <th>Data de Validade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($medicamentos as $medicamento): ?>
                        <tr>
                            <td><?php echo $medicamento['id']; ?></td>
                            <td><?php echo $medicamento['nome']; ?></td>
                            <td><?php echo $medicamento['descricao']; ?></td>
                            <td><?php echo $medicamento['fabricante']; ?></td>
                            <td><?php echo $medicamento['quantidade']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($medicamento['data_validade'])); ?></td>
                            <td class="actions">
                                <a href="editar_medicamento.php?id=<?php echo $medicamento['id']; ?>">Editar</a>
                                <a href="excluir_medicamento.php?id=<?php echo $medicamento['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este medicamento?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum medicamento cadastrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php include('rodape.php'); ?>
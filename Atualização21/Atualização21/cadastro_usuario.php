<?php
session_start();
include('conexao.php');

// Verifica se o usuário é admin
if ($_SESSION['tipo'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Cadastro de usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['excluir'])) {
        // Excluir usuário
        $id = $_POST['id'];
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            echo "<p style='color: green;'>Usuário excluído com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao excluir usuário.</p>";
        }
    } else {
        // Cadastrar novo usuário
        $nome = trim($_POST['nome']);
        $usuario = trim($_POST['usuario']);
        $cpf = trim($_POST['cpf']);
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $tipo = $_POST['tipo'];

        $sql = "INSERT INTO usuarios (nome, usuario, cpf, senha, tipo) VALUES (:nome, :usuario, :cpf, :senha, :tipo)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':tipo', $tipo);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Usuário cadastrado com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao cadastrar usuário.</p>";
        }
    }
}

// Recuperar usuários cadastrados
$sql = "SELECT * FROM usuarios";
$stmt = $pdo->query($sql);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('cabecalho.php'); ?>
<div class="form-container">
    <h2>Cadastro de Usuário</h2>
    <form method="POST" action="cadastro_usuario.php">
        <div>
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" required>
        </div>
        <div>
            <label for="usuario">Usuário</label>
            <input type="text" id="usuario" name="usuario" required>
        </div>
        <div>
        <label for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf" placeholder="CPF (completo exem: 000.000.000-00)" required>
        </div>
        <div>
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <div>
            <label for="tipo">Tipo</label>
            <select id="tipo" name="tipo" required>
                <option value="admin">Admin</option>
                <option value="recepcionista">Recepcionista</option>
                <option value="enfermeira">Enfermeira</option>
                <option value="medico">Médico</option>
                <option value="enfermagem_medicacao">Enf.Medicação</option>
                <option value="tec_laboratorial">Tec.Laboratorio</option>
                <option value="tec_raiox">Tec.RaioX</option>
            </select>
        </div>
        <div>
            <button type="submit">Cadastrar</button>
        </div>
    </form>
</div>

<div class="usuarios-cadastrados">
    <h2>Usuários Cadastrados</h2>
    <table border="2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Usuário</th>
                <th>CPF</th>
                <th>Função</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo $usuario['nome']; ?></td>
                    <td><?php echo $usuario['usuario']; ?></td>
                    <td><?php echo $usuario['cpf']; ?></td>
                    <td><?php echo $usuario['tipo']; ?></td>
                    <td>
                        <form method="POST" action="cadastro_usuario.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                            <button type="submit" name="excluir">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('rodape.php'); ?>
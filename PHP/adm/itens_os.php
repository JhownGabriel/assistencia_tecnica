<?php
session_start();
include_once '../includes/dbconnect.php';

$erro = '';
$success = '';

// Consulta para obter a última `id_ordem`
$result = $mysqli->query("SELECT MAX(id_ordem) as ultima_ordem FROM Items_os");
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['ultima_ordem'] !== null) {
        $id_ordem_os = (int)$row['ultima_ordem'] + 1;
    }
}

// Inserir/Atualizar Item de Ordem de Serviço
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id_ordem"], $_POST["id_serv"], $_POST["preco_items_os"])) {
        if (empty($_POST["id_ordem"]) || empty($_POST["id_serv"]) || empty($_POST["preco_items_os"])) {
            $erro = "Todos os campos são obrigatórios.";
        } else {
            $id_ordem = (int) $_POST["id_ordem"];
            $id_serv = (int) $_POST["id_serv"];
            $preco_items_os = (float) $_POST["preco_items_os"];

            // Verificar se estamos atualizando ou inserindo
            if (isset($_POST['id_items_os']) && $_POST['id_items_os'] != -1) { // Atualizar item existente
                $stmt = $mysqli->prepare("UPDATE Items_os SET preco_items_os = ? WHERE id_ordem = ? AND id_serv = ?");
                $stmt->bind_param("dii", $preco_items_os, $id_ordem, $id_serv);

                if ($stmt->execute()) {
                    $success = "Item de ordem de serviço atualizado com sucesso.";
                } else {
                    $erro = "Erro ao atualizar item de ordem de serviço: " . $stmt->error;
                }
            } else { // Inserir novo item
                $stmt = $mysqli->prepare("INSERT INTO Items_os (id_ordem, id_serv, preco_items_os) VALUES (?, ?, ?)");
                $stmt->bind_param("iid", $id_ordem, $id_serv, $preco_items_os);

                if ($stmt->execute()) {
                    $success = "Item de ordem de serviço cadastrado com sucesso.";
                } else {
                    $erro = "Erro ao cadastrar item de ordem de serviço: " . $stmt->error;
                }
            }
        }
    } else {
        $erro = "Todos os campos são obrigatórios.";
    }
}

// Remover Item de Ordem de Serviço
if (isset($_GET["id_ordem"], $_GET["id_serv"]) && is_numeric($_GET["id_ordem"]) && is_numeric($_GET["id_serv"])) {
    $id_ordem = (int) $_GET["id_ordem"];
    $id_serv = (int) $_GET["id_serv"];

    $stmt = $mysqli->prepare("DELETE FROM Items_os WHERE id_ordem = ? AND id_serv = ?");
    $stmt->bind_param('ii', $id_ordem, $id_serv);
    if ($stmt->execute()) {
        $success = "Item de ordem de serviço removido com sucesso.";
    } else {
        $erro = "Erro ao remover item de ordem de serviço: " . $stmt->error;
    }
}

// Listar Itens de Ordem de Serviço
$result = $mysqli->query("SELECT io.*, s.nome_serv FROM Items_os io LEFT JOIN Servico s ON io.id_serv = s.id_serv");
?>

<?php require_once 'headerCRUD.php'; ?>
<link rel="stylesheet" href="styleCRUD/stylecrud.css" type="text/css">
<body>
    <h1>Cadastro de Itens de Ordem de Serviço</h1>

    <?php if (!empty($erro)): ?>
        <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <!-- Formulário para adicionar ou editar item de ordem de serviço -->
    <form action="itens_os.php" method="POST">
        <input type="hidden" name="id_items_os"
            value="<?= isset($_POST['id_items_os']) ? $_POST['id_items_os'] : -1 ?>">

        <label for="id_ordem">Ordem de Serviço:</label><br>
        <input type="text" name="id_ordem"
            value="<?php echo htmlspecialchars($id_ordem_os); ?>" readonly required><br><br>

        <label for="id_serv">Serviço:</label><br>
        <select name="id_serv" required>
            <option value="">Selecione um serviço</option>
            <?php
            // Listar serviços para o dropdown
            $servicos = $mysqli->query("SELECT id_serv, nome_serv FROM Servico");
            while ($servico = $servicos->fetch_assoc()) {
                // Verificar se o id_serv vindo do POST corresponde ao id_serv do banco
                $selected = (isset($_POST['id_serv']) && $_POST['id_serv'] == $servico['id_serv']) ? 'selected' : '';
                echo "<option value='{$servico['id_serv']}' $selected>{$servico['nome_serv']}</option>";
            }
            ?>
        </select><br><br>


        <label for="preco_items_os">Preço:</label><br>
        <input type="text" step="0.01" name="preco_items_os"
            value="<?= isset($_POST['preco_items_os']) ? htmlspecialchars($_POST['preco_items_os']) : '' ?>"
            required><br><br>

            <script>
            document.getElementById('preco_itens_os').addEventListener('input', function (e) {
                // Remove qualquer caractere não numérico
                let value = e.target.value.replace(/[^0-9]/g, '');

                // Se o valor estiver vazio, não faz nada
                if (value === '') {
                    e.target.value = '';
                    return;
                }

                // Define a parte decimal (centavos)
                let decimalPart = value.slice(-2).padStart(2, '0');
                // Define a parte inteira (reais)
                let integerPart = value.slice(0, -2);

                // Remove zeros à esquerda da parte inteira
                integerPart = integerPart.replace(/^0+/, '') || '0'; // Se estiver vazio, torna-se '0'

                // Adiciona separador de milhar
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Formata o valor final
                let formattedValue = integerPart + ',' + decimalPart;

                // Define o valor formatado no campo
                e.target.value = 'R$ ' + formattedValue;
            });
        </script>

        <button
            type="submit"><?= (isset($_POST['id_ordem']) && $_POST['id_ordem'] != -1) ? 'Salvar' : 'Cadastrar' ?>
        </button>
    </form>

    <hr>

    <!-- Exibição dos itens de ordem de serviço -->
    <h2>Lista de Itens de Ordem de Serviço</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Codigo Ordem Serviço</th>
                <th>Codigo Serviço</th>
                <th>Nome Serviço</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['id_ordem']) ?></td>
                    <td><?= htmlspecialchars($item['id_serv']) ?></td>
                    <td><?= htmlspecialchars($item['nome_serv']) ?></td>
                    <td><?= htmlspecialchars($item['preco_items_os']) ?></td>
                    <td>
                    <a href="itens_os.php?id_ordem=<?= $item['id_ordem'] ?>&id_serv=<?= $item['id_serv'] ?>&del=1"
                    onclick="return confirm('Tem certeza que deseja remover este item?')" class="btn btn-danger btn-sm">Remover</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>

</html>
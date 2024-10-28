<?php
session_start();
include_once '../includes/dbconnect.php';

$erro = '';
$success = '';

// pesquisa o ultimo id do id_ped e incrementa
$result = $mysqli->query("SELECT MAX(id_ped) as ultima_ordem FROM items_pedido");
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['ultima_ordem'] !== null) {
        $id_ordem_proxima = (int)$row['ultima_ordem'] + 1;
    }
}
// pesquisa o ultimo id do id_ped e incrementa
$result = $mysqli->query("SELECT MAX(id_prod) as ultima_produto FROM items_pedido");
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['ultima_produto'] !== null) {
        $id_ordem_produto = (int)$row['ultima_produto'] + 1;
    }
}


// Inserir/Atualizar Item de Pedido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id_ped"], $_POST["id_prod"], $_POST["preco_vendido"])) {
        if (empty($_POST["id_ped"]) || empty($_POST["id_prod"]) || empty($_POST["preco_vendido"])) {
            $erro = "Os campos Pedido, Produto e Preço são obrigatórios.";
        } else {
            $id_ped = $_POST["id_ped"];
            $id_prod = $_POST["id_prod"];
            $preco_vendido = str_replace(['R$', '.', ','], ['', '', '.'],  $_POST["preco_vendido"]);
            $id_itens_ped = isset($_POST["id_itens_ped"]) ? $_POST["id_itens_ped"] : null;

            if ($id_itens_ped === null) { // Inserir novo item de pedido
                $stmt = $mysqli->prepare("INSERT INTO items_pedido (id_ped, id_prod, preco_vendido) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $id_pedido_proxima, $id_prod, $preco_itens_ped);

                if ($stmt->execute()) {
                    $success = "Item de pedido registrado com sucesso.";
                } else {
                    $erro = "Erro ao registrar item de pedido: " . $stmt->error;
                }
            } else { // Atualizar item de pedido existente
                $stmt = $mysqli->prepare("UPDATE items_pedido SET preco_vendido = ? WHERE id_ped = ? AND id_prod = ?");
                $stmt->bind_param("dii", $preco_itens_ped, $id_ped, $id_prod);

                if ($stmt->execute()) {
                    $success = "Item de pedido atualizado com sucesso.";
                } else {
                    $erro = "Erro ao atualizar item de pedido: " . $stmt->error;
                }
            }
        }
    } else {
        $erro = "Todos os campos são obrigatórios.";
    }
}

// Remover Item de Pedido
if (isset($_GET["id_ped"]) && isset($_GET["id_prod"])) {
    $id_ped = (int) $_GET["id_ped"];
    $id_prod = (int) $_GET["id_prod"];

    $stmt = $mysqli->prepare("DELETE FROM items_pedido WHERE id_ped = ? AND id_prod = ?");
    $stmt->bind_param('ii', $id_ped, $id_prod);
    if ($stmt->execute()) {
        $success = "Item de pedido removido com sucesso.";
    } else {
        $erro = "Erro ao remover item de pedido: " . $stmt->error;
    }
}

// Listar Itens de Pedido
$result = $mysqli->query("SELECT ip.*, p.nome_prod, ped.data_ped FROM items_pedido ip LEFT JOIN Produto p ON ip.id_prod = p.id_prod LEFT JOIN Pedido ped ON ip.id_ped = ped.id_ped");
?>

<?php require_once 'headerCRUD.php'; ?>
<link rel="stylesheet" href="styleCRUD/stylecrud.css" type="text/css">
<body>
    <h1>Cadastro de Itens de Pedido</h1>

    <?php if (!empty($erro)): ?>
        <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <!-- Formulário para adicionar ou editar item de pedido -->
    <form action="itens_pedido.php" method="POST">
        <input type="hidden" name="id_itens_ped"
            value="<?= isset($_POST['id_itens_ped']) ? $_POST['id_itens_ped'] : '' ?>">

        <label for="id_ped">Codigo do Pedido</label><br>
        <input type="text" name="id_ped"
                value="<?php echo htmlspecialchars($id_ordem_proxima); ?>"
                required readonly><br><br>

        <label for="id_prod">Codigo do Produto</label><br>
        <input type="text" name="id_prod"
                value="<?php echo htmlspecialchars($id_ordem_produto); ?>"
                required readonly><br><br>

        <label for="preco_itens_ped">Preço:</label><br>
        <input id="preco_itens_ped" type="text" name="preco_itens_ped"
            value="<?php echo htmlspecialchars($preco_vendido); ?>"
            required><br><br>

            <script>
            document.getElementById('preco_itens_ped').addEventListener('input', function (e) {
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

        <button type="submit"><?= (isset($_POST['id_ped'])) ? 'Salvar' : 'Cadastrar' ?></button>
    </form>

    <hr>

    <!-- Exibição dos itens de pedido -->
    <h2>Lista de Itens de Pedido</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Codigo Pedido</th>
                <th>Codigo Produto</th>
                <th>Nome do Produto</th>
                <th>Data do Pedido</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['id_ped']) ?></td>
                    <td><?= htmlspecialchars($item['id_prod']) ?></td>
                    <td><?= htmlspecialchars($item['nome_prod']) ?></td>
                    <td><?= htmlspecialchars($item['data_ped']) ?></td>
                    <td><?= htmlspecialchars($item['preco_vendido']) ?></td>
                    <td>
                        <a href="itens_pedido.php?id_ped=<?= $item['id_ped'] ?>&id_prod=<?= $item['id_prod'] ?>"onclick="return confirm('Tem certeza que deseja remover este item de pedido?')">Remover</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>
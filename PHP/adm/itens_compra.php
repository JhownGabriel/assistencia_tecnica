<?php
session_start();
include_once '../includes/dbconnect.php';

$erro = '';
$success = '';

// Consulta para obter a última `id_compra`
$result = $mysqli->query("SELECT MAX(id_compra) as ultima_compra FROM Items_compra");
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['ultima_compra'] !== null) {
        $id_ordem_compra = (int)$row['ultima_compra'] + 1;
    }
}
// Consulta para obter a última `id_prod`
$result = $mysqli->query("SELECT MAX(id_prod) as ultima_prod FROM Items_compra");
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['ultima_prod'] !== null) {
        $id_ordem_prod = (int)$row['ultima_prod'] + 1;
    }
}
// Consulta para obter o valor da ultima compra
$sql = $mysqli->query("SELECT preco_compra FROM Compra ORDER BY id_compra DESC LIMIT 1");
// Verificar se o resultado contém dados
if ($sql) {
    // Pegar o valor da última compra
    $row = $result->fetch_assoc();
    if ($row && $row['preco_compra'] !== null) {
        $ultimo_valor = (float)$row['preco_compra'];
    }
}
//Inserir/Atualizar Itens de Compra
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id_compra"], $_POST["id_prod"], $_POST["preco_compra"])) {
        if (empty($_POST["id_compra"]) || empty($_POST["id_prod"]) || empty($_POST["preco_compra"])) {
            $erro = "Todos os campos são obrigatórios.";
        } else {
            $id_compra = $_POST["id_compra"];
            $id_prod = $_POST["id_prod"];
            $preco = $_POST["preco_compra"];
            $id_item = isset($_POST["id_item"]) ? $_POST["id_item"] : null;

            //Validar se o preço é um número
            if (!is_numeric($preco)) {
                $erro = "O preço deve ser um número.";
            } else {
                if ($id_item === null) { //Inserir novo item
                    $stmt = $mysqli->prepare("INSERT INTO Items_compra (id_compra, id_prod, preco_items_compra) VALUES (?, ?, ?)");
                    $stmt->bind_param("iis", $id_compra, $id_prod, $preco);

                    if ($stmt->execute()) {
                        $success = "Item de compra registrado com sucesso.";
                    } else {
                        $erro = "Erro ao registrar item de compra: " . $stmt->error;
                    }
                } else { // Atualizar item existente
                    $stmt = $mysqli->prepare("UPDATE Items_compra SET preco_items_compra = ? WHERE id_compra = ? AND id_prod = ?");
                    $stmt->bind_param("dii", $preco, $id_compra, $id_prod);

                    if ($stmt->execute()) {
                        $success = "Item de compra atualizado com sucesso.";
                    } else {
                        $erro = "Erro ao atualizar item de compra: " . $stmt->error;
                    }
                }
            }
        }
    } else {
        $erro = "Todos os campos são obrigatórios.";
    }
}

//Remover Item de Compra
if (isset($_GET["id_compra"], $_GET["id_prod"])) {
    $id_compra = (int) $_GET["id_compra"];
    $id_prod = (int) $_GET["id_prod"];

    $stmt = $mysqli->prepare("DELETE FROM Items_compra WHERE id_compra = ? AND id_prod = ?");
    $stmt->bind_param('ii', $id_compra, $id_prod);
    if ($stmt->execute()) {
        $success = "Item de compra removido com sucesso.";
    } else {
        $erro = "Erro ao remover item de compra: " . $stmt->error;
    }
}

//Listar Itens de Compra
$result = $mysqli->query("SELECT ic.*, p.nome_prod FROM Items_compra ic LEFT JOIN Produto p ON ic.id_prod = p.id_prod");

?>

<?php require_once 'headerCRUD.php'; ?>
<link rel="stylesheet" href="styleCRUD/stylecrud.css" type="text/css">
<body>
    <h1>Cadastro de Itens de Compra</h1>

    <?php if (!empty($erro)): ?>
        <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php
    // Recupera o valor de preco_compra passado pela URL
    $preco_compra = isset($_GET['preco_compra']) ? $_GET['preco_compra'] : '';
        
    // Se precisar, você pode usar htmlspecialchars() para evitar problemas de segurança
    $preco_compra = htmlspecialchars($preco_compra);
    ?>

    <!-- Formulário para adicionar ou editar item de compra -->
    <form action="itens_compra.php" method="POST">
        <input type="hidden" name="id_item" value="<?= isset($_POST['id_item']) ? $_POST['id_item'] : '' ?>">

        <label for="id_compra">Codigo da Compra:</label><br>
        <input type="number" name="id_compra" min="1"
        value="<?php echo htmlspecialchars($id_ordem_compra); ?>" readonly required><br><br>

        <label for="nome_prod">Codigo do Produto:</label><br>
        <input type="text" name="nome_prod"
            value="<?php echo htmlspecialchars($id_ordem_prod); ?>" readonly required><br><br>

            <label for="preco_">Preço:</label><br>
        <input type="text" id="preco" name="preco"
            value="<?php echo htmlspecialchars($ultimo_valor)?>" readonly required><br><br>

        <script>
            document.getElementById('preco').addEventListener('input', function (e) {
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
        <button type="submit"><?= (isset($_POST['id_compra'])) ? 'Salvar' : 'Cadastrar' ?></button>
    </form>

    <hr>

    <!-- Exibição dos itens de compra -->
    <h2>Lista de Itens de Compra</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Codigo da Compra</th>
                <th>Codigo do Produto</th>
                <th>Nome Produto</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['id_compra']) ?></td>
                    <td><?= htmlspecialchars($item['id_prod']) ?></td>
                    <td><?= htmlspecialchars($item['nome_prod']) ?></td>
                    <td><?= htmlspecialchars($item['preco_items_compra']) ?></td>
                    <td>
                        <a href="itens_compra.php?id_compra=<?= $item['id_compra'] ?>&id_prod=<?= $item['id_prod'] ?>"
                            onclick="return confirm('Tem certeza que deseja remover este item de compra?')">Remover</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>
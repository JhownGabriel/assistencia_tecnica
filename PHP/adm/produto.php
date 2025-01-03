<?php
session_start();
include_once '../includes/dbconnect.php';

$erro = '';
$success = '';

// Inserir/Atualizar Produto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["nome_prod"], $_POST["preco_venda"], $_POST["estoque_minimo"], $_POST["status_prod"])) {
        if (empty($_POST["nome_prod"]) || empty($_POST["preco_venda"]) || empty($_POST["estoque_minimo"]) || empty($_POST["status_prod"])) {
            $erro = "Todos os campos são obrigatórios.";
        } else {
            $id_prod = isset($_POST["id_prod"]) ? $_POST["id_prod"] : -1;
            $nome_prod = $_POST["nome_prod"];
            $marca = $_POST["marca"];
            $desc_prod = $_POST["desc_prod"];
            $preco_venda_prod = str_replace(['R$ ', '.', ','], ['', '', '.'], $_POST["preco_venda"]);
            $estoque_minimo = $_POST["estoque_minimo"];
            $status_prod = $_POST["status_prod"];

            if ($id_prod == -1) { // Inserir novo produto
                $stmt = $mysqli->prepare("INSERT INTO Produto (nome_prod, marca, desc_prod, preco_venda, estoque_minimo, status_prod) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssdis", $nome_prod, $marca, $desc_prod, $preco_venda_prod, $estoque_minimo, $status_prod);

                if ($stmt->execute()) {
                    $success = "Produto cadastrado com sucesso.";
                } else {
                    $erro = "Erro ao cadastrar produto: " . $stmt->error;
                }
            } else { // Atualizar produto existente
                $stmt = $mysqli->prepare("UPDATE Produto SET nome_prod = ?, marca = ?, desc_prod = ?, preco_venda = ?, estoque_minimo = ?, status_prod = ? WHERE id_prod = ?");
                $stmt->bind_param("sssdisi", $nome_prod, $marca, $desc_prod, $preco_venda_prod, $estoque_minimo, $status_prod, $id_prod);

                if ($stmt->execute()) {
                    $success = "Produto atualizado com sucesso.";
                } else {
                    $erro = "Erro ao atualizar produto: " . $stmt->error;
                }
            }
        }
    } else {
        $erro = "Todos os campos são obrigatórios.";
    }
}

// Desabilitar Produto
if (isset($_GET["id_prod"]) && is_numeric($_GET["id_prod"]) && isset($_GET["del"])) {
    $id_prod = (int) $_GET["id_prod"];
    $stmt = $mysqli->prepare("UPDATE Produto SET status_prod = 'Desabilitado' WHERE id_prod = ?"); // Supondo que 'Desabilitado' é um dos status
    $stmt->bind_param('i', $id_prod);
    if ($stmt->execute()) {
        $success = "Produto desabilitado com sucesso.";
    } else {
        $erro = "Erro ao desabilitar produto: " . $stmt->error;
    }
}

// Listar Produtos
$result = $mysqli->query("SELECT * FROM Produto WHERE status_prod != 'Desabilitado'"); // Apenas produtos ativos
?>

<?php require_once 'headerCRUD.php'; ?>
<link rel="stylesheet" href="styleCRUD/stylecrud.css" type="text/css">
<body>
    <h1>Cadastro de Produtos</h1>

    <?php if (!empty($erro)): ?>
        <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <!-- Formulário para adicionar ou editar produto -->
    <form action="produto.php" method="POST">
        <input type="hidden" name="id_prod" value="<?= isset($_POST['id_prod']) ? $_POST['id_prod'] : -1 ?>">
            <label for="nome_prod">Nome do Produto:</label><br>
            <input type="text" name="nome_prod" value="<?= isset($_POST['nome_prod']) ? htmlspecialchars($_POST['nome_prod']) : '' ?>" required><br><br>

            <label for="marca">Marca:</label><br>
            <input type="text" name="marca" value="<?= isset($_POST['marca']) ? htmlspecialchars($_POST['marca']) : '' ?>"><br><br>

            <label for="desc_prod">Descrição:</label><br>
            <input type="text" name="desc_prod" value="<?= isset($_POST['desc_prod']) ? htmlspecialchars($_POST['desc_prod']) : '' ?>"><br><br>

            <label for="preco_venda">Preço do Produto:</label><br>
            <input type="text" id="preco_venda" name="preco_venda" placeholder="R$ 0,00" value="<?= isset($_POST['preco_venda']) ? htmlspecialchars($_POST['preco_venda']) : '' ?>" required><br><br>

            
        <script>
            document.getElementById('preco_venda').addEventListener('input', function (e) {
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

            <label for="estoque_minimo">Estoque Mínimo:</label><br>
            <input type="number" name="estoque_minimo" min="1" value="<?= isset($_POST['estoque_minimo']) ? htmlspecialchars($_POST['estoque_minimo']) : '' ?>" required><br><br>

            <label for="status_prod">Status:</label><br>
            <input type="text" name="status_prod" value="<?= isset($_POST['status_prod']) ? htmlspecialchars($_POST['status_prod']) : '' ?>" required><br><br> 

            <button type="submit"><?= (isset($_POST['id_prod']) && $_POST['id_prod'] != -1) ? 'Salvar' : 'Cadastrar' ?></button>
    </form>

    <hr>

    <!-- Exibição dos produtos -->
    <h2>Lista de Produtos</h2>
    <table>
        <thead>
            <tr>
                <th>Codigo</th>
                <th>Nome</th>
                <th>Marca</th>
                <th>Descrição</th>
                <th>Preço de Venda</th>
                <th>Estoque Mínimo</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($produto = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($produto['id_prod']) ?></td>
                    <td><?= htmlspecialchars($produto['nome_prod']) ?></td>
                    <td><?= htmlspecialchars($produto['marca']) ?></td>
                    <td><?= htmlspecialchars($produto['desc_prod']) ?></td>
                    <td><?= htmlspecialchars($produto['preco_venda']) ?></td>
                    <td><?= htmlspecialchars($produto['estoque_minimo']) ?></td>
                    <td><?= htmlspecialchars($produto['status_prod']) ?></td>
                    <td>
                        <a href="produto.php?id_prod=<?= $produto['id_prod'] ?>&del=1" onclick="return confirm('Tem certeza que deseja desabilitar este produto?')" class="btn btn-danger btn-sm">Desabilitar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

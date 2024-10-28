<?php
session_start();
include_once '../PHP/includes/dbconnect.php';
    $result = $mysqli->query("SELECT * FROM Produto WHERE status_prod != 'Desabilitado'"); // Apenas produtos ativos
?>
<?php
    require_once 'header.php';
?>
    <main>
        <div class="usadosbg">
            <table class="usadostable">
                <tr>
                    <th>Marca</th>
                    <th>Preço</th>
                    <th>Nome Produto</th>
                    <th>Descriçao</th>
                </tr>
            <?php while ($produto = $result->fetch_assoc()): ?>
                <tr id="divisoriaprod">
                    <div class="fundoprod">
                        <div id="produtos">
                        <td><?= htmlspecialchars($produto['marca']) ?></td>
                        <td>R$ <?= htmlspecialchars($produto['preco_venda'])?></td>
                        <td><?= htmlspecialchars($produto['desc_prod']) ?></td>
                        </div>
                        <td><a id="linkdoprod" href="modelo.php?id=<?= $produto['id_prod'] ?>">Ver detalhes</a></td>
                    </div>
                </tr>
            <?php endwhile; ?>
            </table>
        </div>
    </main>
<?php
    require_once 'footer.php';
?>
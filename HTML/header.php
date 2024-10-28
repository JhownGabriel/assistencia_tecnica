
<?php
// Verifica se o usuário está logado
$logado = isset($_SESSION['logado']) && $_SESSION['logado'];  // Se a variável 'logado' estiver definida e for verdadeira
$nome = isset($_SESSION['nome_usu']) ? $_SESSION['nome_usu'] : 'NENHUM';  // Nome do usuário ou 'NENHUM' se não estiver logado
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../CSS/style.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Verifica se o usuário está logado com base na variável PHP
            const isLoggedIn = <?php echo $logado ? 'true' : 'false'; ?>;
            
            // Se o usuário estiver logado, remove os botões de login e criar conta
            if (isLoggedIn) {
                const criarConta = document.getElementById("criar_conta");
                if (criarConta) {
                    criarConta.remove(); // Remove o botão de criar conta e login
                }
            } else {
                // Se o usuário não estiver logado, remove o botão de logout e admin
                const logout = document.getElementById("logout");
                const adminCrud = document.getElementById("adminCrud");
                if (logout) {
                    logout.remove();
                }
                if (adminCrud) {
                    adminCrud.remove();
                }
            }
        });
    </script>
    <style>
        * {
            padding: 0px;
            margin: 0px;
            font-family: "Josefin Sans", sans-serif;
            font-style: normal;
        }

        a {
            text-decoration: none;
            color: #FFFFFF;
        }
        .usadosbg {
            width: 50%;
            margin: 10px auto 0;
            background-color: grey;
            box-shadow: 5px 5px 10px black;
            padding: 10px;
        }
        .usadosbg th {
            background-color: blue;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar d-flex justify-content-between align-items-center px-3">
            <div class="d-flex align-items-center">
                <img src="../images/logoazul.png" class="d-inline-block align-top" alt="Logo" />
            </div>
            <div id="criar_conta" class="d-flex align-items-center">
                <li class="btn btn-warning mb-0"><a href="login.php">Login</a></li>
                <li class="btn btn-warning mb-0"><a href="cadastro.php" id="login-account">Criar Conta</a></li>
                <img src="../images/login.png" alt="Conta" id="conta_foto" class="ms-3" />
            </div>
        </nav>
    </header>
</body>
    <?php
        $nome = isset($_SESSION['nome_usu']) ? $_SESSION['nome_usu'] : 'NENHUM';  //definindo o nome do usuario em caso de nulo
    ?>
    <nav id="navbar">
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>
        <ul>
        <?php  
            if ($nome == 'NENHUM'){
                echo '<li><a id="navbar" class="active" href="index.php">Home</a></li>';
            }else{
                echo '<li><a class="active" id="navbar" href="login.php">Olá, ' . htmlspecialchars($nome['nome_usu']) . '</a></li>';
            } 
        ?>
            <li><a class="active" href="produtos.php">usados</a></li>
            <li><a class="active" href="escolhadeserviço.php">serviços</a></li>
            <li><a class="active" href="dicas.php">dicas</a></li>
            <li class="active" class="account" id="logout"><a href="../PHP/includes/logout.php">LOGOUT</a></li>
            <li style="background-color: #000; border-radius: 5px;" class="account" id="adminCrud"><a href="../PHP/adm/CRUD.php">ADMINISTRADOR</a></li>
        </ul>
    </nav>
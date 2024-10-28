<?php
session_start();
include_once '../PHP/includes/dbconnect.php';
?>
<?php
    require_once 'header.php';
?>
    <main>
    <div class="fundoserv">
        <div class="servicos">
            <p>Mecanica de microondas</p>
            <img src="../images/microondasft.png" alt="microondas">
        </div>
        <div class="servicos">
            <p>Mecanica de maquina de lavar</p>
            <img src="../images/maquinadelavarft.png" alt="maquinadelavar">
        </div>
    </div>
    </main>
<?php
    require_once 'footer.php';
?>
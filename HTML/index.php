<?php
session_start();
include_once '../PHP/includes/dbconnect.php';
?>

<?php
    require_once 'header.php';
?>
    <main>
        <div class="carrosel">
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../images/carro ok 1.jpg" class="d-block w-100" alt="..." id="carroimg">
                    </div>
                    <div class="carousel-item">
                        <img src="../images/tela.png" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="../images/maquina de lavar 1.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="../images/microondas ok 1.jpg" class="d-block w-100" alt="...">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <div id="trabalho_content">
            <div id="trabalho_infos">
                <h2>Especializado em manutenções de maquinas de lavar
                    e microondas há 12 anos.
                </h2>
            </div>
            
        </div>
    </main>
    <?php
        require_once 'footer.php';
    ?>

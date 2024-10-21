<?php
    require_once '../includes/verificar_login.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slider Animado com Textos Explicativos</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #333;
            font-size: 2rem;
        }

        .slider-container {
            width: 80%;
            margin: 50px auto;
            overflow: hidden;
            position: relative;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .slider {
            display: flex;
            transition: transform 1s ease-in-out;
        }

        .slide {
            min-width: 100%;
            position: relative;
            color: white;
            font-size: 1.5rem;
            text-align: center;
        }

        /* Background Images for Slides */
        .slide:nth-child(1) {
            background-image: url('https://img.freepik.com/free-photo/handshake-business-men-concept_53876-31214.jpg?t=st=1729390462~exp=1729394062~hmac=7a865f7aa70c8d3757a3632e23d1e116debadb0309168c0e8973141e3f27b674&w=1060');
        }

        .slide:nth-child(2) {
            background-image: url('https://img.freepik.com/free-photo/cyber-monday-shopping-sales_23-2148688550.jpg?t=st=1729390680~exp=1729394280~hmac=730dbbe7c1923cfea63f1bfdc1431b132440a9eb6fa3d850880e4cc0108131ff&w=360');
        }

        .slide:nth-child(3) {
            background-image: url('https://img.freepik.com/free-photo/young-man-working-warehouse-with-boxes_1303-16604.jpg?t=st=1729391099~exp=1729394699~hmac=cf39191df87ec1a6bf0cfb990878e5dc6731ce52ca7d9a921dde17141768b4aa&w=1060');
        }

        .slide:nth-child(4) {
            background-image: url('https://img.freepik.com/free-photo/delivery-man-with-mask-client-paying_23-2148890021.jpg?t=st=1729392347~exp=1729395947~hmac=73e90bc777ba970c453209a80baefda464bb910ef269a727ab76658d5160e911&w=1060');
        }

        .slide:nth-child(5) {
            background-image: url('https://images.unsplash.com/photo-1512428559087-560fa5ceab42?auto=format&fit=crop&w=800&q=80');
        }

        .slide:nth-child(6) {
            background-image: url('https://img.freepik.com/free-photo/plumber-man_1368-539.jpg?t=st=1729392398~exp=1729395998~hmac=45fe1edd8410163d9b66e607279957dcf3a9cda07ee537dc7874adabcf66a683&w=360');
        }

        .slide:nth-child(7) {
            background-image: url('https://images.unsplash.com/photo-1542856391-55b01412795c?auto=format&fit=crop&w=800&q=80');
        }

        /* Style for all slides */
        .slide {
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            transition: 0.5s;
        }

        .slide a {
            display: inline-block;
            padding: 15px 30px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s ease-in-out;
        }

        .slide a:hover {
            background-color: rgba(0, 0, 0, 0.9);
        }

        /* Text Explanations */
        .slide p {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 1.1rem;
            margin: 0;
        }

        /* Slider Navigation */
        .slider-nav {
            text-align: center;
            margin-top: 20px;
        }

        .slider-nav button {
            margin: 5px;
            padding: 10px 15px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
        }

        .slider-nav button:hover {
            background-color: #0056b3;
        }

        /* Dots Navigation */
        .dots {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }

        .dot {
            height: 15px;
            width: 15px;
            margin: 0 5px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.3s ease-in-out;
        }

        .dot.active {
            background-color: #007bff;
        }
    </style>
</head>

<body>
    <main>
        <h1>Área de Gerenciamento de Clientes</h1>
        <div class="slider-container">
            <div class="slider">
                <div class="slide">
                    <p>Cliente: Cadastrar e Verificar Clientes</p>
                    <a href="cliente.php">Acessar Cliente</a>
                </div>
                <div class="slide">
                    <p>Compra: Verificar registros de compras de clientes</p>
                    <a href="compra.php">Acessar Compra</a>
                </div>
                <div class="slide">
                    <p>Fornecedor: Cadastrar e Verificar Fornecedores</p>
                    <a href="fornecedor.php">Acessar Fornecedor</a>
                </div>
                <div class="slide">
                    <p>Pedido: Cadastrar e Verificar Pedidos</p>
                    <a href="pedido.php">Acessar Pedido</a>
                </div>
                <div class="slide">
                    <p>Produto: Cadastrar e Verificar Produtos</p>
                    <a href="produto.php">Acessar Produto</a>
                </div>
                <div class="slide">
                    <p>Serviço: Cadastrar e Verificar Serviços</p>
                    <a href="servico.php">Acessar Serviço</a>
                </div>
                <div class="slide">
                    <p>Ordem de Serviço: Cadastrar e Verificar Ordem de Serviços</p>
                    <a href="servico.php">Acessar Serviço</a>
                </div>
            </div>
        </div>

        <div class="slider-nav">
            <button onclick="prevSlide()">Anterior</button>
            <button onclick="nextSlide()">Próximo</button>
        </div>

        <div class="dots">
            <span class="dot" onclick="currentSlide(0)"></span>
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="dot" onclick="currentSlide(4)"></span>
            <span class="dot" onclick="currentSlide(5)"></span>
        </div>
    </main>

    <script>
        const slider = document.querySelector('.slider');
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        let currentIndex = 0;

        function nextSlide() {
            currentIndex++;
            if (currentIndex >= slides.length) {
                currentIndex = 0;
            }
            updateSliderPosition();
        }

        function prevSlide() {
            currentIndex--;
            if (currentIndex < 0) {
                currentIndex = slides.length - 1;
            }
            updateSliderPosition();
        }

        function updateSliderPosition() {
            const offset = -currentIndex * 100;
            slider.style.transform = `translateX(${offset}%)`;
            updateDots();
        }

        function updateDots() {
            dots.forEach(dot => dot.classList.remove('active'));
            dots[currentIndex].classList.add('active');
        }

        function currentSlide(index) {
            currentIndex = index;
            updateSliderPosition();
        }
    </script>
</body>
</html>

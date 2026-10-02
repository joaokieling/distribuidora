<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUX — Distribuidora de Bebidas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="navbar">
    <a href="index.php" class="logo">LUX</a>

    <nav>
        <ul class="nav-links">
            <li><a href="index.php">Início</a></li>
            <li><a href="pages/produtos.php">Bebidas</a></li>
            <li><a href="#">Ofertas</a></li>
            <li><a href="#">Sobre</a></li>
        </ul>
    </nav>

    <div class="nav-icons">
        <a href="pages/pesquisa.php" title="Pesquisar">⌕</a>
        <a href="#" title="Minha conta">♙</a>
        <a href="pages/carrinho.php" title="Carrinho">🛒</a>
    </div>
</header>

<main>
    <section class="hero">
        <h1>O SABOR DO<br><span>SEU MOMENTO.</span></h1>
        <p>Bebidas para todos os momentos. Qualidade, variedade e sabor em um só lugar.</p>
        <a href="pages/produtos.php" class="btn">VER BEBIDAS</a>
    </section>

    <section style="padding: 40px 0;">
        <div class="section-title">
            <h2>ENCONTRE SUA BEBIDA</h2>
            <p>Explore nossas principais categorias</p>
        </div>

        <div class="category-grid">
            <a href="pages/produtos.php" class="category-card">
                <h3>🍺 Cervejas</h3>
                <p>Geladas e refrescantes</p>
            </a>
            <a href="pages/produtos.php" class="category-card">
                <h3>🥃 Destilados</h3>
                <p>Para momentos especiais</p>
            </a>
            <a href="pages/produtos.php" class="category-card">
                <h3>🍷 Vinhos</h3>
                <p>Seleção premium</p>
            </a>
            <a href="pages/produtos.php" class="category-card">
                <h3>🥂 Espumantes</h3>
                <p>Para comemorar</p>
            </a>
            <a href="pages/produtos.php" class="category-card">
                <h3>⚡ Energéticos</h3>
                <p>Mais disposição</p>
            </a>
            <a href="pages/produtos.php" class="category-card">
                <h3>💧 Sem Álcool</h3>
                <p>Para toda a família</p>
            </a>
        </div>
    </section>
</main>

</body>
</html>
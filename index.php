<<<<<<< HEAD
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
=======
<?php

require_once 'Pessoa.php';
require_once 'Bebida.php';
require_once 'Estoque.php';
require_once 'Vendedor.php';
require_once 'Comprador.php';
require_once 'Repositor.php';
require_once 'Venda.php';

echo "<h1>🍺 Distribuidora de Bebidas</h1>";

// ---------- 1. Cadastro de pessoas ----------
$vendedor  = new Vendedor("Carlos Souza", "111.111.111-11", 30, "V001", 5.0);
$repositor = new Repositor("Marcos Lima", "222.222.222-22", 25, "Depósito A");

$compradorMaior = new Comprador("João Silva",   "333.333.333-33", 22);
$compradorMenor = new Comprador("Pedro Júnior", "444.444.444-44", 16);

$vendedor->exibirDados();
$repositor->exibirDados();
$compradorMaior->exibirDados();
$compradorMenor->exibirDados();

// ---------- 2. Cadastro de bebidas ----------
$cerveja  = new Bebida("Cerveja Pilsen", 5.50, 4.8, 350);
$vodka    = new Bebida("Vodka Premium",  89.90, 40.0, 1000);
$refri    = new Bebida("Refrigerante Cola", 7.00, 0.0, 2000);

$cerveja->exibirDados();
$vodka->exibirDados();
$refri->exibirDados();

// ---------- 3. Estoque ----------
$estoque = new Estoque();
$repositor->repor($estoque, $cerveja, 100);
$repositor->repor($estoque, $vodka,   20);
$repositor->repor($estoque, $refri,   50);

$estoque->exibirEstoque();

// ---------- 4. Venda com MAIOR de idade ----------
echo "<h2>🛒 Venda 1 — João (22 anos)</h2>";
$venda1 = new Venda($compradorMaior, $vendedor, $estoque);
$venda1->adicionarItem($cerveja, 6);
$venda1->adicionarItem($vodka,   1);
$venda1->finalizar();

// ---------- 5. Venda com MENOR de idade (bloqueio) ----------
echo "<h2>🛒 Venda 2 — Pedro (16 anos)</h2>";
$venda2 = new Venda($compradorMenor, $vendedor, $estoque);
$venda2->adicionarItem($cerveja, 2);   // 🚫 bloqueado
$venda2->adicionarItem($refri,   3);   // ✅ permitido (não alcoólica)

// Finaliza só se tiver item válido
if ($venda2->calcularTotal() > 0) {
    $venda2->finalizar();
} else {
    echo "❌ Venda 2 não pôde ser finalizada (nenhum item válido).<br>";
}

// ---------- 6. Estoque final ----------
$estoque->exibirEstoque();
>>>>>>> 0edd241b3532b24208dc1d3fcc558f61764ca2a1

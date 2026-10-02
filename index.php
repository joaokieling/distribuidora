<?php

require_once 'Pessoa.php';
require_once 'Categoria.php';
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
$cerveja = new Bebida("Cerveja Pilsen", 5.50, 4.8, 350, Categoria::CERVEJA);
$vodka   = new Bebida("Vodka Premium", 89.90, 40.0, 1000, Categoria::DESTILADO);
$refri   = new Bebida("Refrigerante Cola", 7.00, 0.0, 2000, Categoria::REFRIGERANTE);

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
$venda2->adicionarItem($refri,   3);   // ✅ permitido

if ($venda2->calcularTotal() > 0) {
    $venda2->finalizar();
} else {
    echo "❌ Venda 2 não pôde ser finalizada (nenhum item válido).<br>";
}

// ---------- 6. Estoque final ----------
$estoque->exibirEstoque();
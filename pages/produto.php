<?php
session_start();

require_once '../config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("Produto inválido.");
}

$sql = "
    SELECT 
        bebidas.*,
        categorias.nome AS categoria_nome
    FROM bebidas
    INNER JOIN categorias
        ON bebidas.categoria_id = categorias.id
    WHERE bebidas.id = :id
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);

$bebida = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bebida) {
    die("Bebida não encontrada.");
}


/*
|--------------------------------------------------------------------------
| ADICIONAR AO CARRINHO
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $quantidade = filter_input(
        INPUT_POST,
        'quantidade',
        FILTER_VALIDATE_INT
    );

    if (!$quantidade || $quantidade < 1) {
        $quantidade = 1;
    }


    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }


    if (isset($_SESSION['carrinho'][$id])) {

        $_SESSION['carrinho'][$id] += $quantidade;

    } else {

        $_SESSION['carrinho'][$id] = $quantidade;

    }


    header('Location: carrinho.php');

    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($bebida['nome']) ?> — LUX
    </title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>

<body>


<header class="navbar">

    <a
        href="../index.php"
        class="logo"
    >
        LUX
    </a>


    <nav>

        <ul class="nav-links">

            <li>
                <a href="../index.php">
                    Início
                </a>
            </li>

            <li>
                <a href="produtos.php">
                    Bebidas
                </a>
            </li>

            <li>
                <a href="#">
                    Ofertas
                </a>
            </li>

            <li>
                <a href="#">
                    Sobre
                </a>
            </li>

        </ul>

    </nav>


    <div class="nav-icons">

        <span>⌕</span>

        <span>♙</span>

        <a href="carrinho.php">
            🛒
        </a>

    </div>

</header>


<main>

    <section class="product-detail">


        <div class="product-image">

            <div class="image-placeholder">

                <span>
                    LUX
                </span>

            </div>

        </div>


        <div class="product-info">


            <span class="product-category">

                <?= htmlspecialchars(
                    $bebida['categoria_nome']
                ) ?>

            </span>


            <h1>

                <?= htmlspecialchars(
                    $bebida['nome']
                ) ?>

            </h1>


            <p class="product-description">

                Uma bebida selecionada pela LUX
                para proporcionar qualidade e sabor
                em todos os momentos.

            </p>


            <div class="product-details">


                <div>

                    <span class="detail-label">
                        Volume
                    </span>

                    <strong>
                        <?= $bebida['volume_ml'] ?> ml
                    </strong>

                </div>


                <div>

                    <span class="detail-label">
                        Teor alcoólico
                    </span>

                    <strong>
                        <?= $bebida['teor_alcoolico'] ?>%
                    </strong>

                </div>


            </div>


            <div class="product-price">

                R$

                <?= number_format(
                    $bebida['preco'],
                    2,
                    ',',
                    '.'
                ) ?>

            </div>


            <!-- FORMULÁRIO DO CARRINHO -->

            <form
                method="POST"
                action=""
            >


                <div class="quantity">

                    <button
                        type="button"
                        onclick="diminuirQuantidade()"
                    >
                        −
                    </button>


                    <input
                        type="number"
                        name="quantidade"
                        id="quantidade"
                        value="1"
                        min="1"
                    >


                    <button
                        type="button"
                        onclick="aumentarQuantidade()"
                    >
                        +
                    </button>

                </div>


                <button
                    type="submit"
                    class="btn product-btn"
                >

                    ADICIONAR AO CARRINHO

                </button>


            </form>


            <a
                href="produtos.php"
                class="back-link"
            >

                ← Voltar para bebidas

            </a>


        </div>

    </section>

</main>


<script>

function aumentarQuantidade() {

    const campo =
        document.getElementById("quantidade");

    campo.value =
        parseInt(campo.value) + 1;

}


function diminuirQuantidade() {

    const campo =
        document.getElementById("quantidade");


    if (parseInt(campo.value) > 1) {

        campo.value =
            parseInt(campo.value) - 1;

    }

}

</script>


</body>

</html>
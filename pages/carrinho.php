<?php

session_start();

require_once '../config/database.php';


/*
|--------------------------------------------------------------------------
| INICIALIZA O CARRINHO
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}


/*
|--------------------------------------------------------------------------
| REMOVER PRODUTO
|--------------------------------------------------------------------------
*/

if (isset($_GET['remover'])) {

    $id = filter_input(
        INPUT_GET,
        'remover',
        FILTER_VALIDATE_INT
    );


    if ($id && isset($_SESSION['carrinho'][$id])) {

        unset($_SESSION['carrinho'][$id]);

    }


    header('Location: carrinho.php');

    exit;
}


/*
|--------------------------------------------------------------------------
| BUSCAR PRODUTOS
|--------------------------------------------------------------------------
*/

$carrinho = [];

$total = 0;


if (!empty($_SESSION['carrinho'])) {

    $ids = array_keys($_SESSION['carrinho']);

    $placeholders = implode(
        ',',
        array_fill(0, count($ids), '?')
    );


    $sql = "
        SELECT *
        FROM bebidas
        WHERE id IN ($placeholders)
    ";


    $stmt = $pdo->prepare($sql);

    $stmt->execute($ids);

    $produtos = $stmt->fetchAll(
        PDO::FETCH_ASSOC
    );


    foreach ($produtos as $produto) {

        $quantidade =
            $_SESSION['carrinho'][$produto['id']];


        $subtotal =
            $produto['preco'] * $quantidade;


        $total += $subtotal;


        $produto['quantidade'] =
            $quantidade;


        $produto['subtotal'] =
            $subtotal;


        $carrinho[] =
            $produto;

    }

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
        Carrinho — LUX
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


<section class="cart-section">


    <div class="section-title">

        <h2>
            SEU CARRINHO
        </h2>

        <p>
            Revise seus produtos antes de continuar.
        </p>

    </div>


    <?php if (empty($carrinho)): ?>


        <div class="empty-cart">

            <h3>
                Seu carrinho está vazio.
            </h3>

            <p>
                Que tal escolher uma bebida?
            </p>

            <a
                href="produtos.php"
                class="btn"
            >
                VER BEBIDAS
            </a>

        </div>


    <?php else: ?>


        <div class="cart-container">


            <div class="cart-products">


                <?php foreach ($carrinho as $produto): ?>


                    <div class="cart-item">


                        <div class="cart-item-image">

                            LUX

                        </div>


                        <div class="cart-item-info">

                            <h3>

                                <?= htmlspecialchars(
                                    $produto['nome']
                                ) ?>

                            </h3>


                            <p>

                                <?= $produto['volume_ml'] ?>
                                ml

                            </p>


                            <span>

                                R$
                                <?= number_format(
                                    $produto['preco'],
                                    2,
                                    ',',
                                    '.'
                                ) ?>

                            </span>

                        </div>


                        <div class="cart-quantity">

                            <?= $produto['quantidade'] ?>

                        </div>


                        <div class="cart-subtotal">

                            R$
                            <?= number_format(
                                $produto['subtotal'],
                                2,
                                ',',
                                '.'
                            ) ?>

                        </div>


                        <a
                            href="carrinho.php?remover=<?= $produto['id'] ?>"
                            class="remove-product"
                        >
                            ×
                        </a>


                    </div>


                <?php endforeach; ?>


            </div>


            <aside class="cart-summary">


                <h3>
                    RESUMO DO PEDIDO
                </h3>


                <div class="summary-line">

                    <span>
                        Subtotal
                    </span>

                    <strong>

                        R$
                        <?= number_format(
                            $total,
                            2,
                            ',',
                            '.'
                        ) ?>

                    </strong>

                </div>


                <div class="summary-line">

                    <span>
                        Entrega
                    </span>

                    <strong>
                        A calcular
                    </strong>

                </div>


                <div class="summary-total">

                    <span>
                        Total
                    </span>

                    <strong>

                        R$
                        <?= number_format(
                            $total,
                            2,
                            ',',
                            '.'
                        ) ?>

                    </strong>

                </div>


                <a
    href="checkout.php"
    class="btn checkout-btn"
>
    FINALIZAR COMPRA
</a>


            </aside>


        </div>


    <?php endif; ?>


</section>


</main>


</body>

</html>
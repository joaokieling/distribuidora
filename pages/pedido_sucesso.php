<?php

session_start();


/*
|--------------------------------------------------------------------------
| VERIFICA ÚLTIMO PEDIDO
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['ultimo_pedido'])) {

    header('Location: ../index.php');

    exit;
}


$pedido =
    $_SESSION['ultimo_pedido'];

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
        Pedido realizado — LUX
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

        <a href="produtos.php">
            🛒
        </a>

    </div>

</header>


<main>

<section class="success-section">


    <div class="success-card">


        <div class="success-icon">
            ✓
        </div>


        <h1>
            PEDIDO REALIZADO!
        </h1>


        <p class="success-message">

            Obrigado pela sua compra,
            <strong>
                <?= htmlspecialchars(
                    $pedido['nome']
                ) ?>
            </strong>.

        </p>


        <div class="success-details">


            <div>

                <span>
                    Pedido
                </span>

                <strong>
                    #<?= htmlspecialchars(
                        $pedido['id']
                    ) ?>
                </strong>

            </div>


            <div>

                <span>
                    Pagamento
                </span>

                <strong>

                    <?= htmlspecialchars(
                        $pedido['pagamento']
                    ) ?>

                </strong>

            </div>


            <div>

                <span>
                    Total
                </span>

                <strong>

                    R$
                    <?= number_format(
                        $pedido['total'],
                        2,
                        ',',
                        '.'
                    ) ?>

                </strong>

            </div>


        </div>


        <p class="success-info">

            Seu pedido foi registrado com sucesso
            no sistema LUX.

        </p>


        <a
            href="produtos.php"
            class="btn"
        >

            CONTINUAR COMPRANDO

        </a>


    </div>

</section>

</main>


</body>

</html>
<?php

require_once '../config/database.php';

$sql = "SELECT * FROM bebidas ORDER BY nome ASC";

$stmt = $pdo->query($sql);

$bebidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Bebidas — LUX</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>

<body>

    <!-- MENU -->

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

            <span>🛒</span>

        </div>

    </header>


    <!-- PRODUTOS -->

    <main>

        <section class="categories">

            <div class="section-title">

                <h2>
                    NOSSAS BEBIDAS
                </h2>

                <p>
                    Encontre a bebida ideal para o seu momento.
                </p>

            </div>


            <div class="category-grid">

                <?php foreach ($bebidas as $bebida): ?>

                    <a
                        href="produto.php?id=<?= $bebida['id'] ?>"
                        class="category-card"
                    >

                        <div>

                            <h3>
                                <?= htmlspecialchars($bebida['nome']) ?>
                            </h3>


                            <p>
                                <?= $bebida['volume_ml'] ?> ml
                            </p>


                            <p>
                                <?= $bebida['teor_alcoolico'] ?>% álcool
                            </p>


                            <strong>

                                R$
                                <?= number_format(
                                    $bebida['preco'],
                                    2,
                                    ',',
                                    '.'
                                ) ?>

                            </strong>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>

        </section>

    </main>

</body>

</html>
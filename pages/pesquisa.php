<?php

require_once '../config/database.php';

$termo = trim($_GET['q'] ?? '');

$bebidas = [];

if ($termo !== '') {

    $sql = "
        SELECT
            bebidas.*,
            categorias.nome AS categoria_nome
        FROM bebidas
        INNER JOIN categorias
            ON bebidas.categoria_id = categorias.id
        WHERE bebidas.nome LIKE :termo
           OR categorias.nome LIKE :termo
        ORDER BY bebidas.nome ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'termo' => '%' . $termo . '%'
    ]);

    $bebidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        Pesquisa — LUX
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

        <a
            href="pesquisa.php"
            class="nav-search"
            title="Pesquisar"
        >
            ⌕
        </a>

        <a
            href="#"
            title="Minha conta"
        >
            ♙
        </a>

        <a
            href="carrinho.php"
            title="Carrinho"
        >
            🛒
        </a>

    </div>

</header>


<main>

<section class="search-section">


    <div class="section-title">

        <h2>
            PESQUISAR BEBIDAS
        </h2>

        <p>
            Encontre sua bebida favorita.
        </p>

    </div>


    <form
        method="GET"
        action="pesquisa.php"
        class="search-form"
    >

        <input
            type="text"
            name="q"
            placeholder="Digite o nome da bebida..."
            value="<?= htmlspecialchars($termo) ?>"
            autocomplete="off"
            required
        >

        <button
            type="submit"
            class="btn"
        >
            PESQUISAR
        </button>

    </form>


    <?php if ($termo !== ''): ?>

        <div class="search-result-title">

            <h3>
                RESULTADOS PARA:
                "<span><?= htmlspecialchars($termo) ?></span>"
            </h3>

            <p>
                <?= count($bebidas) ?>
                bebida(s) encontrada(s)
            </p>

        </div>


        <?php if (!empty($bebidas)): ?>

            <div class="category-grid">

                <?php foreach ($bebidas as $bebida): ?>

                    <a
                        href="produto.php?id=<?= $bebida['id'] ?>"
                        class="category-card"
                    >

                        <div>

                            <h3>
                                <?= htmlspecialchars(
                                    $bebida['nome']
                                ) ?>
                            </h3>

                            <p>
                                <?= htmlspecialchars(
                                    $bebida['categoria_nome']
                                ) ?>
                            </p>

                            <p>
                                <?= $bebida['volume_ml'] ?> ml
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

        <?php else: ?>

            <div class="search-empty">

                <h3>
                    Nenhuma bebida encontrada.
                </h3>

                <p>
                    Tente pesquisar por outro nome ou categoria.
                </p>

                <a
                    href="produtos.php"
                    class="btn"
                >
                    VER TODAS AS BEBIDAS
                </a>

            </div>

        <?php endif; ?>

    <?php endif; ?>


</section>

</main>


</body>

</html>

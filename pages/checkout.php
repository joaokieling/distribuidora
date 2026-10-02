<?php

session_start();

require_once '../config/database.php';


/*
|--------------------------------------------------------------------------
| VERIFICA SE EXISTE CARRINHO
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['carrinho']) ||
    empty($_SESSION['carrinho'])
) {
    header('Location: carrinho.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| VARIÁVEIS
|--------------------------------------------------------------------------
*/

$erro = '';

$ids = array_keys($_SESSION['carrinho']);

$placeholders = implode(
    ',',
    array_fill(0, count($ids), '?')
);


/*
|--------------------------------------------------------------------------
| BUSCA OS PRODUTOS
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        bebidas.*,
        categorias.nome AS categoria_nome,
        estoque.quantidade AS estoque_quantidade
    FROM bebidas
    INNER JOIN categorias
        ON bebidas.categoria_id = categorias.id
    INNER JOIN estoque
        ON bebidas.id = estoque.bebida_id
    WHERE bebidas.id IN ($placeholders)
";

$stmt = $pdo->prepare($sql);
$stmt->execute($ids);

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (empty($produtos)) {
    unset($_SESSION['carrinho']);

    header('Location: carrinho.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| CALCULA TOTAL
|--------------------------------------------------------------------------
*/

$total = 0;

foreach ($produtos as &$produto) {

    $quantidade =
        $_SESSION['carrinho'][$produto['id']];

    $produto['quantidade'] = $quantidade;

    $produto['subtotal'] =
        $produto['preco'] * $quantidade;

    $total += $produto['subtotal'];
}

unset($produto);


/*
|--------------------------------------------------------------------------
| PROCESSAMENTO DO PEDIDO
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');

    $cpf = trim($_POST['cpf'] ?? '');

    $idade = filter_input(
        INPUT_POST,
        'idade',
        FILTER_VALIDATE_INT
    );

    $pagamento = trim(
        $_POST['pagamento'] ?? ''
    );


    /*
    |--------------------------------------------------------------------------
    | VALIDAÇÕES BÁSICAS
    |--------------------------------------------------------------------------
    */

    if ($nome === '') {

        $erro = 'Informe seu nome.';

    } elseif (!preg_match('/^[0-9]{11}$/', $cpf)) {

        $erro = 'O CPF deve conter exatamente 11 números.';

    } elseif (
        $idade === false ||
        $idade < 1 ||
        $idade > 120
    ) {

        $erro = 'Informe uma idade válida.';

    } elseif (
        !in_array(
            $pagamento,
            ['PIX', 'CARTAO', 'DINHEIRO']
        )
    ) {

        $erro = 'Selecione uma forma de pagamento.';

    }


    /*
    |--------------------------------------------------------------------------
    | INICIA TRANSAÇÃO
    |--------------------------------------------------------------------------
    */

    if ($erro === '') {

        try {

            $pdo->beginTransaction();


            /*
            |--------------------------------------------------------------------------
            | VERIFICA ESTOQUE E MAIORIDADE
            |--------------------------------------------------------------------------
            */

            foreach ($produtos as $produto) {

                /*
                | Verifica se a quantidade ainda está disponível
                */

                if (
                    $produto['quantidade'] >
                    $produto['estoque_quantidade']
                ) {

                    throw new Exception(
                        "Estoque insuficiente para: " .
                        $produto['nome']
                    );
                }


                /*
                | Verifica bebida alcoólica
                */

                $ehAlcoolica =
                    $produto['teor_alcoolico'] >= 0.5;


                if (
                    $ehAlcoolica &&
                    $idade < 18
                ) {

                    throw new Exception(
                        "A bebida " .
                        $produto['nome'] .
                        " só pode ser vendida para maiores de 18 anos."
                    );
                }

            }


            /*
            |--------------------------------------------------------------------------
            | BUSCA OU CRIA O COMPRADOR
            |--------------------------------------------------------------------------
            */

            $sqlComprador = "
                SELECT id
                FROM pessoas
                WHERE cpf = :cpf
                LIMIT 1
            ";

            $stmtComprador =
                $pdo->prepare($sqlComprador);

            $stmtComprador->execute([
                'cpf' => $cpf
            ]);

            $comprador =
                $stmtComprador->fetch(PDO::FETCH_ASSOC);


            if ($comprador) {

                $compradorId =
                    $comprador['id'];

            } else {

                $sqlNovoComprador = "
                    INSERT INTO pessoas
                    (
                        nome,
                        cpf,
                        idade,
                        tipo
                    )
                    VALUES
                    (
                        :nome,
                        :cpf,
                        :idade,
                        'COMPRADOR'
                    )
                ";

                $stmtNovoComprador =
                    $pdo->prepare($sqlNovoComprador);

                $stmtNovoComprador->execute([
                    'nome' => $nome,
                    'cpf' => $cpf,
                    'idade' => $idade
                ]);

                $compradorId =
                    $pdo->lastInsertId();
            }


            /*
            |--------------------------------------------------------------------------
            | BUSCA UM VENDEDOR
            |--------------------------------------------------------------------------
            */

            $sqlVendedor = "
                SELECT id
                FROM pessoas
                WHERE tipo = 'VENDEDOR'
                LIMIT 1
            ";

            $stmtVendedor =
                $pdo->query($sqlVendedor);

            $vendedor =
                $stmtVendedor->fetch(PDO::FETCH_ASSOC);


            /*
            |--------------------------------------------------------------------------
            | CRIA VENDEDOR PADRÃO LUX SE NECESSÁRIO
            |--------------------------------------------------------------------------
            */

            if (!$vendedor) {

                $sqlCriarVendedor = "
                    INSERT INTO pessoas
                    (
                        nome,
                        cpf,
                        idade,
                        tipo,
                        matricula,
                        comissao
                    )
                    VALUES
                    (
                        'Vendedor LUX',
                        'LUX-VENDEDOR-001',
                        18,
                        'VENDEDOR',
                        'LUX001',
                        5.00
                    )
                ";

                $stmtCriarVendedor =
                    $pdo->prepare($sqlCriarVendedor);

                $stmtCriarVendedor->execute();

                $vendedorId =
                    $pdo->lastInsertId();

                $comissaoPercentual = 5.00;

            } else {

                $vendedorId =
                    $vendedor['id'];


                $sqlComissao = "
                    SELECT comissao
                    FROM pessoas
                    WHERE id = :id
                ";

                $stmtComissao =
                    $pdo->prepare($sqlComissao);

                $stmtComissao->execute([
                    'id' => $vendedorId
                ]);

                $dadosVendedor =
                    $stmtComissao->fetch(
                        PDO::FETCH_ASSOC
                    );

                $comissaoPercentual =
                    $dadosVendedor['comissao']
                    ?? 5.00;
            }


            /*
            |--------------------------------------------------------------------------
            | CALCULA COMISSÃO
            |--------------------------------------------------------------------------
            */

            $comissao =
                $total *
                ($comissaoPercentual / 100);


            /*
            |--------------------------------------------------------------------------
            | CRIA A VENDA
            |--------------------------------------------------------------------------
            */

            $sqlVenda = "
                INSERT INTO vendas
                (
                    comprador_id,
                    vendedor_id,
                    total,
                    comissao
                )
                VALUES
                (
                    :comprador_id,
                    :vendedor_id,
                    :total,
                    :comissao
                )
            ";

            $stmtVenda =
                $pdo->prepare($sqlVenda);

            $stmtVenda->execute([
                'comprador_id' => $compradorId,
                'vendedor_id' => $vendedorId,
                'total' => $total,
                'comissao' => $comissao
            ]);

            $vendaId =
                $pdo->lastInsertId();


            /*
            |--------------------------------------------------------------------------
            | INSERE OS ITENS E REDUZ ESTOQUE
            |--------------------------------------------------------------------------
            */

            foreach ($produtos as $produto) {


                /*
                | Insere item da venda
                */

                $sqlItem = "
                    INSERT INTO venda_itens
                    (
                        venda_id,
                        bebida_id,
                        quantidade,
                        preco_unitario,
                        subtotal
                    )
                    VALUES
                    (
                        :venda_id,
                        :bebida_id,
                        :quantidade,
                        :preco_unitario,
                        :subtotal
                    )
                ";

                $stmtItem =
                    $pdo->prepare($sqlItem);

                $stmtItem->execute([
                    'venda_id' =>
                        $vendaId,

                    'bebida_id' =>
                        $produto['id'],

                    'quantidade' =>
                        $produto['quantidade'],

                    'preco_unitario' =>
                        $produto['preco'],

                    'subtotal' =>
                        $produto['subtotal']
                ]);


                /*
                | Reduz estoque
                */

                $sqlEstoque = "
                    UPDATE estoque
                    SET quantidade = quantidade - :quantidade
                    WHERE bebida_id = :bebida_id
                ";

                $stmtEstoque =
                    $pdo->prepare($sqlEstoque);

                $stmtEstoque->execute([
                    'quantidade' =>
                        $produto['quantidade'],

                    'bebida_id' =>
                        $produto['id']
                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | FINALIZA TRANSAÇÃO
            |--------------------------------------------------------------------------
            */

            $pdo->commit();


            /*
            |--------------------------------------------------------------------------
            | GUARDA DADOS DO ÚLTIMO PEDIDO
            |--------------------------------------------------------------------------
            */

            $_SESSION['ultimo_pedido'] = [

                'id' =>
                    $vendaId,

                'total' =>
                    $total,

                'pagamento' =>
                    $pagamento,

                'nome' =>
                    $nome,

                'cpf' =>
                    $cpf

            ];


            /*
            |--------------------------------------------------------------------------
            | LIMPA CARRINHO
            |--------------------------------------------------------------------------
            */

            unset($_SESSION['carrinho']);


            /*
            |--------------------------------------------------------------------------
            | REDIRECIONA
            |--------------------------------------------------------------------------
            */

            header(
                'Location: pedido_sucesso.php'
            );

            exit;


        } catch (Exception $e) {


            /*
            |--------------------------------------------------------------------------
            | DESFAZ TRANSAÇÃO
            |--------------------------------------------------------------------------
            */

            if ($pdo->inTransaction()) {

                $pdo->rollBack();

            }


            $erro =
                $e->getMessage();

        }

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
        Checkout — LUX
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

<section class="checkout-section">


    <div class="section-title">

        <h2>
            FINALIZAR COMPRA
        </h2>

        <p>
            Preencha seus dados para continuar.
        </p>

    </div>


    <?php if ($erro !== ''): ?>

        <div class="checkout-error">

            <?= htmlspecialchars($erro) ?>

        </div>

    <?php endif; ?>


    <div class="checkout-container">


        <!-- DADOS DO COMPRADOR -->

        <div class="checkout-form">

            <h3>
                DADOS DO COMPRADOR
            </h3>


            <form
                method="POST"
                action=""
            >


                <div class="form-group">

                    <label for="nome">
                        Nome completo
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        placeholder="Digite seu nome"
                        value="<?= htmlspecialchars(
                            $_POST['nome'] ?? ''
                        ) ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="cpf">
                        CPF
                    </label>

                    <input
                        type="text"
                        id="cpf"
                        name="cpf"
                        placeholder="00000000000"
                        maxlength="11"
                        inputmode="numeric"
                        pattern="[0-9]{11}"
                        value="<?= htmlspecialchars(
                            $_POST['cpf'] ?? ''
                        ) ?>"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="idade">
                        Idade
                    </label>

                    <input
                        type="number"
                        id="idade"
                        name="idade"
                        min="1"
                        max="120"
                        placeholder="Digite sua idade"
                        value="<?= htmlspecialchars(
                            $_POST['idade'] ?? ''
                        ) ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="pagamento">
                        Forma de pagamento
                    </label>

                    <select
                        id="pagamento"
                        name="pagamento"
                        required
                    >

                        <option value="">
                            Selecione
                        </option>

                        <option
                            value="PIX"
                            <?= (
                                ($_POST['pagamento'] ?? '') === 'PIX'
                            )
                                ? 'selected'
                                : ''
                            ?>
                        >
                            PIX
                        </option>

                        <option
                            value="CARTAO"
                            <?= (
                                ($_POST['pagamento'] ?? '') === 'CARTAO'
                            )
                                ? 'selected'
                                : ''
                            ?>
                        >
                            Cartão
                        </option>

                        <option
                            value="DINHEIRO"
                            <?= (
                                ($_POST['pagamento'] ?? '') === 'DINHEIRO'
                            )
                                ? 'selected'
                                : ''
                            ?>
                        >
                            Dinheiro
                        </option>

                    </select>

                </div>


                <button
                    type="submit"
                    class="btn checkout-submit"
                >

                    CONFIRMAR PEDIDO

                </button>


            </form>

        </div>


        <!-- RESUMO -->

        <aside class="checkout-summary">

            <h3>
                RESUMO DO PEDIDO
            </h3>


            <?php foreach ($produtos as $produto): ?>


                <div class="checkout-product">

                    <div>

                        <strong>

                            <?= htmlspecialchars(
                                $produto['nome']
                            ) ?>

                        </strong>

                        <span>

                            <?= $produto['quantidade'] ?>
                            x
                            R$
                            <?= number_format(
                                $produto['preco'],
                                2,
                                ',',
                                '.'
                            ) ?>

                        </span>

                    </div>


                    <strong>

                        R$
                        <?= number_format(
                            $produto['subtotal'],
                            2,
                            ',',
                            '.'
                        ) ?>

                    </strong>

                </div>


            <?php endforeach; ?>


            <div class="checkout-total">

                <span>
                    TOTAL
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


        </aside>


    </div>

</section>

</main>


</body>

</html>
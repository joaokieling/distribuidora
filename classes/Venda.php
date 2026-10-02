<?php

require_once 'Comprador.php';
require_once 'Vendedor.php';
require_once 'Bebida.php';
require_once 'Estoque.php';

class Venda {
    private Comprador $comprador;
    private Vendedor  $vendedor;
    private Estoque   $estoque;

    /** @var array<int, array{bebida:Bebida, qtd:int}> */
    private array $itens = [];

    private bool $finalizada = false;

    public function __construct(Comprador $comprador, Vendedor $vendedor, Estoque $estoque) {
        $this->comprador = $comprador;
        $this->vendedor  = $vendedor;
        $this->estoque   = $estoque;
    }

    public function adicionarItem(Bebida $bebida, int $quantidade): void {
        if ($this->finalizada) {
            throw new RuntimeException("Venda já finalizada.");
        }
        if ($quantidade <= 0) {
            throw new InvalidArgumentException("Quantidade inválida.");
        }

        if (!$this->comprador->podeComprar($bebida)) {
            echo "<strong>VENDA BLOQUEADA:</strong> {$this->comprador->getNome()} " .
                 "({$this->comprador->getIdade()} anos) não pode comprar " .
                 "'{$bebida->getNome()}' (bebida alcoólica).<br>";
            return;
        }

        if ($this->estoque->consultar($bebida) < $quantidade) {
            throw new RuntimeException("Estoque insuficiente para '{$bebida->getNome()}'.");
        }

        $this->itens[] = ['bebida' => $bebida, 'qtd' => $quantidade];
    }

    public function calcularTotal(): float {
        $total = 0.0;
        foreach ($this->itens as $item) {
            $total += $item['bebida']->getPreco() * $item['qtd'];
        }
        return $total;
    }

    public function finalizar(): void {
        if ($this->finalizada) {
            throw new RuntimeException("Venda já foi finalizada.");
        }
        if (empty($this->itens)) {
            throw new RuntimeException("Não há itens na venda.");
        }

        foreach ($this->itens as $item) {
            $this->estoque->remover($item['bebida'], $item['qtd']);
        }

        $this->finalizada = true;
        $total     = $this->calcularTotal();
        $comissao  = $this->vendedor->calcularComissao($total);

        echo "<h3>NOTA FISCAL</h3>";
        echo "Comprador: {$this->comprador->getNome()}<br>";
        echo "Vendedor: {$this->vendedor->getNome()}<br>";
        echo "-----------------------------<br>";
        foreach ($this->itens as $item) {
            $sub = $item['bebida']->getPreco() * $item['qtd'];
            echo "{$item['qtd']}x {$item['bebida']->getNome()} — R$ " .
                 number_format($sub, 2, ',', '.') . "<br>";
        }
        echo "-----------------------------<br>";
        echo "<strong>Total: R$ " . number_format($total, 2, ',', '.') . "</strong><br>";
        echo "Comissão do vendedor: R$ " . number_format($comissao, 2, ',', '.') . "<br>";
        echo "Venda finalizada.<br>";
    }

    public function isFinalizada(): bool { return $this->finalizada; }
}
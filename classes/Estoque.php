<?php

require_once 'Bebida.php';

class Estoque {
    /** @var array<string,int> */
    private array $itens = [];

    public function adicionar(Bebida $bebida, int $quantidade): void {
        if ($quantidade <= 0) {
            throw new InvalidArgumentException("Quantidade deve ser positiva.");
        }
        $nome = $bebida->getNome();
        $this->itens[$nome] = ($this->itens[$nome] ?? 0) + $quantidade;
    }

    public function remover(Bebida $bebida, int $quantidade): void {
        if ($quantidade <= 0) {
            throw new InvalidArgumentException("Quantidade deve ser positiva.");
        }

        $nome = $bebida->getNome();
        $atual = $this->itens[$nome] ?? 0;

        if ($quantidade > $atual) {
            throw new RuntimeException("Estoque insuficiente de '{$nome}'. Disponível: {$atual}.");
        }
        $this->itens[$nome] = $atual - $quantidade;
    }

    public function consultar(Bebida $bebida): int {
        return $this->itens[$bebida->getNome()] ?? 0;
    }

    public function exibirEstoque(): void {
        echo "<h3>Estoque atual</h3>";
        if (empty($this->itens)) {
            echo "Estoque vazio.<br>";
            return;
        }
        foreach ($this->itens as $nome => $qtd) {
            echo "- {$nome}: {$qtd} unidade(s)<br>";
        }
    }
}
<?php

require_once 'Pessoa.php';
require_once 'Estoque.php';
require_once 'Bebida.php';

class Repositor extends Pessoa {
    private string $setor;

    public function __construct(string $nome, string $cpf, int $idade, string $setor) {
        parent::__construct($nome, $cpf, $idade);

        if (!$this->isMaiorDeIdade()) {
            throw new RuntimeException("Repositor precisa ser maior de idade.");
        }
        $this->setor = $setor;
    }

    public function getSetor(): string { return $this->setor; }

    public function repor(Estoque $estoque, Bebida $bebida, int $quantidade): void {
        $estoque->adicionar($bebida, $quantidade);
        echo "✅ {$this->nome} repôs {$quantidade}x '{$bebida->getNome()}' no setor {$this->setor}.<br>";
    }

    public function exibirDados(): void {
        echo "<h3>📦 REPOSITOR</h3>";
        parent::exibirDados();
        echo "Setor: {$this->setor}<br>";
    }
}
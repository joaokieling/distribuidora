<?php

require_once 'Pessoa.php';

class Vendedor extends Pessoa {
    private string $matricula;
    private float  $comissao;

    public function __construct(string $nome, string $cpf, int $idade, string $matricula, float $comissao = 5.0) {
        parent::__construct($nome, $cpf, $idade);

        if (!$this->isMaiorDeIdade()) {
            throw new RuntimeException("Vendedor precisa ser maior de idade.");
        }
        $this->matricula = $matricula;
        $this->comissao  = $comissao;
    }

    public function getMatricula(): string { return $this->matricula; }
    public function getComissao(): float   { return $this->comissao; }

    public function calcularComissao(float $valorVenda): float {
        return $valorVenda * ($this->comissao / 100);
    }

    public function exibirDados(): void {
        echo "<h3>VENDEDOR</h3>";
        parent::exibirDados();
        echo "Matrícula: {$this->matricula}<br>";
        echo "Comissão: {$this->comissao}%<br>";
    }
}
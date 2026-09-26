<?php

require_once 'Pessoa.php';
require_once 'Bebida.php';

class Comprador extends Pessoa {

    public function __construct(string $nome, string $cpf, int $idade) {
        parent::__construct($nome, $cpf, $idade);
    }

    /**
     * Verifica se o comprador PODE comprar a bebida.
     * Regra: bebida alcoólica exige 18+.
     */
    public function podeComprar(Bebida $bebida): bool {
        if ($bebida->isAlcoolica() && !$this->isMaiorDeIdade()) {
            return false;
        }
        return true;
    }

    public function exibirDados(): void {
        echo "<h3>🧑 COMPRADOR</h3>";
        parent::exibirDados();
    }
}
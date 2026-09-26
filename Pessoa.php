<?php

class Pessoa {
    protected string $nome;
    protected string $cpf;
    protected int $idade;

    public function __construct(string $nome, string $cpf, int $idade) {
        if ($idade < 0 || $idade > 120) {
            throw new InvalidArgumentException("Idade inválida: $idade");
        }
        $this->nome  = $nome;
        $this->cpf   = $cpf;
        $this->idade = $idade;
    }

    public function getNome(): string { return $this->nome; }
    public function getCpf(): string  { return $this->cpf; }
    public function getIdade(): int   { return $this->idade; }

    public function isMaiorDeIdade(): bool {
        return $this->idade >= 18;
    }

    public function exibirDados(): void {
        echo "Nome: {$this->nome}<br>";
        echo "CPF: {$this->cpf}<br>";
        echo "Idade: {$this->idade} anos<br>";
        echo "Maior de idade: " . ($this->isMaiorDeIdade() ? "Sim" : "Não") . "<br>";
    }
}
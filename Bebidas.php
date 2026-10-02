<?php

class Bebida {
    private string $nome;
    private float  $preco;
    private float  $teorAlcoolico; // %
    private int    $volumeMl;

    public function __construct(string $nome, float $preco, float $teorAlcoolico, int $volumeMl) {
        if ($preco < 0)          throw new InvalidArgumentException("Preço inválido.");
        if ($teorAlcoolico < 0)  throw new InvalidArgumentException("Teor alcoólico inválido.");
        if ($volumeMl <= 0)      throw new InvalidArgumentException("Volume inválido.");

        $this->nome          = $nome;
        $this->preco         = $preco;
        $this->teorAlcoolico = $teorAlcoolico;
        $this->volumeMl      = $volumeMl;
    }

    public function getNome(): string          { return $this->nome; }
    public function getPreco(): float          { return $this->preco; }
    public function getTeorAlcoolico(): float  { return $this->teorAlcoolico; }
    public function getVolumeMl(): int         { return $this->volumeMl; }

    /** Bebida alcoólica? (qualquer teor > 0) */
    public function isAlcoolica(): bool {
        return $this->teorAlcoolico > 0;
    }

    public function exibirDados(): void {
        echo "🍺 {$this->nome} ({$this->volumeMl}ml) — R$ " .
             number_format($this->preco, 2, ',', '.') .
             " — Teor: {$this->teorAlcoolico}%";
        if ($this->isAlcoolica()) {
            echo " <strong>[+18]</strong>";
        }
        echo "<br>";
    }
}

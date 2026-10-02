<?php

/**
 * Categorias de bebida — SEM enum, apenas constantes de classe.
 */
class Categoria {
    // ---------- CATEGORIAS ----------
    const CERVEJA      = 'Cerveja';
    const VINHO        = 'Vinho';
    const ESPUMANTE    = 'Espumante';
    const DESTILADO    = 'Destilado';
    const LICOR        = 'Licor';
    const WHISKY       = 'Whisky';
    const CACHAÇA      = 'Cachaça';
    const SAKE         = 'Saquê';
    const SEM_ALCOOL   = 'Sem Álcool';
    const ENERGETICO   = 'Energético';
    const SUCOS        = 'Suco';
    const AGUA         = 'Água';
    const ISOTONICO    = 'Isotônico';
    const REFRIGERANTE = 'Refrigerante';

    // ---------- REGRAS GERAIS ----------
    const TEOR_MINIMO_ALCOOLICO = 0.5;
    const IDADE_MINIMA_LEGAL    = 18;

    // ---------- LISTA DE CATEGORIAS SEM ÁLCOOL ----------
    const CATEGORIAS_SEM_ALCOOL = [
        self::SEM_ALCOOL,
        self::ENERGETICO,
        self::SUCOS,
        self::AGUA,
        self::ISOTONICO,
        self::REFRIGERANTE,
    ];

    // ---------- MÉTODOS ESTÁTICOS ----------
    public static function rotulo(string $categoria): string {
        switch ($categoria) {
            case self::CERVEJA:      return '🍺 Cerveja';
            case self::VINHO:        return '🍷 Vinho';
            case self::ESPUMANTE:    return '🥂 Espumante';
            case self::DESTILADO:    return '🥃 Destilado';
            case self::LICOR:        return '🍸 Licor';
            case self::WHISKY:       return '🥃 Whisky';
            case self::CACHAÇA:      return '🍶 Cachaça';
            case self::SAKE:         return '🍶 Saquê';
            case self::SEM_ALCOOL:   return '🚫 Sem Álcool';
            case self::ENERGETICO:   return '⚡ Energético';
            case self::SUCOS:        return '🧃 Suco';
            case self::AGUA:         return '💧 Água';
            case self::ISOTONICO:    return '💦 Isotônico';
            case self::REFRIGERANTE: return '🥤 Refrigerante';
            default:                 return "❓ {$categoria}";
        }
    }

    public static function exigeMaioridade(string $categoria): bool {
        return !in_array($categoria, self::CATEGORIAS_SEM_ALCOOL, true);
    }
}
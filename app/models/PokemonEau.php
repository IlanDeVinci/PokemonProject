<?php

class PokemonEau extends Pokemon {
    protected string $specialAttackName = 'Hydrocanon';

    // Constructor slightly modifies the base stats of the Pokémon for its type
    public function __construct(string $nom, int $pointsDeVie, int $puissanceAttaque, int $defense) {
        parent::__construct($nom, 'Eau', $pointsDeVie, $puissanceAttaque, $defense);
        $this->defense = (int)($this->defense * 1.1);
        $this->puissanceAttaque = (int)($this->puissanceAttaque * 1.1);
        $this->pointsDeVie = (int)($this->pointsDeVie * 1.2);
        $this->pointsDeVieRestants = $this->pointsDeVie;

    }

    // Special attack method for the Pokémon
    public function capaciteSpeciale(Pokemon $adversaire): array {
        $specialAttack = new Attack($this->specialAttackName, 120, 95);
        $effectiveness = $this->isSuperEffectiveAgainst($adversaire);
        return $this->attack($adversaire, $specialAttack, $effectiveness);
    }
}
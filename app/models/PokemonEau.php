<?php

class PokemonEau extends Pokemon {
    protected string $specialAttackName = 'Hydrocanon';

    public function __construct(string $nom, int $pointsDeVie, int $puissanceAttaque, int $defense) {
        parent::__construct($nom, 'Eau', $pointsDeVie, $puissanceAttaque, $defense);
        $this->defense = (int)($this->defense * 1.1);
        $this->puissanceAttaque = (int)($this->puissanceAttaque * 1.1);
        $this->pointsDeVie = (int)($this->pointsDeVie * 1.2);
        $this->pointsDeVieRestants = $this->pointsDeVie;

    }

    public function capaciteSpeciale(Pokemon $adversaire): array {
        $specialAttack = new Attack($this->specialAttackName, 55, 95); // Example values
        $effectiveness = $this->isSuperEffectiveAgainst($adversaire);
        return $this->attack($adversaire, $specialAttack, $effectiveness);
    }
}
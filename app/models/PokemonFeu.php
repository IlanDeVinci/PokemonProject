<?php

class PokemonFeu extends Pokemon {
    protected string $specialAttackName = 'Lance-flammes';

    public function __construct(string $nom, int $pointsDeVie, int $puissanceAttaque, int $defense) {
        parent::__construct($nom, 'Feu', $pointsDeVie, $puissanceAttaque, $defense);
        $this->defense = (int)($this->defense * 0.9);
        $this->puissanceAttaque = (int)($this->puissanceAttaque * 1.3);
        $this->pointsDeVie = (int)($this->pointsDeVie * 1.0);
        $this->pointsDeVieRestants = $this->pointsDeVie;
    }

    public function capaciteSpeciale(Pokemon $adversaire): array {
        $specialAttack = new Attack($this->specialAttackName, 60, 90); // Example values
        $effectiveness = $this->isSuperEffectiveAgainst($adversaire);
        return $this->attack($adversaire, $specialAttack, $effectiveness);
    }
}
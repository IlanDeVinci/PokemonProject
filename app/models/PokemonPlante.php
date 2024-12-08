<?php

class PokemonPlante extends Pokemon {
    protected string $specialAttackName = 'Feuille magique';

    public function __construct(string $nom, int $pointsDeVie, int $puissanceAttaque, int $defense) {
        parent::__construct($nom, 'Plante', $pointsDeVie, $puissanceAttaque, $defense);
        $this->defense = (int)($this->defense * 1.2);         // 20% more defense
        $this->puissanceAttaque = (int)($this->puissanceAttaque * 1.0); // normal attack
        $this->pointsDeVie = (int)($this->pointsDeVie * 1.1);     // 10% more HP
                $this->pointsDeVieRestants = $this->pointsDeVie;

    }

    public function capaciteSpeciale(Pokemon $adversaire): array {
        $specialAttack = new Attack($this->specialAttackName, 50, 100); // Example values
        $effectiveness = $this->isSuperEffectiveAgainst($adversaire);
        return $this->attack($adversaire, $specialAttack, $effectiveness);
    }
}
<?php

class PokemonPlante extends Pokemon {
    protected string $specialAttackName = 'Feuille magique';

    // Constructor slightly modifies the base stats of the Pokémon for its type
    public function __construct(string $nom, int $pointsDeVie, int $puissanceAttaque, int $defense) {
        parent::__construct($nom, 'Plante', $pointsDeVie, $puissanceAttaque, $defense);
        $this->defense = (int)($this->defense * 1.2);         // 20% more defense
        $this->puissanceAttaque = (int)($this->puissanceAttaque * 1.1); // normal attack
        $this->pointsDeVie = (int)($this->pointsDeVie * 1.1);     // 10% more HP
                $this->pointsDeVieRestants = $this->pointsDeVie;

    }
    // Special attack method for the Pokémon
    public function capaciteSpeciale(Pokemon $adversaire): array {
        $specialAttack = new Attack($this->specialAttackName, 105, 100); // Example values
        $effectiveness = $this->isSuperEffectiveAgainst($adversaire);
        return $this->attack($adversaire, $specialAttack, $effectiveness);
    }
}
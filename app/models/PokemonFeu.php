<?php

class PokemonFeu extends Pokemon {
    protected string $specialAttackName = 'Lance-flammes';

    // Constructor slightly modifies the base stats of the Pokémon for its type
    public function __construct(string $nom, int $pointsDeVie, int $puissanceAttaque, int $defense) {
        parent::__construct($nom, 'Feu', $pointsDeVie, $puissanceAttaque, $defense);
        $this->defense = (int)($this->defense * 0.9);
        $this->puissanceAttaque = (int)($this->puissanceAttaque * 1.4);
        $this->pointsDeVie = (int)($this->pointsDeVie * 1.1);
        $this->pointsDeVieRestants = $this->pointsDeVie;
    }

    // Special attack method for the Pokémon
    public function capaciteSpeciale(Pokemon $adversaire): array {
        $specialAttack = new Attack($this->specialAttackName, 135, 90); // Example values
        $effectiveness = $this->isSuperEffectiveAgainst($adversaire);
        return $this->attack($adversaire, $specialAttack, $effectiveness);
    }
}
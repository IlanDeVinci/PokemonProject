<?php

abstract class Pokemon implements Combattant {
    use Soins;
    
    protected string $nom;
    protected string $type;
    protected int $pointsDeVieRestants;
    protected int $pointsDeVie;
    protected int $puissanceAttaque;
    protected int $defense;
    protected array $attacks = [];
    protected string $specialAttackName;

    public function __construct(string $nom, string $type, int $pointsDeVie, int $puissanceAttaque, int $defense) {
        $this->nom = $nom;
        $this->type = $type;
        $this->pointsDeVie = $pointsDeVie;
        $this->pointsDeVieRestants = $pointsDeVie;
        $this->puissanceAttaque = $puissanceAttaque;
        $this->defense = $defense;
    }

    abstract public function capaciteSpeciale(Pokemon $adversaire): array;

    public function seBattre($adversaire) {
        $chosenAttack = $this->chooseAttack();
        return $this->attack($adversaire, $chosenAttack);
    }

    public function utiliserAttaqueSpeciale($adversaire) {
        return $this->capaciteSpeciale($adversaire);
    }

    public function attack(Pokemon $adversaire, Attack $attack, float $effectiveness = 1.0): array {
        return $attack->executerAttaque($this, $adversaire, $effectiveness);
    }

    public function chooseAttack(): Attack {
        return $this->attacks[array_rand($this->attacks)];
    }

    public function recevoirDegats(int $degats): void {
        $this->pointsDeVieRestants -= $degats;
    }

    public function estKO(): bool {
        return $this->pointsDeVieRestants <= 0;
    }

    public function getPointsDeVieMax(): int {
        return $this->pointsDeVie;
    }

    public function getPointsDeVieRestants(): int {
        return $this->pointsDeVieRestants;
    }

    public function setPointsDeVieRestants(int $pointsDeVieRestants): void {
        $this->pointsDeVieRestants = $pointsDeVieRestants;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getAttaque(): int {
        return $this->puissanceAttaque;
    }

    public function getDefense(): int {
        return $this->defense;
    }

    public function setAttacks(array $attacks): void {
        $this->attacks = $attacks;
    }

    public function getAttacks(): array {
        return $this->attacks;
    }

    public function getSpecialAttackName(): string {
        return $this->specialAttackName;
    }

    public function isSuperEffectiveAgainst(Pokemon $adversaire): float {
        if ($this instanceof PokemonFeu && $adversaire instanceof PokemonPlante) return 1.5;
        if ($this instanceof PokemonEau && $adversaire instanceof PokemonFeu) return 1.5;
        if ($this instanceof PokemonPlante && $adversaire instanceof PokemonEau) return 1.5;
        if ($this instanceof PokemonFeu && $adversaire instanceof PokemonEau) return 0.5;
        if ($this instanceof PokemonEau && $adversaire instanceof PokemonPlante) return 0.5;
        if ($this instanceof PokemonPlante && $adversaire instanceof PokemonFeu) return 0.5;
        return 1.0;
    }

    public function soigner(): void {
        $this->pointsDeVieRestants = $this->getPointsDeVieMax();
        // Reset any status effects if applicable
        // ...existing code...
    }
    
}
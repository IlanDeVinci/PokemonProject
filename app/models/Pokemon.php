<?php

// Base class for all Pokemon types
abstract class Pokemon implements Combattant {
    use Soins;
    
    protected string $nom;
    protected string $type;
    protected string $pointsDeVieRestants;
    protected int $pointsDeVie;
    protected int $puissanceAttaque;
    protected int $defense;
    protected array $attacks = [];
    protected string $specialAttackName;

    // Initialize a new Pokemon with basic stats
    public function __construct(string $nom, string $type, int $pointsDeVie, int $puissanceAttaque, int $defense) {
        $this->nom = $nom;
        $this->type = $type;
        $this->pointsDeVie = $pointsDeVie;
        $this->pointsDeVieRestants = $pointsDeVie;
        $this->puissanceAttaque = $puissanceAttaque;
        $this->defense = $defense;
    }

    // Special ability implementation for each Pokemon type
    abstract public function capaciteSpeciale(Pokemon $adversaire): array;

    // Handle battle turn
    public function seBattre($adversaire) {
        $chosenAttack = $this->chooseAttack();
        return $this->attack($adversaire, $chosenAttack);
    }

    // Execute special attack
    public function utiliserAttaqueSpeciale($adversaire) {
        return $this->capaciteSpeciale($adversaire);
    }

    // Perform attack on opponent
    public function attack(Pokemon $adversaire, Attack $attack, float $effectiveness = 1.0): array {
        return $attack->executerAttaque($this, $adversaire, $effectiveness);
    }

    // Select random attack from available attacks
    public function chooseAttack(): Attack {
        return $this->attacks[array_rand($this->attacks)];
    }

    // Process damage received
    public function recevoirDegats(int $degats): void {
        $this->pointsDeVieRestants -= $degats;
    }

    // Check if Pokemon is knocked out
    public function estKO(): bool {
        return $this->pointsDeVieRestants <= 0;
    }

    // Getters and setters
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

    // Calculate type effectiveness multiplier
    public function isSuperEffectiveAgainst(Pokemon $adversaire): float {
        if ($this instanceof PokemonFeu && $adversaire instanceof PokemonPlante) return 2;
        if ($this instanceof PokemonEau && $adversaire instanceof PokemonFeu) return 2;
        if ($this instanceof PokemonPlante && $adversaire instanceof PokemonEau) return 2;
        if ($this instanceof PokemonFeu && $adversaire instanceof PokemonEau) return 0.5;
        if ($this instanceof PokemonEau && $adversaire instanceof PokemonPlante) return 0.5;
        if ($this instanceof PokemonPlante && $adversaire instanceof PokemonFeu) return 0.5;
        return 1.0;
    }
    
}
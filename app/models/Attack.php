<?php

class Attack {
    private string $nom;
    private int $puissance;
    private float $precision;

    public function __construct(string $nom, int $puissance, float $precision) {
        $this->nom = $nom;
        $this->puissance = $puissance;
        $this->precision = $precision;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getPuissance(): int {
        return $this->puissance;
    }

    public function getPrecision(): float {
        return $this->precision;
    }

    // Method to execute an attack and calculate damage dealt to the opponent, then call recevoirDegats() on the opponent to apply the damage
    public function executerAttaque(Pokemon $attaquant, Pokemon $adversaire, float $effectiveness = 1.0): array {

        // Check if the attack hits the opponent
        if ($this->attackHits()) {
            $defense = max(10, $adversaire->getDefense());
            $attack = max(10, $attaquant->getAttaque());

            // Calculate damage dealt to the opponent
            $degats = floor(((5*$this->puissance*$attack/$defense)/50 + 2) * $effectiveness);
            $degats = (int)($degats * rand(85, 100) / 100);
            $degats = max(2, $degats);
            $isCritical = false;
            // Add critical hit chance (6.25% chance)
            if (rand(1, 16) === 1) {
                $degats = floor($degats * 1.5);
                $isCritical = true;
            }

            // Call recevoirDegats() on the opponent to apply the damage
            $adversaire->recevoirDegats($degats);

            // Return an array with the attack used, damage dealt, hit status, and effectiveness
            return [
                'attaqueUtilisee' => $this->nom,
                'degats' => $degats,
                'touche' => true,
                'efficacite' => $effectiveness,
                'critique' => $isCritical
            ];
        } else {
            return [
                'attaqueUtilisee' => $this->nom,
                'degats' => 0,
                'touche' => false,
                'efficacite' => $effectiveness,
                'critique' => false
            ];
        }
    }

    // Method to check if the attack hits the opponent based on the attack's precision
    private function attackHits(): bool {
        $rand = rand(0, 100);
        return $rand <= $this->precision;
    }
}
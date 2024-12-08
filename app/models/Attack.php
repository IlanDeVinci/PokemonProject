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

    public function executerAttaque(Pokemon $attaquant, Pokemon $adversaire, float $effectiveness = 1.0): array {
        if ($this->attaqueTouches()) {
            $defense = max(1, $adversaire->getDefense());
            
            $degats = (int) floor(($attaquant->getAttaque() * 0.1 * $this->puissance * $effectiveness) / $defense);
            $degats = max(1, $degats);
            $degats = (int)($degats * rand(85, 100) / 100);
            $adversaire->recevoirDegats($degats);
            return [
                'attaqueUtilisee' => $this->nom,
                'degats' => $degats,
                'touche' => true,
                'efficacite' => $effectiveness,
                'message' => ''
            ];
        } else {
            return [
                'attaqueUtilisee' => $this->nom,
                'degats' => 0,
                'touche' => false,
                'efficacite' => $effectiveness,
                'message' => 'L\'attaque a manqué!'
            ];
        }
    }

    private function attaqueTouches(): bool {
        $rand = rand(0, 100);
        return $rand <= $this->precision;
    }
}
<?php

class Combat {
    private Pokemon $pokemon1;
    private Pokemon $pokemon2;

    public function __construct(Pokemon $pokemon1, Pokemon $pokemon2) {
        $this->pokemon1 = $pokemon1;
        $this->pokemon2 = $pokemon2;
    }

    public function demarrerCombat(?int $healPokemonId): array {

        $attaquant = $this->pokemon1;
        $defenseur = $this->pokemon2;
        $tour = 1;
        $battleLog = [];

        // Heal the specified Pokémon using soigner()
        if ($healPokemonId === 1) {
            $this->pokemon1->soigner();
        } elseif ($healPokemonId === 2) {
            $this->pokemon2->soigner();
        }

        // Do not heal the other Pokémon; retain current health

        // Add initial stats to battle log
        $battleLog[] = ["initialStats" => [
            "pokemon1" => [
                "nom" => $this->pokemon1->getNom(),
                "pvMax" => $this->pokemon1->getPointsDeVieMax(),
                "attaque" => $this->pokemon1->getAttaque(),
                "type" => $this->pokemon1->getType()
            ],
            "pokemon2" => [
                "nom" => $this->pokemon2->getNom(),
                "pvMax" => $this->pokemon2->getPointsDeVieMax(),
                "attaque" => $this->pokemon2->getAttaque(),
                "type" => $this->pokemon2->getType()
            ]
        ]];

        while ($this->pokemon1->getPointsDeVieRestants() > 0 && $this->pokemon2->getPointsDeVieRestants() > 0) {
            $turnData = [
                "tour" => $tour,
                "action" => $this->tourDeCombat($attaquant, $defenseur),
                "statut" => $this->getStatut()
            ];

            // Check for victory after the action
            if ($this->pokemon1->getPointsDeVieRestants() <= 0 || $this->pokemon2->getPointsDeVieRestants() <= 0) {
                $turnData["fin"] = $this->getVainqueur();
            }

            $battleLog[] = ["turn" => $turnData];

            if (isset($turnData["fin"])) {
                break;
            }
            
            $temp = $attaquant;
            $attaquant = $defenseur;
            $defenseur = $temp;
              
            $tour++;
        }
     
        return $battleLog;
    }

    private function tourDeCombat(Pokemon $attaquant, Pokemon $defenseur): array {
        $attaquantId = ($attaquant === $this->pokemon1) ? 1 : 2;

        if (rand(1, 100) <= 30) { // 30% chance to use special attack
            $attackResult = $attaquant->utiliserAttaqueSpeciale($defenseur);
        } else {
            $attackResult = $attaquant->seBattre($defenseur);
        }

        $message = ["action" => [
            "id" => $attaquantId,
            "attaquant" => $attaquant->getNom(),
            "defenseur" => $defenseur->getNom(),
            "attackUsed" => $attackResult['attaqueUtilisee'],
            "degats" => $attackResult['degats'],
            "touche" => $attackResult['touche'],
            "efficacite" => $attackResult['efficacite'],
            "message" => $attackResult['message']
        ]];

        // Prevent HP from going below 0
        $defenseur->setPointsDeVieRestants(max(0, $defenseur->getPointsDeVieRestants()));
        return $message;
    }

    private function getStatut(): array {
        $statut =  ["statut" => [
            "pokemon1" => [
                "nom" => $this->pokemon1->getNom(),
                "pv" => $this->pokemon1->getPointsDeVieRestants(),
                "pvMax" => $this->pokemon1->getPointsDeVieMax() // New line
            ],
            "pokemon2" => [
                "nom" => $this->pokemon2->getNom(),
                "pv" => $this->pokemon2->getPointsDeVieRestants(),
                "pvMax" => $this->pokemon2->getPointsDeVieMax() // New line
            ]
        ]];
        
        return $statut;
    }

    private function getVainqueur(): array {
        $vainqueur = $this->pokemon1->getPointsDeVieRestants() > 0 ? $this->pokemon1 : $this->pokemon2;
        return ["fin" => [
            "vainqueur" => $vainqueur->getNom(),
            "pvRestants" => $vainqueur->getPointsDeVieRestants()
        ]];
    }
}
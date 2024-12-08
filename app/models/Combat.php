<?php

class Combat {
    private Pokemon $pokemon1;
    private Pokemon $pokemon2;

    public function __construct(Pokemon $pokemon1, Pokemon $pokemon2) {
        $this->pokemon1 = $pokemon1;
        $this->pokemon2 = $pokemon2;
    }

    // Start the combat with the specified Pokémon ID to heal
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

        // While both Pokémon are still alive, continue the battle
        while ($this->pokemon1->getPointsDeVieRestants() > 0 && $this->pokemon2->getPointsDeVieRestants() > 0) {

            // Keep track of the battle log for each turn
            $turnData = [
                "tour" => $tour,
                "action" => $this->tourDeCombat($attaquant, $defenseur),
                "statut" => $this->getStatus()
            ];

            // Check for victory after the action
            if ($this->pokemon1->getPointsDeVieRestants() <= 0 || $this->pokemon2->getPointsDeVieRestants() <= 0) {
                $turnData["fin"] = $this->getVainqueur();
            }

            // Add the turn data to the battle log
            $battleLog[] = ["turn" => $turnData];

            // If the battle is over, break out of the loop
            if (isset($turnData["fin"])) {
                break;
            }
            
            // Switch the attacker and defender for the next turn
            $temp = $attaquant;
            $attaquant = $defenseur;
            $defenseur = $temp;
              
            $tour++;
        }
     
        return $battleLog;
    }

    // Simulate a turn of combat between two Pokémon
    private function tourDeCombat(Pokemon $attaquant, Pokemon $defenseur): array {

        // Determine the attacker ID (1 or 2)
        $attaquantId = ($attaquant === $this->pokemon1) ? 1 : 2;

        if (rand(1, 100) <= 30) { // 30% chance to use special attack
            $attackResult = $attaquant->utiliserAttaqueSpeciale($defenseur);
        } else { // 70% chance to use regular attack
            $attackResult = $attaquant->seBattre($defenseur);
        }

        // Add the action data to the message
        $message = ["action" => [
            "id" => $attaquantId,
            "attaquant" => $attaquant->getNom(),
            "defenseur" => $defenseur->getNom(),
            "attackUsed" => $attackResult['attaqueUtilisee'],
            "degats" => $attackResult['degats'],
            "touche" => $attackResult['touche'],
            "efficacite" => $attackResult['efficacite'],
            "critique" => $attackResult['critique']
        ]];

        // Prevent HP from going below 0
        $defenseur->setPointsDeVieRestants(max(0, $defenseur->getPointsDeVieRestants()));
        return $message;
    }

    // Get the current status of both Pokémon
    private function getStatus(): array {
        $status =  ["statut" => [
            "pokemon1" => [
                "nom" => $this->pokemon1->getNom(),
                "pv" => $this->pokemon1->getPointsDeVieRestants(),
                "pvMax" => $this->pokemon1->getPointsDeVieMax()
            ],
            "pokemon2" => [
                "nom" => $this->pokemon2->getNom(),
                "pv" => $this->pokemon2->getPointsDeVieRestants(),
                "pvMax" => $this->pokemon2->getPointsDeVieMax()
            ]
        ]];
        
        return $status;
    }

    // Get the winner of the battle
    private function getVainqueur(): array {
        $vainqueur = $this->pokemon1->getPointsDeVieRestants() > 0 ? $this->pokemon1 : $this->pokemon2;
        return ["fin" => [
            "vainqueur" => $vainqueur->getNom(),
            "pvRestants" => $vainqueur->getPointsDeVieRestants()
        ]];
    }
}
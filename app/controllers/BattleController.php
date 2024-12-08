<?php
class BattleController {
    use Render;
    private $pokemonModel;

    public function __construct() {
        $this->pokemonModel = new PokemonModel();
    }
    // index method to display the battle form
    public function index() {
        $pokemons = $this->pokemonModel->findAll();
        if (empty($pokemons)) {
            $error = "No Pokémon found in the database. Please add some Pokémon first.";
            $this->renderView('battle_form', ['error' => $error, 'pokemons' => $pokemons]);
            return;
        }
        // Checks if the request method is POST, if it is, it checks if the pokemon1 and pokemon2 fields are set
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['pokemon1']) || !isset($_POST['pokemon2'])) {
                $error = "Please select two Pokémon to battle.";
            } else {
                // Checks if the selected Pokémon exist in the database
                $pokemon1Data = $this->pokemonModel->findById($_POST['pokemon1']);
                $pokemon2Data = $this->pokemonModel->findById($_POST['pokemon2']);

                if (!$pokemon1Data || !$pokemon2Data) {
                    $error = "One or both selected Pokémon could not be found.";
                } else {
                    // Creates instances of the selected Pokémon
                    $pokemon1 = $this->createPokemonInstance($pokemon1Data);
                    $pokemon2 = $this->createPokemonInstance($pokemon2Data);

                    $attackModel = new AttackModel();
                    // Retrieves the attacks for the selected Pokémon
                    $attacksData1 = $attackModel->getAttacksForPokemon($pokemon1Data['name']);
                    $attacks1 = [];
                    foreach ($attacksData1 as $attackData) {
                        $attacks1[] = new Attack($attackData['name'], $attackData['power'], $attackData['accuracy']);
                    }
                    // If the Pokémon has no attacks, it adds a default attack
                    if (empty($attacks1)) {
                        $attacks1[] = new Attack('Struggle', 20, 90);
                    }
                    // Sets the attacks for the Pokémon
                    $pokemon1->setAttacks($attacks1);
                    // Same as above for the second Pokémon
                    $attacksData2 = $attackModel->getAttacksForPokemon($pokemon2Data['name']);
                    $attacks2 = [];
                    foreach ($attacksData2 as $attackData) {
                        $attacks2[] = new Attack($attackData['name'], $attackData['power'], $attackData['accuracy']);
                    }
                    if (empty($attacks2)) {
                        $attacks2[] = new Attack('Struggle', 20, 90);
                    }
                    $pokemon2->setAttacks($attacks2);

                    session_start();
                    // Saves the Pokémon instances in the session
                    $_SESSION['pokemon1'] = $pokemon1;
                    $_SESSION['pokemon2'] = $pokemon2;

                    // Starts a new combat with the selected Pokémon
                    $combat = new Combat($pokemon1, $pokemon2);
                
                    // Starts combat with null as the fainted Pokémon ID so both Pokémon start with full health
                    $battleLog = $combat->demarrerCombat(null);

                    // After the fight, calls the renderView method to display the battle log
                    $this->renderView('battle_display', ['battleLog' => $battleLog]);
                    return;
                }
            }
            if (isset($error)) {
                $this->renderView('battle_form', ['error' => $error, 'pokemons' => $pokemons]);
            }
            return;
        }
        // If the request method is not POST, it calls the renderView method to display the battle form
        $this->renderView('battle_form', ['pokemons' => $pokemons]);
    }

    // Restart method to restart the battle with the same Pokémon instances
    public function restart() {
        // Get the fainted Pokémon ID from the AJAX request
        $data = json_decode(file_get_contents('php://input'), true);
        $faintedPokemonId = $data['faintedPokemon'] ?? null;

        // Retrieve the Pokémon instances from session
        session_start();
        $pokemon1 = $_SESSION['pokemon1'];
        $pokemon2 = $_SESSION['pokemon2'];

        // Start a new combat using the same Pokémon instances
        $combat = new Combat($pokemon1, $pokemon2);
        $battleLog = $combat->demarrerCombat($faintedPokemonId);

        // Return the new battle log as JSON
        header('Content-Type: application/json');
        echo json_encode($battleLog);
    }

    // Method to create a Pokémon instance based on the Pokémon data
    
    private function createPokemonInstance(array $data): Pokemon {
        $nom = $data['name'];
        $pointsDeVie = $data['health'];
        $puissanceAttaque = $data['attack'];
        $defense = $data['defense'];

        switch ($data['type']) {
            case 'Feu':
                return new PokemonFeu($nom, $pointsDeVie, $puissanceAttaque, $defense);
            case 'Eau':
                return new PokemonEau($nom, $pointsDeVie, $puissanceAttaque, $defense);
            case 'Plante':
                return new PokemonPlante($nom, $pointsDeVie, $puissanceAttaque, $defense);
            default:
                throw new Exception('Unknown Pokémon type');
        }
    }
}
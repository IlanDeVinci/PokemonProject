<?php
class BattleController {
    use Render;
    private $pokemonModel;

    public function __construct() {
        $this->pokemonModel = new PokemonModel();
    }

    public function index() {
        $pokemons = $this->pokemonModel->findAll();
        if (empty($pokemons)) {
            $error = "No Pokémon found in the database. Please add some Pokémon first.";
            $this->renderView('battle_form', ['error' => $error, 'pokemons' => $pokemons]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['pokemon1']) || !isset($_POST['pokemon2'])) {
                $error = "Please select two Pokémon to battle.";
            } else {
                $pokemon1Data = $this->pokemonModel->findById($_POST['pokemon1']);
                $pokemon2Data = $this->pokemonModel->findById($_POST['pokemon2']);

                if (!$pokemon1Data || !$pokemon2Data) {
                    $error = "One or both selected Pokémon could not be found.";
                } else {
                    $pokemon1 = $this->createPokemonInstance($pokemon1Data);
                    $pokemon2 = $this->createPokemonInstance($pokemon2Data);

                    $attackModel = new AttackModel();

                    $attacksData1 = $attackModel->getAttacksForPokemon($pokemon1Data['name']);
                    $attacks1 = [];
                    foreach ($attacksData1 as $attaqueData) {
                        $attacks1[] = new Attack($attaqueData['name'], $attaqueData['power'], $attaqueData['accuracy']);
                    }
                    if (empty($attacks1)) {
                        $attacks1[] = new Attack('Struggle', 20, 90);
                    }
                    $pokemon1->setAttacks($attacks1);

                    $attacksData2 = $attackModel->getAttacksForPokemon($pokemon2Data['name']);
                    $attacks2 = [];
                    foreach ($attacksData2 as $attaqueData) {
                        $attacks2[] = new Attack($attaqueData['name'], $attaqueData['power'], $attaqueData['accuracy']);
                    }
                    if (empty($attacks2)) {
                        $attacks2[] = new Attack('Struggle', 20, 90);
                    }
                    $pokemon2->setAttacks($attacks2);

                    // Store Pokémon instances in session
                    session_start();
                    $_SESSION['pokemon1'] = $pokemon1;
                    $_SESSION['pokemon2'] = $pokemon2;

                    $combat = new Combat($pokemon1, $pokemon2);
                    $battleLog = $combat->demarrerCombat(null);
                    $this->renderView('battle_display', ['battleLog' => $battleLog]);
                    return;
                }
            }
            if (isset($error)) {
                $this->renderView('battle_form', ['error' => $error, 'pokemons' => $pokemons]);
            }
            return;
        }

        $this->renderView('battle_form', ['pokemons' => $pokemons]);
    }

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
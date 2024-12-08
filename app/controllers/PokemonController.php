<?php
class PokemonController {
    use Render;
    private $model;

    public function __construct() {
        $this->model = new PokemonModel();
    }

    // index method to display all Pokémon 
    public function index() {
        $pokemons = $this->model->findAll();
        $this->renderView('pokemon_list', ['pokemons' => $pokemons]);
    }

    // view method to display a single Pokémon and its attacks
    public function view($id = 1) {
        $pokemon = $this->model->findById($id);
        if (!$pokemon) {
            header('Location: /pokemon');
            exit();
        }
        $attacks = $this->model->getAttacks($id);
        $type = $this->model->getType($id);
        $this->renderView('pokemon_view', ['pokemon' => $pokemon, 'attacks' => $attacks, 'type' => $type]);
    }

    // add method to add a new Pokémon to the database or display the form to add a Pokémon
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and cap values
            $name = substr(trim($_POST['name']), 0, 20);
            if (strlen($name) < 3) $name = str_pad($name, 3, 'A');
            
            $health = min(max((int)$_POST['health'], 40), 300);
            $attack = min(max((int)$_POST['attack'], 40), 150);
            $defense = min(max((int)$_POST['defense'], 10), 100);
            
            $data = [
                'name' => $name,
                'health' => $health,
                'attack' => $attack,
                'defense' => $defense,
                'type' => $_POST['type']
            ];
            $this->model->create($data);
            header('Location: /pokemon');
        } else {
            $this->renderView('add_pokemon');
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and cap values
            $name = substr(trim($_POST['name']), 0, 20);
            if (strlen($name) < 3) $name = str_pad($name, 3, 'A');
            
            $health = min(max((int)$_POST['health'], 40), 300);
            $attack = min(max((int)$_POST['attack'], 40), 150);
            $defense = min(max((int)$_POST['defense'], 10), 100);
            
            $data = [
                'name' => $name,
                'health' => $health,
                'attack' => $attack,
                'defense' => $defense,
                'type' => $_POST['type']
            ];
            $this->model->edit($id, $data);
            header('Location: /pokemon/view/' . $id);
        } else {
            $pokemon = $this->model->findById($id);
            if (!$pokemon) {
                header('Location: /pokemon');
                exit();
            }
            $this->renderView('edit_pokemon', ['pokemon' => $pokemon]);
        }
    }

    // delete method to delete a Pokémon
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->model->delete($id);
            header('Location: /pokemon');
            exit();
        }
    }

    // addAttack method to add an attack to a Pokémon
    public function addAttack($pokemonId = 1) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $attackId = $_POST['attack_id'];
            $currentAttacks = $this->model->getAttacks($pokemonId);

            // Check if Pokemon already has this attack
            foreach ($currentAttacks as $attack) {
                if ($attack['id'] == $attackId) {
                    header("Location: /pokemon/view/$pokemonId");
                    exit();
                }
            }
            // Check if Pokemon has maximum number of attacks
            if (count($currentAttacks) >= 4) {
                header("Location: /pokemon/view/$pokemonId");
                exit();
            }
            $this->model->addAttack($pokemonId, $attackId);
            header("Location: /pokemon/view/$pokemonId");
        } else {
            // Get attacks that are not already assigned to the Pokemon and display the form to add an attack
            $attackModel = new AttackModel();
            $existingAttacks = $this->model->getAttacks($pokemonId);
            $existingAttackIds = array_map(function($attack) {
            return $attack['id'];
            }, $existingAttacks);
            $attacks = array_filter($attackModel->findAll(), function($attack) use ($existingAttackIds) {
            return !in_array($attack['id'], $existingAttackIds);
            });
            $this->renderView('add_attack', ['attacks' => $attacks]);
        }
        }

        // removeAttack method to remove an attack from a Pokémon
    public function removeAttack($pokemonId = 1) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $attackId = $_POST['attack_id'];
            $this->model->removeAttack($pokemonId, $attackId);
            header("Location: /pokemon/view/$pokemonId");
        } else {
            $attacks = $this->model->getAttacks($pokemonId);
            $this->renderView('remove_attack', ['attacks' => $attacks]);
        }
    }
}
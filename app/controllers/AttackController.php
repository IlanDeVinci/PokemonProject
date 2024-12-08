<?php

class AttackController {
    use Render;
    private $model;

    public function __construct() {
        $this->model = new AttackModel();
    }

    // index method to display all attacks
    public function index() {
        $attacks = $this->model->findAll();
        $this->renderView('attack_list', ['attacks' => $attacks]);
    }

    // view method to display a single attack
    public function view($id) {
        $attack = $this->model->findById($id);
        if (!$attack) {
            header('Location: /attack');
            exit();
        }
        $this->renderView('attack_view', ['attack' => $attack]);
    }

    // add method to add a new attack to the database or display the form to add an attack
    // checks if the request method is POST, if it is, it sanitizes and caps the values
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $power = min(max((int)$_POST['power'], 10), 150);
            $accuracy = min(max((int)$_POST['accuracy'], 0), 100);

            if (strlen($name) > 20) {
            $name = substr($name, 0, 20);
            }

            $data = [
            'name' => $name,
            'power' => $power,
            'accuracy' => $accuracy
            ];
            $this->model->create($data);
            header('Location: /attack');
        } else {
            $this->renderView('create_attack');
        }
    }
    // delete method to delete an attack
    public function delete($id) {
        $this->model->delete($id);
        header('Location: /attack');
    }

    public function edit($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $power = min(max((int)$_POST['power'], 10), 150);
        $accuracy = min(max((int)$_POST['accuracy'], 0), 100);

        if (strlen($name) > 20) {
            $name = substr($name, 0, 20);
        }

        $data = [
            'name' => $name,
            'power' => $power,
            'accuracy' => $accuracy
        ];
        $this->model->edit($id, $data);
        header('Location: /attack/view/' . $id);
    } else {
        $attack = $this->model->findById($id);
        if (!$attack) {
            header('Location: /attack');
            exit();
        }
        $this->renderView('edit_attack', ['attack' => $attack]);    
    }
}
} 
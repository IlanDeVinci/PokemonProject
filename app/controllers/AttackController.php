<?php

class AttackController {
    use Render;
    private $model;

    public function __construct() {
        $this->model = new AttackModel();
    }

    public function index() {
        $attacks = $this->model->findAll();
        $this->renderView('attack_list', ['attacks' => $attacks]);
    }

    public function view($id) {
        $attack = $this->model->findById($id);
        $this->renderView('attack_view', ['attack' => $attack]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'power' => $_POST['power'],
                'accuracy' => $_POST['accuracy']
            ];
            $this->model->create($data);
            header('Location: /attack');
        } else {
            $this->renderView('create_attack');
        }
    }

    public function delete($id) {
        $this->model->delete($id);
        header('Location: /attacks');
    }
}
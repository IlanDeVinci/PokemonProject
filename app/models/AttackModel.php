<?php
class AttackModel extends Bdd {
    public function __construct() {
        parent::__construct();
    }

    // Retrieves all attacks from the database
    public function findAll(): array {
        $stmt = $this->co->prepare('SELECT * FROM attacks');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieves a specific attack by its ID
    public function findById(int $id): ?array {
        $stmt = $this->co->prepare('SELECT * FROM attacks WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

   // Adds a new attack to the database
    public function create(array $data): bool {
        $stmt = $this->co->prepare('INSERT INTO attacks (name, power, accuracy) 
            VALUES (:name, :power, :accuracy)');
        return $stmt->execute($data);
    }

    // Deletes an attack from the database

    public function delete(int $id): bool {
        try {
            $this->co->beginTransaction();
            
            // First delete related attacks from pokemon_attacks table
            $stmt = $this->co->prepare('DELETE FROM pokemon_attacks WHERE attack_id = :id');
            $stmt->execute(['id' => $id]);
            
            // Then delete the attack
            $stmt = $this->co->prepare('DELETE FROM attacks WHERE id = :id');
            $stmt->execute(['id' => $id]);
            
            $this->co->commit();
            return true;
        } catch (PDOException $e) {
            $this->co->rollBack();
            return false;
        }
    }
    // Edits an attack in the database
    public function edit(int $id, array $data): bool {
        $stmt = $this->co->prepare('UPDATE attacks SET name = :name, power = :power, accuracy = :accuracy WHERE id = :id');
        return $stmt->execute(array_merge($data, ['id' => $id]));
    }

    // Retrieves all attacks for a specific Pokémon
    public function getAttacksForPokemon(string $pokemonName): array {
        $stmt = $this->co->prepare('SELECT attacks.* FROM attacks
            INNER JOIN pokemon_attacks ON attacks.id = pokemon_attacks.attack_id
            INNER JOIN pokemons ON pokemon_attacks.pokemon_id = pokemons.id
            WHERE pokemons.name = :name');
        $stmt->execute(['name' => $pokemonName]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
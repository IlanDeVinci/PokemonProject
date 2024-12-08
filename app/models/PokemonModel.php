<?php
// Main Pokemon data model class
class PokemonModel extends Bdd {
    public function __construct() {
        parent::__construct();
    }
    
    // Get all pokemons from database
    public function findAll(): array {
        $stmt = $this->co->prepare('SELECT * FROM pokemons');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find a specific pokemon by ID
    public function findById(int $id): ?array {
        $stmt = $this->co->prepare('SELECT * FROM pokemons WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Create a new pokemon
    public function create(array $data): bool {
        var_dump($data);
        $stmt = $this->co->prepare('INSERT INTO pokemons (name, health, attack, defense, type) 
            VALUES (:name, :health, :attack, :defense, :type)');
        return $stmt->execute($data);
    }

    public function edit(int $id, array $data): bool {
        $stmt = $this->co->prepare('UPDATE pokemons SET name = :name, health = :health, attack = :attack, defense = :defense, type = :type WHERE id = :id');
        return $stmt->execute(array_merge($data, ['id' => $id]));
    }
    // Delete a pokemon and its attack relationships
    public function delete(int $id): bool {
        try {
            $this->co->beginTransaction();
            
            $stmt = $this->co->prepare('DELETE FROM pokemon_attacks WHERE pokemon_id = :id');
            $stmt->execute(['id' => $id]);
            
            $stmt = $this->co->prepare('DELETE FROM pokemons WHERE id = :id');
            $stmt->execute(['id' => $id]);
            
            $this->co->commit();
            return true;
        } catch (PDOException $e) {
            $this->co->rollBack();
            return false;
        }
    }

    // Get all attacks for a specific pokemon
    public function getAttacks(int $pokemonId): array {
        $stmt = $this->co->prepare('SELECT attacks.* FROM attacks
            INNER JOIN pokemon_attacks ON attacks.id = pokemon_attacks.attack_id
            WHERE pokemon_attacks.pokemon_id = :pokemon_id');
        $stmt->execute(['pokemon_id' => $pokemonId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get pokemon's type
    public function getType(int $pokemonId): array {
        $stmt = $this->co->prepare('SELECT type FROM pokemons WHERE id = :pokemon_id');
        $stmt->execute(['pokemon_id' => $pokemonId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add an attack to a pokemon
    public function addAttack(int $pokemonId, int $attackId): bool {
        $stmt = $this->co->prepare('INSERT INTO pokemon_attacks (pokemon_id, attack_id) 
            VALUES (:pokemon_id, :attack_id)');
        return $stmt->execute([
            'pokemon_id' => $pokemonId,
            'attack_id' => $attackId
        ]);
    }

    // Remove an attack from a pokemon
    public function removeAttack(int $pokemonId, int $attackId): bool {
        $stmt = $this->co->prepare('DELETE FROM pokemon_attacks WHERE pokemon_id = :pokemon_id AND attack_id = :attack_id');
        return $stmt->execute([
            'pokemon_id' => $pokemonId,
            'attack_id' => $attackId
        ]);
    }
}

    <h1 class="text-2xl font-bold mb-6">Select Pokémon to Battle</h1>
    <?php if (isset($error)): ?>
        <div class="error text-red-500 font-bold mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($pokemons)): ?>
    <form method="post" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Pokémon 1:</label>
            <select name="pokemon1" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
                <?php foreach ($pokemons as $pokemon): ?>
                    <option value="<?= $pokemon['id'] ?>"><?= htmlspecialchars($pokemon['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Pokémon 2:</label>
            <select name="pokemon2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
                <?php foreach ($pokemons as $pokemon): ?>
                    <option value="<?= $pokemon['id'] ?>"><?= htmlspecialchars($pokemon['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit"
                class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            Start Battle
        </button>
    </form>
    <?php endif; ?>

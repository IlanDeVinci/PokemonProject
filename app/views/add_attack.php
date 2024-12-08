
    <h1 class="text-2xl font-bold mb-6">Add Attack to Pokémon</h1>
    <form method="post" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Select Attack:</label>
            <select name="attack_id"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
                <?php foreach ($attacks as $attack): ?>
                    <option value="<?= $attack['id'] ?>"><?= htmlspecialchars($attack['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit"
                class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            Add Attack
        </button>
    </form>

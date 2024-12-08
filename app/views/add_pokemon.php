<h1 class="text-2xl font-bold mb-6">Add New Pokémon</h1>
<form method="post" class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-gray-700">Name:</label>
        <input type="text" name="name" required minlength="3" maxlength="30"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Health (40-300):</label>
        <input type="number" name="health" required min="40" max="300" step="5"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Attack (40-150):</label>
        <input type="number" name="attack" required min="40" max="150" step="5"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Defense (10-100):</label>
        <input type="number" name="defense" required min="10" max="100" step="5"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Type:</label>
        <select name="type" required
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
            <option value="Feu">Feu</option>
            <option value="Eau">Eau</option>
            <option value="Plante">Plante</option>
        </select>
    </div>
    <button type="submit"
            class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
        Add Pokémon
    </button>
</form>

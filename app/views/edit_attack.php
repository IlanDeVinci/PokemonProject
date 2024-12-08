<h1 class="text-2xl font-bold mb-6">Edit Attack</h1>
<form method="POST" action="/attack/edit/<?= htmlspecialchars($attack['id']) ?>" class="space-y-6">    
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Attack Name (3-20 chars):</label>
        <input type="text" id="name" name="name" required minlength="3" maxlength="20"
               value="<?= htmlspecialchars($attack['name']) ?>"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
    </div>

    <div>
        <label for="power" class="block text-sm font-medium text-gray-700">Power (10-150):</label>
        <input type="number" id="power" name="power" min="10" max="150" step="5" required
               value="<?= htmlspecialchars($attack['power']) ?>"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 mb-2">
    </div>

    <div>
        <label for="accuracy" class="block text-sm font-medium text-gray-700">Accuracy (10-100):</label>
        <input type="number" id="accuracy" name="accuracy" min="10" max="100" step="5" required
               value="<?= htmlspecialchars($attack['accuracy']) ?>"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 mb-2">
    </div>

    <button type="submit"
            class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
        Update Attack
    </button>
</form>

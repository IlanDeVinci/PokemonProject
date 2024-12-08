<div class="max-w-md mx-auto my-12 bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="p-8 text-wrap   ">
        <h1 class="text-4xl font-bold mb-4 text-center text-gray-800"><?= htmlspecialchars($pokemon['name']) ?></h1>
        <h2 class="text-2xl text-gray-600 mb-6 text-center">Type: <?= $pokemon['type'] ?></h2>
        <div class="grid grid-cols-2 gap-6 mb-8 justify-items-center items-center">
            <p class="text-gray-700"><span class="font-semibold">Health:</span> <?= $pokemon['health'] ?></p>
            <?php
                $healthPercent = (($pokemon['health']) / 300) * 100;
                $healthPercent = max(0, min(100, $healthPercent));
            ?>
            <div class="w-full bg-gray-200 rounded h-4">
                <div class="bg-red-500 h-4 rounded" style="width: <?= $healthPercent ?>%"></div>
            </div>

            <p class="text-gray-700"><span class="font-semibold">Attack:</span> <?= $pokemon['attack'] ?></p>
            <?php
                $attackPercent = (($pokemon['attack']) / 150) * 100;
                $attackPercent = max(0, min(100, $attackPercent));
            ?>
            <div class="w-full bg-gray-200 rounded h-4">
                <div class="bg-yellow-500 h-4 rounded" style="width: <?= $attackPercent ?>%"></div>
            </div>

            <p class="text-gray-700"><span class="font-semibold">Defense:</span> <?= $pokemon['defense'] ?></p>
            <?php
                $defensePercent = (($pokemon['defense']) / 100) * 100;
                $defensePercent = max(0, min(100, $defensePercent));
            ?>
            <div class="w-full bg-gray-200 rounded h-4">
                <div class="bg-blue-500 h-4 rounded" style="width: <?= $defensePercent ?>%"></div>
            </div>
        </div>
        <h2 class="text-3xl font-semibold mb-4 text-gray-800">Attacks</h2>
        <ul class="space-y-4 mb-8">
            <?php foreach ($attacks as $attack): ?>
                <li class="flex justify-between items-center bg-gray-50 p-4 rounded-lg shadow">
                    <div class="flex flex-col">
                        <span class="text-gray-800 font-medium"><?= htmlspecialchars($attack['name']) ?></span>
                        <div class="text-sm text-gray-600">
                            <span>Power: <?= htmlspecialchars($attack['power']) ?></span>
                            <span class="mx-2">|</span>
                            <span>Accuracy: <?= htmlspecialchars($attack['accuracy']) ?></span>
                        </div>
                    </div>
                    <form action="/pokemon/removeAttack/<?= $pokemon['id']?>" method="POST">
                        <input type="hidden" name="attack_id" value="<?= $attack['id'] ?>">
                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium" onclick="return confirm('Are you sure you want to remove this attack?')">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="flex justify-between mb-8">
            <?php if (count($attacks) < 4): ?>
                <a href="/pokemon/addAttack/<?= $pokemon['id'] ?>" class="text-blue-600 hover:underline font-medium">Add Attack</a>
            <?php endif; ?>
            <a href="/pokemon" class="text-blue-600 hover:underline font-medium">Back to Pokémon List</a>
        </div>
        <div class="space-y-4">
            <a href="/pokemon/edit/<?= $pokemon['id'] ?>" class="block w-full text-center text-white bg-blue-600 hover:bg-blue-800 py-3 rounded-lg font-bold">Edit Pokémon</a>
            <form action="/pokemon/delete" method="POST">
                <input type="hidden" name="id" value="<?= $pokemon['id'] ?>">
                <button type="submit" class="w-full text-white bg-red-600 hover:bg-red-800 py-3 rounded-lg font-bold" onclick="return confirm('Are you sure you want to delete this Pokémon?')">Delete Pokémon</button>
            </form>
        </div>
    </div>
</div>

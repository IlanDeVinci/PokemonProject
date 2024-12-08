<header class="px-8 py-12 bg-gray-100 text-center"> 
    <h1 class="text-4xl font-title text-neutral-950">Welcome to my Pokémon Project</h1>
    <p class="text-lg text-neutral-700 mt-4">Create your own Pokémon, Attacks and Battle with your favorite Pokémon!</p>
    <div class="mt-8"></div>
</header>

<div class="space-y-5 p-8">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-neutral-950">Pokémon List</h1>
        <div class="flex space-x-4">
            <a href="/pokemon/add" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Add New Pokémon</a>
            <a href="/battle" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">Battle</a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($pokemons as $pokemon): ?>
            <div class="bg-gray-50 rounded-lg shadow hover:shadow-lg transition-shadow p-6">
                <a href="/pokemon/view/<?= $pokemon['id'] ?>" class="block">
                    <h2 class="text-xl font-semibold text-neutral-950 hover:text-blue-600"><?= htmlspecialchars($pokemon['name']) ?></h2>
                    <p class="text-neutral-700 mt-2">Type: <?= htmlspecialchars($pokemon['type']) ?></p>
                    <div class="mt-4">
                        <div class="mb-2">
                            <span class="block text-sm font-medium text-neutral-700">Health: <?= htmlspecialchars($pokemon['health']) ?></span>
                            <div class="w-full bg-gray-200 rounded h-2.5">
                                <?php
                                    $healthPercent = (($pokemon['health']) / (300)) * 100;
                                    $healthPercent = max(0, min(100, $healthPercent));
                                ?>
                                <div class="bg-red-500 h-2.5 rounded" style="width: <?= $healthPercent ?>%"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <span class="block text-sm font-medium text-neutral-700">Attack: <?= htmlspecialchars($pokemon['attack']) ?></span>
                            <div class="w-full bg-gray-200 rounded h-2.5">
                                <?php
                                    $attackPercent = (($pokemon['attack']) / (150)) * 100;
                                    $attackPercent = max(0, min(100, $attackPercent));
                                ?>
                                <div class="bg-yellow-500 h-2.5 rounded" style="width: <?= $attackPercent ?>%"></div>
                            </div>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-neutral-700">Defense: <?= htmlspecialchars($pokemon['defense']) ?></span>
                            <div class="w-full bg-gray-200 rounded h-2.5">
                                <?php
                                    $defensePercent = (($pokemon['defense']) / (100)) * 100;
                                    $defensePercent = max(0, min(100, $defensePercent));
                                ?>
                                <div class="bg-blue-500 h-2.5 rounded" style="width: <?= $defensePercent ?>%"></div>
</div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

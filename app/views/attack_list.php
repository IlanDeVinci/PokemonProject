<header class="px-8 py-12 bg-gray-100 text-center"> 
    <h1 class="text-4xl font-title text-neutral-950">Welcome to my Pokémon Project</h1>
    <p class="text-lg text-neutral-700 mt-4">View and manage your Pokémon's attacks!</p>
    <div class="mt-8"></div>
</header>

<div class="space-y-5 p-8">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-neutral-950">Attack List</h1>
        <div class="flex space-x-4">
            <a href="/attack/add" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Add New Attack</a>
            <a href="/battle" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">Battle</a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($attacks as $attack): ?>
            <div class="bg-gray-50 rounded-lg shadow hover:shadow-lg transition-shadow p-6">
                <a href="/attack/view/<?= $attack['id'] ?>" class="block">
                    <h2 class="text-xl font-semibold text-neutral-950 hover:text-blue-600">
                        <?= htmlspecialchars($attack['name']) ?>
                    </h2>
                    <p class="text-neutral-700 mt-2">Power: <?= htmlspecialchars($attack['power']) ?></p>
                      <div class="w-full bg-gray-300 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?= min(100, max(0, (($attack['power']) / 150) * 100)) ?>%"></div>
                    </div>
                    <p class="text-neutral-700 mt-2">Accuracy: <?= htmlspecialchars($attack['accuracy']) ?></p>
                  
                    <div class="w-full bg-gray-300 rounded-full h-2.5">
                        <div class="bg-green-600 h-2.5 rounded-full" style="width: <?= min(100, max(0, (($attack['accuracy']) / 100) * 100)) ?>%"></div>
                    </div>

                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

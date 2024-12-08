<div class="max-w-md mx-auto my-12 bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="p-8">
        <h1 class="text-4xl font-bold mb-4 text-center text-gray-800"><?= htmlspecialchars($attack['name']) ?></h1>
        <p class="text-neutral-700 mt-2">Power: <?= htmlspecialchars($attack['power']) ?></p>
                      <div class="w-full bg-gray-300 rounded-full h-4">
                        <div class="bg-blue-600 h-4 rounded-full" style="width: <?= min(100, max(0, (($attack['power']) / 150) * 100)) ?>%"></div>
                    </div>
                    <p class="text-neutral-700 mt-2">Accuracy: <?= htmlspecialchars($attack['accuracy']) ?></p>
                
                    <div class="w-full bg-gray-300 rounded-full h-4 mb-4">
                        <div class="bg-green-600 h-4 rounded-full" style="width: <?= min(100, max(0, (($attack['accuracy']) / 100) * 100)) ?>%"></div>
                    </div>
        <div class="flex justify-center">
            <a href="/attack" class="text-blue-600 hover:underline font-medium">Back to Attack List</a>
        </div>
    </div>
</div>

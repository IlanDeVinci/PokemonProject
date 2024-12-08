<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Pokemon Project' ?></title>
    <link rel="stylesheet" href="../../public/style.css">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex flex-col">
    <header class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-lg">
        <nav class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
            <a href="/" class="flex items-center text-2xl font-bold hover:text-blue-200">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Pok%C3%A9_Ball_icon.svg/1026px-Pok%C3%A9_Ball_icon.svg.png" alt="Logo" class="h-8 w-8 mr-2">
                Pokémon Project
            </a>
            <div class="hidden md:flex items-center space-x-6">
                <a href="/" class="hover:text-blue-200 transition-colors">Home</a>
                <a href="/battle" class="hover:text-blue-200 transition-colors">Battle</a>
                <a href="/attack" class="hover:text-blue-200 transition-colors">Attacks</a>
            </div>
            </div>
        </nav>
    </header>

    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm p-6">
            <?= $content ?? '<p class="text-gray-500">No content to display</p>' ?>
        </div>
    </main>

    <footer class="bg-gray-800 text-white mt-auto py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p>&copy; <?= date('Y') ?> Pokémon Project</p>
            </div>
        </div>
    </footer>
</body>
</html>
<?php
require __DIR__ . '/../vendor/autoload.php';

use DataConceptAssignment\WeatherApp\Controllers\WeatherController;

$controller = new WeatherController();

$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $result = $controller->handleRequest($_POST['city'], $_POST['date']);
    } catch (\Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aké bude počasie?</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-bold mb-6 text-center">Aké bude počasie?</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Chyba!</strong>
                <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($result): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($result['message']); ?></span>
            </div>
            <a href="<?php echo htmlspecialchars($result['downloadLink']); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block mb-4">
                Stiahnuť Excel
            </a>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-4">
                <label for="city" class="block text-gray-700 text-sm font-bold mb-2">Mesto:</label>
                <input type="text" id="city" name="city" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-6">
                <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Dátum:</label>
                <input type="date" id="date" name="date" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                Odoslať
            </button>
        </form>
    </div>
</body>
</html>
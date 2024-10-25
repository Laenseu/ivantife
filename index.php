<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ivanti ISM Service Request</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-md rounded-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Ivanti ISM Service Request</h2>
        <form action="process.php" method="POST">
            <div class="mb-4">
                <label for="user" class="block text-sm font-medium text-gray-700">Username:</label>
                <input type="text" id="user" name="user" required
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500 p-2" />
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-700 transition duration-200">Submit</button>
        </form>
    </div>

</body>
</html>



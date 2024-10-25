<?php
// Start the session
session_start();

$validationMessage = ""; // Initialize the message variable
$success = false; // Initialize the success flag


if (isset($_SESSION['strRequestNum'], $_SESSION['recId'])) {
    $strRequestNum = $_SESSION['strRequestNum'];
    $recId = $_SESSION['recId'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userInput = $_POST['number1'];
        $otpInput = $_POST['number2'];

        // Check if user input matches the stored `strRequestNum`
        if ($userInput == $strRequestNum) {
            $validationMessage = "RecID: $recId"; // Display RecID if matched

            // Add cURL request here with `recId` and `otpInput`
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://sititsmserver.domain.local/HEAT/api/odata/businessobject/servicereqs('$recId')",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode([
                    "_OTP2" => $otpInput
                ]),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: rest_api_key=9E7D8E238EE34F92B87F595841DD7079',
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            // Output the response
            if ($response) {
                $validationMessage .= "<br>Update Response: " . htmlspecialchars($response);
                $success = true; // Set success flag
            } else {
                $validationMessage .= "<br>Failed to update OTP.";
                $success = false; // Set failure flag
            }
        } else {
            $validationMessage = "No match found for the entered number.";
            $success = false; // Set failure flag
        }
    }
} else {
    $validationMessage = "Session values are missing. Please go back and resubmit the form.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number Input Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-lg p-8 max-w-sm w-full">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Number Input Form</h1>
        <form id="numberForm" method="post" action="">
            <div class="mb-4">
                <label for="number1" class="block text-sm font-medium text-gray-700">Service Request:</label>
                <input type="number" id="number1" name="number1" required
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500 p-2" />
            </div>
            <div class="mb-4">
                <label for="number2" class="block text-sm font-medium text-gray-700">OTP:</label>
                <input type="number" id="number2" name="number2" required
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500 p-2" />
            </div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-700 transition duration-200">Submit</button>
        </form>
    </div>


    <?php if ($validationMessage): ?>
    <script>
        alert(<?php echo json_encode($success ? "Success: " . $validationMessage : "Error: " . $validationMessage); ?>);
    </script>
    <?php endif; ?>

</body>
</html>

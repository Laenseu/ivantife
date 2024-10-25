<?php

// $username = $_GET['username'];
// $user_recid = $_POST['user']; // Get the username from POST data
// ATaylor - 1087342EA6954D7D96140D64B452E597

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Start the session
session_start();

// Check if the form data is set
if (isset($_POST['user'])) {
    validate();
} else {
    echo "Please enter a username.";
}

function validate(){
    

    

try {
    // WSDL URL of your Ivanti ISM service
    $wsdl = "http://sititsmserver.domain.local/HEAT/ServiceAPI/FRSHEATIntegration.asmx?wsdl";
    
    $username = $_POST['user'];
    // Create a new SOAP client
    $client = new SoapClient($wsdl, array('trace' => 1));

    // Define the parameters for the request
    $params = array(
        'sessionKey' => 'SITITSMSERVER.domain.local#SV7Q3TEKTTJPE3TP3AJCCD7SC7G0DGS0#2', // Your session key
        'tenantId'   => 'SITITSMSERVER.domain.local',  // Replace with your actual tenant ID
        'boType'     => 'Employee',  // Business Object type (e.g., Employee)
        'fieldName'  => 'LoginID',  // Field name to search by (e.g., LoginId)
        'fieldValue' => $username   // The value of the field to match (e.g., ATaylor)
    );

    // Make the SOAP call for 'FindBusinessObject' action
    $response = $client->__soapCall('FindSingleBusinessObjectByField', array($params));

    // Output the response
    // print_r($response);
    
    
    if ($response->FindSingleBusinessObjectByFieldResult->status === "Success") {
        $recId = $response->FindSingleBusinessObjectByFieldResult->obj->RecID;
        $_SESSION['recId'] = $recId; // Store RecID in session
        execute();
        
        // Redirect to validation.php after successful execution
        header("Location: validation.php");
        exit;  // Stop further execution
    } else {
        echo "Failed to retrieve RecID.";
        header("Location: validation.php");

    }

    
    
    
} catch (Exception $e) {
    // Display error if something goes wrong
    echo "Error: " . $e->getMessage();
}
    
}

function execute(){
    $user_recid = $_POST['user'];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://sititsmserver.domain.local/HEAT/api/rest/ServiceRequest/new',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode([
            "attachmentsToDelete" => [],
            "attachmentsToUpload" => [],
            "parameters" => [
                "par-69C9987AA5AF4C088B3DEC08432E6D78" => "Mike",
                "par-8340A894FB4F4352B4E3A7A8A145C65E" => "Location",   
            ],
            "delayedFulfill" => false,
            "formName" => "ServiceReq.ResponsiveAnalyst.DefaultLayout",
            "saveReqState" => false,
            "serviceReqData" => [
                "Subject" => "SR from RESTAPI - Mike Test 1 2 3",
                "Symptom" => "SR from RESTAPI - Mike Test 1 2 3"
            ],
            "strCustomerLocation" => "West",
            "strUserId" => $user_recid, // Use the variable directly
            "subscriptionId" => "5B65569636FD4962A4C228B6E67BDACB", // This is subscriptionID 
            "localOffset" => -330
        ]),
        CURLOPT_HTTPHEADER => array(
            'Authorization: rest_api_key=9E7D8E238EE34F92B87F595841DD7079',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    
    if (curl_errno($curl)) {
        // Output cURL error
        echo 'cURL Error: ' . curl_error($curl);
    } else {
        // Decode the JSON response
        $data = json_decode($response, true);
        
        // Check if the response indicates success
        if ($data['IsSuccess']) {
            // Extract strRequestNum and RecID
            $strRequestNum = $data['ServiceRequests'][0]['strRequestNum'];
            $_SESSION['strRequestNum'] = $strRequestNum; // Store strRequestNum in session
            $_SESSION['recId'] = $data['ServiceRequests'][0]['strRequestRecId']; // Store RecID in session

            // Redirect to validation.php after successful execution
            header("Location: validation.php"); // Redirect to validation.php
            exit;  // Stop further execution
        } else {
            echo "Failed to create service request. Reason: " . $data['ErrorText'];
        }
    }

    curl_close($curl);
}
?>
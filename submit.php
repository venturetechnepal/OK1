<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $list_id = 'ac031324a9'; // Your Mailchimp list ID
    $authToken = '2ee17e9bd897c3c8b7d6aabeff0276ce-us22'; // Your Mailchimp API key
    $email = $_POST['email']; // Email from form submission
    $data_center = substr($authToken, strpos($authToken, '-') + 1); // Extract data center from API key
    $url = 'https://' . $data_center . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members'; // Construct API URL

    // JSON payload to be sent in the API request
    $json = json_encode([
        'email_address' => $email,
        'status' => 'subscribed', // pass 'subscribed' or 'pending'
    ]);

    try {
        $ch = curl_init($url); // Initialize cURL session
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $authToken); // Set HTTP authentication header with API key
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // Set content type to JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as string
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Set request timeout
        curl_setopt($ch, CURLOPT_POST, 1); // Specify this is a POST request
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (not recommended for production)
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json); // Set JSON payload as POST fields

        $result = curl_exec($ch); // Execute cURL session and get response
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get HTTP status code of response
        curl_close($ch); // Close cURL session

        // Print response and success message if email added successfully
        if (200 == $status_code) {
            echo "Mail Added Successfully";
        } else {
            echo "Failed to add email. Please try again.";
        }
    } catch (Exception $e) {
        // Print error message if exception occurs
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>

<?php
// Target Node.js server URL
$nodejs_url = 'https://sebas7.estimation.quebec:3002'; // Replace with your Node.js server URL

// Prepare the request URL


//$request_url = $nodejs_url . $_SERVER['REQUEST_URI'];


$request_uri = str_replace('/api/node.php/', '/', $_SERVER['REQUEST_URI']);
$request_uri = str_replace('/api/node.php?', '/', $request_uri);

$request_url = $nodejs_url . $request_uri;

//echo $request_uri;
//exit(0);


// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $request_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL certificate verification
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Disable hostname verification
// Pass client's request headers to the Node.js server
$headers = [];
foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'HTTP_') === 0) {
        $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
        $headers[] = $key . ': ' . $value;	
    }
}
//$headers[] = 'Access-Control-Allow-Origin: *';

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


// Execute the cURL request
$response = curl_exec($ch);

// Check for cURL errors
if(curl_errno($ch)) {
    // Handle error
    $error_message = curl_error($ch);
    // Output or log the error message
    echo "cURL Error: " . $error_message;
}

// Close cURL session
curl_close($ch);

// Output the response from the Node.js server
//echo $response;

// Extract response headers and body
list($headers, $body) = explode("\r\n\r\n", $response, 2);

// Output response headers
$headers = explode("\r\n", $headers);
$headers[] = "Access-Control-Allow-Origin: ['https://localhost:4200', 'https://estimation.quebec']";

foreach ($headers as $header) {
    header($header);
}



// Output the response body from the Node.js server
echo $body;

?>


<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS, post, get');
header("Access-Control-Max-Age", "3600");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header("Access-Control-Allow-Credentials", "true");

print_r($_POST["name"]);
// header("Content-Type: multipart/form-data");
// $data = json_decode(file_get_contents("php://input"), TRUE);
// $CustomerName = $data['customer_name'];
// echo $CustomerName; 


// echo "<pre>";
// print_r($_SERVER["REQUEST_METHOD"]) . "<br>";
// echo "<pre>";
// echo $_POST["name"];
// }
// if (isset($_FILES["id"])) {
//     echo "there is id";
// }
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     header('HTTP/1.1 204 No Content');
//     die;
// }
// End of CORS things

// $requestBody = file_get_contents('php://input');
// $requestBody = json_decode($requestBody, true);
// if ($requestBody === null) {
//     header('HTTP/1.1 400 Bad Request');
//     echo json_encode([
//         'errorMessage' => 'Please provide valid JSON',
//     ]);
//     die;
// }

// echo $_POST;

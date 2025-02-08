<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Handle CORS

// Helper functions
function is_prime($n) {
    if ($n < 2) return false;
    for ($i = 2; $i * $i <= $n; $i++) {
        if ($n % $i == 0) return false;
    }
    return true;
}

function is_perfect($n) {
    if ($n < 2) return false;
    $sum = 0;
    for ($i = 1; $i < $n; $i++) {
        if ($n % $i == 0) $sum += $i;
    }
    return $sum == $n;
}

function is_armstrong($n) {
    $digits = str_split((string)$n);
    $length = count($digits);
    $sum = 0;
    foreach ($digits as $digit) {
        $sum += pow((int)$digit, $length);
    }
    return $sum == $n;
}

function digit_sum($n) {
    return array_sum(str_split((string)$n));
}

function get_fun_fact($n) {
    $url = "http://numbersapi.com/$n/math?json";
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data['text'] ?? "No fun fact available.";
}

// Main logic
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['number'])) {
    $input = $_GET['number'];
    if (!is_numeric($input)) {
        http_response_code(400);
        echo json_encode(["number" => $input, "error" => true]);
        exit;
    }

    $number = (int)$input;
    $properties = [];
    if (is_armstrong($number)) $properties[] = "armstrong";
    if ($number % 2 == 0) $properties[] = "even";
    else $properties[] = "odd";

    $response = [
        "number" => $number,
        "is_prime" => is_prime($number),
        "is_perfect" => is_perfect($number),
        "properties" => $properties,
        "digit_sum" => digit_sum($number),
        "fun_fact" => get_fun_fact($number)
    ];

    http_response_code(200);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(["number" => null, "error" => true]);
}
?>

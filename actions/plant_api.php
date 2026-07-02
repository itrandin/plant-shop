<?php

header('Content-Type: application/json; charset=utf-8');

$token = 'usr-7giZ0rU8DWotHtoW9CDgFT7dAKzU5pCD5WsGpbRN-ro';
$query = trim($_GET['q'] ?? '');

if ($query === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Не указано растение.'
    ]);
    exit;
}

$url = 'https://trefle.io/api/v1/plants/search?token=' . urlencode($token) . '&q=' . urlencode($query);

$response = @file_get_contents($url);

if ($response === false) {
    echo json_encode([
        'success' => false,
        'message' => 'Не удалось получить данные из API.'
    ]);
    exit;
}

$data = json_decode($response, true);

if (empty($data['data'][0])) {
    echo json_encode([
        'success' => false,
        'message' => 'Информация о растении не найдена.'
    ]);
    exit;
}

$plant = $data['data'][0];

echo json_encode([
    'success' => true,
    'name' => $plant['common_name'] ?? 'Не указано',
    'scientific_name' => $plant['scientific_name'] ?? 'Не указано',
    'family' => $plant['family'] ?? 'Не указано',
    'genus' => $plant['genus'] ?? 'Не указано',
    'year' => $plant['year'] ?? 'Не указано',
    'image_url' => $plant['image_url'] ?? ''
]);
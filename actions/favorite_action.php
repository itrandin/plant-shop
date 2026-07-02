<?php

require_once '../config/config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn()) {
    echo json_encode([
        'success' => false,
        'message' => 'Необходимо войти в аккаунт.'
    ]);
    exit;
}

$id_user = getCurrentUserId();
$id_plant = (int)($_POST['id_plant'] ?? 0);

if ($id_plant <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Некорректный товар.'
    ]);
    exit;
}

$check = mysqli_prepare($conn, "
    SELECT id_favorite 
    FROM favorites 
    WHERE id_user = ? AND id_plant = ?
");

mysqli_stmt_bind_param($check, "ii", $id_user, $id_plant);
mysqli_stmt_execute($check);

$result = mysqli_stmt_get_result($check);

if ($favorite = mysqli_fetch_assoc($result)) {
    $delete = mysqli_prepare($conn, "
        DELETE FROM favorites 
        WHERE id_favorite = ?
    ");

    mysqli_stmt_bind_param($delete, "i", $favorite['id_favorite']);
    mysqli_stmt_execute($delete);

    echo json_encode([
        'success' => true,
        'status' => 'removed'
    ]);
    exit;
}

$insert = mysqli_prepare($conn, "
    INSERT INTO favorites (id_user, id_plant)
    VALUES (?, ?)
");

mysqli_stmt_bind_param($insert, "ii", $id_user, $id_plant);
mysqli_stmt_execute($insert);

echo json_encode([
    'success' => true,
    'status' => 'added'
]);
exit;
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
    SELECT id_cart, quantity
    FROM cart
    WHERE id_user = ? AND id_plant = ?
");

mysqli_stmt_bind_param($check, "ii", $id_user, $id_plant);
mysqli_stmt_execute($check);

$result = mysqli_stmt_get_result($check);

if ($item = mysqli_fetch_assoc($result)) {
    $new_quantity = $item['quantity'] + 1;

    $update = mysqli_prepare($conn, "
        UPDATE cart
        SET quantity = ?
        WHERE id_cart = ?
    ");

    mysqli_stmt_bind_param($update, "ii", $new_quantity, $item['id_cart']);
    mysqli_stmt_execute($update);
} else {
    $insert = mysqli_prepare($conn, "
        INSERT INTO cart (id_user, id_plant, quantity)
        VALUES (?, ?, 1)
    ");

    mysqli_stmt_bind_param($insert, "ii", $id_user, $id_plant);
    mysqli_stmt_execute($insert);
}

$count_query = mysqli_prepare($conn, "
    SELECT COALESCE(SUM(quantity), 0) AS cart_count
    FROM cart
    WHERE id_user = ?
");

mysqli_stmt_bind_param($count_query, "i", $id_user);
mysqli_stmt_execute($count_query);

$count_result = mysqli_stmt_get_result($count_query);
$count_data = mysqli_fetch_assoc($count_result);

echo json_encode([
    'success' => true,
    'message' => 'Товар добавлен в корзину.',
    'cart_count' => (int)$count_data['cart_count']
]);
exit;
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
$id_cart = (int)($_POST['id_cart'] ?? 0);
$action = $_POST['action'] ?? '';

if ($id_cart <= 0 || !in_array($action, ['plus', 'minus'], true)) {
    echo json_encode([
        'success' => false,
        'message' => 'Некорректные данные.'
    ]);
    exit;
}

$item_query = mysqli_prepare($conn, "
    SELECT c.id_cart, c.quantity, p.price
    FROM cart c
    INNER JOIN plants p ON c.id_plant = p.id_plant
    WHERE c.id_cart = ? AND c.id_user = ?
");

mysqli_stmt_bind_param($item_query, "ii", $id_cart, $id_user);
mysqli_stmt_execute($item_query);
$item_result = mysqli_stmt_get_result($item_query);
$item = mysqli_fetch_assoc($item_result);

if (!$item) {
    echo json_encode([
        'success' => false,
        'message' => 'Товар не найден.'
    ]);
    exit;
}

$new_quantity = (int)$item['quantity'];
$new_quantity += $action === 'plus' ? 1 : -1;
$removed = false;

if ($new_quantity <= 0) {
    $delete = mysqli_prepare($conn, "
        DELETE FROM cart
        WHERE id_cart = ? AND id_user = ?
    ");
    mysqli_stmt_bind_param($delete, "ii", $id_cart, $id_user);
    mysqli_stmt_execute($delete);
    $removed = true;
} else {
    $update = mysqli_prepare($conn, "
        UPDATE cart
        SET quantity = ?
        WHERE id_cart = ? AND id_user = ?
    ");
    mysqli_stmt_bind_param($update, "iii", $new_quantity, $id_cart, $id_user);
    mysqli_stmt_execute($update);
}

$totals_query = mysqli_prepare($conn, "
    SELECT 
        COALESCE(SUM(c.quantity), 0) AS cart_count,
        COALESCE(SUM(c.quantity * p.price), 0) AS cart_total
    FROM cart c
    INNER JOIN plants p ON c.id_plant = p.id_plant
    WHERE c.id_user = ?
");

mysqli_stmt_bind_param($totals_query, "i", $id_user);
mysqli_stmt_execute($totals_query);
$totals = mysqli_fetch_assoc(mysqli_stmt_get_result($totals_query));

$cart_count = (int)$totals['cart_count'];
$cart_total = (float)$totals['cart_total'];
$delivery_price = $cart_count > 0 ? 350 : 0;
$final_total = $cart_total + $delivery_price;
$item_total = $removed ? 0 : ((float)$item['price'] * $new_quantity);

echo json_encode([
    'success' => true,
    'removed' => $removed,
    'quantity' => $new_quantity,
    'item_total' => number_format($item_total, 0, '', ' ') . ' ₽',
    'cart_count' => $cart_count,
    'cart_total' => number_format($cart_total, 0, '', ' ') . ' ₽',
    'delivery_price' => number_format($delivery_price, 0, '', ' ') . ' ₽',
    'final_total' => number_format($final_total, 0, '', ' ') . ' ₽'
]);
exit;

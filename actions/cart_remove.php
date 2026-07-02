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

if ($id_cart <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Некорректный товар.'
    ]);
    exit;
}

$delete = mysqli_prepare($conn, "
    DELETE FROM cart
    WHERE id_cart = ? AND id_user = ?
");
mysqli_stmt_bind_param($delete, "ii", $id_cart, $id_user);
mysqli_stmt_execute($delete);

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

echo json_encode([
    'success' => true,
    'cart_count' => $cart_count,
    'cart_total' => number_format($cart_total, 0, '', ' ') . ' ₽',
    'delivery_price' => number_format($delivery_price, 0, '', ' ') . ' ₽',
    'final_total' => number_format($final_total, 0, '', ' ') . ' ₽'
]);
exit;

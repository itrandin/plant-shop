<?php

require_once '../config/config.php';

if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cart.php');
    exit;
}

$id_user = getCurrentUserId();

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$city = trim($_POST['city'] ?? '');
$street = trim($_POST['street'] ?? '');
$house = trim($_POST['house'] ?? '');

if ($name === '' || $phone === '' || $city === '' || $street === '' || $house === '') {
    $_SESSION['cart_error'] = 'Заполните обязательные поля для оформления заказа.';
    header('Location: ../cart.php');
    exit;
}

$cart_query = mysqli_prepare($conn, "
    SELECT c.id_cart, c.id_plant, c.quantity, p.price
    FROM cart c
    INNER JOIN plants p ON c.id_plant = p.id_plant
    WHERE c.id_user = ?
");
mysqli_stmt_bind_param($cart_query, "i", $id_user);
mysqli_stmt_execute($cart_query);
$cart_result = mysqli_stmt_get_result($cart_query);

$cart_items = [];
$cart_total = 0;
$cart_count = 0;

while ($item = mysqli_fetch_assoc($cart_result)) {
    $cart_items[] = $item;
    $cart_total += (float)$item['price'] * (int)$item['quantity'];
    $cart_count += (int)$item['quantity'];
}

if (empty($cart_items)) {
    $_SESSION['cart_error'] = 'Корзина пуста.';
    header('Location: ../cart.php');
    exit;
}

$delivery_price = $cart_count > 0 ? 350 : 0;
$total_amount = $cart_total + $delivery_price;

mysqli_begin_transaction($conn);

try {
    $order_stmt = mysqli_prepare($conn, "
        INSERT INTO orders (id_user, status, total_amount)
        VALUES (?, 'Новый', ?)
    ");
    mysqli_stmt_bind_param($order_stmt, "id", $id_user, $total_amount);

    if (!mysqli_stmt_execute($order_stmt)) {
        throw new Exception('Не удалось создать заказ.');
    }

    $id_order = mysqli_insert_id($conn);

    $item_stmt = mysqli_prepare($conn, "
        INSERT INTO order_items (id_order, id_plant, quantity, price)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($cart_items as $item) {
        $id_plant = (int)$item['id_plant'];
        $quantity = (int)$item['quantity'];
        $price = (float)$item['price'];

        mysqli_stmt_bind_param($item_stmt, "iiid", $id_order, $id_plant, $quantity, $price);

        if (!mysqli_stmt_execute($item_stmt)) {
            throw new Exception('Не удалось сохранить состав заказа.');
        }
    }

    $clear_stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE id_user = ?");
    mysqli_stmt_bind_param($clear_stmt, "i", $id_user);

    if (!mysqli_stmt_execute($clear_stmt)) {
        throw new Exception('Не удалось очистить корзину.');
    }

    mysqli_commit($conn);

    $_SESSION['checkout_success'] = 'Спасибо за заказ! Номер заказа: #' . $id_order . '.';
    header('Location: ../account.php');
    exit;
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['cart_error'] = 'Ошибка оформления заказа. Попробуйте еще раз.';
    header('Location: ../cart.php');
    exit;
}

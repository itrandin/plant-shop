<?php

function adminTables(): array {
    return [
        'roles' => ['pk' => 'id_role', 'title' => 'Роли'],
        'users' => ['pk' => 'id_user', 'title' => 'Пользователи'],
        'categories' => ['pk' => 'id_category', 'title' => 'Категории'],
        'care_levels' => ['pk' => 'id_care_level', 'title' => 'Уровни ухода'],
        'plants' => ['pk' => 'id_plant', 'title' => 'Растения'],
        'favorites' => ['pk' => 'id_favorite', 'title' => 'Избранное'],
        'cart' => ['pk' => 'id_cart', 'title' => 'Корзина'],
        'orders' => ['pk' => 'id_order', 'title' => 'Заказы'],
        'order_items' => ['pk' => 'id_order_item', 'title' => 'Товары в заказах'],
        'reviews' => ['pk' => 'id_review', 'title' => 'Отзывы'],
        'contact_messages' => ['pk' => 'id_message', 'title' => 'Сообщения обратной связи'],
    ];
}

function adminViews(): array {
    return [
        'vw_catalog' => 'Каталог растений',
        'vw_popular_plants' => 'Популярные растения',
        'vw_discount_plants' => 'Растения со скидкой',
        'vw_user_orders' => 'История заказов пользователей',
        'vw_plant_reviews' => 'Отзывы о растениях',
    ];
}

function requireAdmin(): void {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }

    if (!isAdmin()) {
        header('Location: account.php');
        exit;
    }
}

function getAdminTableConfig(string $table): ?array {
    $tables = adminTables();
    return $tables[$table] ?? null;
}

function h($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

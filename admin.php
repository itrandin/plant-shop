<?php
require_once 'config/config.php';
requireAdmin();
include 'includes/header.php';

$tables = adminTables();
$views = adminViews();

$stats = [];
foreach (['users', 'plants', 'orders', 'reviews', 'contact_messages'] as $table) {
    $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM {$table}");
    $stats[$table] = (int)mysqli_fetch_assoc($result)['total'];
}

$salesResult = mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS total_sales FROM orders");
$stats['sales'] = (float)mysqli_fetch_assoc($salesResult)['total_sales'];

$cartResult = mysqli_query($conn, "SELECT COALESCE(SUM(quantity), 0) AS cart_items FROM cart");
$stats['cart_items'] = (int)mysqli_fetch_assoc($cartResult)['cart_items'];
?>

<main>
    <section class="admin-hero-section">
        <div class="container">
            <div class="admin-hero-box">
                <h1>Административная панель</h1>
                <p>Управление таблицами базы данных, просмотр заказов и представлений.</p>
            </div>
        </div>
    </section>

    <section class="admin-section">
        <div class="container">
            <div class="admin-stats-grid">
                <article><b><?= $stats['users'] ?></b><span>Пользователей</span></article>
                <article><b><?= $stats['plants'] ?></b><span>Растений</span></article>
                <article><b><?= $stats['orders'] ?></b><span>Заказов</span></article>
                <article><b><?= number_format($stats['sales'], 0, '', ' ') ?> ₽</b><span>Сумма продаж</span></article>
                <article><b><?= $stats['reviews'] ?></b><span>Отзывов</span></article>
                <article><b><?= $stats['contact_messages'] ?></b><span>Сообщений</span></article>
                <article><b><?= $stats['cart_items'] ?></b><span>Товаров в корзинах</span></article>
            </div>


            <div class="admin-card">
                <div class="admin-card-head">
                    <h2>Быстрое управление</h2>
                    <p>Основные разделы администратора для работы с магазином.</p>
                </div>

                <div class="admin-table-links admin-quick-links">
                    <a href="admin_orders.php">Заказы и статусы</a>
                    <a href="admin_users.php">Пользователи и роли</a>
                    <a href="admin_reviews.php">Отзывы покупателей</a>
                    <a href="admin_messages.php">Сообщения</a>
                    <a href="admin_table.php?table=plants">Растения</a>
                    <a href="admin_view.php?view=vw_catalog">Представление каталога</a>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-head">
                    <h2>Таблицы базы данных</h2>
                    <p>Для каждой таблицы доступен просмотр, добавление, изменение и удаление записей.</p>
                </div>

                <div class="admin-table-links">
                    <?php foreach ($tables as $name => $config): ?>
                        <a href="admin_table.php?table=<?= h($name) ?>"><?= h($config['title']) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-head">
                    <h2>Представления</h2>
                    <p>Представления доступны только для просмотра.</p>
                </div>

                <div class="admin-table-links">
                    <?php foreach ($views as $name => $title): ?>
                        <a href="admin_view.php?view=<?= h($name) ?>"><?= h($title) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

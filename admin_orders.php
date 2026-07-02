<?php
require_once 'config/config.php';
requireAdmin();

$ordersQuery = mysqli_query($conn, "
    SELECT 
        o.id_order,
        o.order_date,
        o.status,
        o.total_amount,
        u.name AS user_name,
        u.email,
        GROUP_CONCAT(CONCAT(p.plant_name, ' × ', oi.quantity) SEPARATOR ', ') AS products
    FROM orders o
    INNER JOIN users u ON o.id_user = u.id_user
    LEFT JOIN order_items oi ON o.id_order = oi.id_order
    LEFT JOIN plants p ON oi.id_plant = p.id_plant
    GROUP BY o.id_order, o.order_date, o.status, o.total_amount, u.name, u.email
    ORDER BY o.order_date DESC
");

$statuses = ['Новый', 'В обработке', 'Отправлен', 'Доставлен', 'Отменен'];

include 'includes/header.php';
?>

<main>
    <section class="admin-section">
        <div class="container">
            <div class="admin-page-head">
                <div>
                    <h1>Управление заказами</h1>
                    <p>Просмотр заказов покупателей и изменение текущего статуса доставки.</p>
                </div>
                <a class="admin-btn admin-btn-light" href="admin.php">← Назад</a>
            </div>

            <?php if (!empty($_SESSION['admin_success'])): ?>
                <div class="admin-message"><?= h($_SESSION['admin_success']) ?></div>
                <?php unset($_SESSION['admin_success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['admin_error'])): ?>
                <div class="admin-message admin-message-error"><?= h($_SESSION['admin_error']) ?></div>
                <?php unset($_SESSION['admin_error']); ?>
            <?php endif; ?>

            <div class="admin-data-card">
                <div class="admin-table-wrap">
                    <table class="admin-data-table admin-orders-table">
                        <thead>
                            <tr>
                                <th>№ заказа</th>
                                <th>Дата</th>
                                <th>Покупатель</th>
                                <th>Товары</th>
                                <th>Сумма</th>
                                <th>Статус</th>
                                <th>Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = mysqli_fetch_assoc($ordersQuery)): ?>
                                <tr>
                                    <td>#<?= (int)$order['id_order'] ?></td>
                                    <td><?= h(date('d.m.Y', strtotime($order['order_date']))) ?></td>
                                    <td>
                                        <strong><?= h($order['user_name']) ?></strong><br>
                                        <?= h($order['email']) ?>
                                    </td>
                                    <td><?= h($order['products'] ?: 'Состав заказа не указан') ?></td>
                                    <td><strong><?= number_format((float)$order['total_amount'], 0, '', ' ') ?> ₽</strong></td>
                                    <td>
                                        <form class="admin-inline-form" action="actions/admin_order_status.php" method="POST">
                                            <input type="hidden" name="id_order" value="<?= (int)$order['id_order'] ?>">
                                            <select name="status">
                                                <?php foreach ($statuses as $status): ?>
                                                    <option value="<?= h($status) ?>" <?= $order['status'] === $status ? 'selected' : '' ?>>
                                                        <?= h($status) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                    </td>
                                    <td>
                                            <button class="admin-small-btn" type="submit">Сохранить</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

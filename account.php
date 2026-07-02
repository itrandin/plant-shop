<?php
require_once 'config/config.php';
requireLogin();

$user_id = getCurrentUserId();

$stmt = mysqli_prepare($conn, "SELECT name, email, phone FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$orders_count_query = mysqli_prepare($conn, "
    SELECT COUNT(*) AS count_orders, COALESCE(SUM(total_amount), 0) AS total_sum
    FROM orders
    WHERE id_user = ?
");
mysqli_stmt_bind_param($orders_count_query, "i", $user_id);
mysqli_stmt_execute($orders_count_query);
$orders_stats = mysqli_fetch_assoc(mysqli_stmt_get_result($orders_count_query));

$favorites_count_query = mysqli_prepare($conn, "
    SELECT COUNT(*) AS count_favorites
    FROM favorites
    WHERE id_user = ?
");
mysqli_stmt_bind_param($favorites_count_query, "i", $user_id);
mysqli_stmt_execute($favorites_count_query);
$favorites_stats = mysqli_fetch_assoc(mysqli_stmt_get_result($favorites_count_query));

$orders_query = mysqli_prepare($conn, "
    SELECT 
        o.id_order,
        o.order_date,
        o.status,
        o.total_amount,
        COALESCE(GROUP_CONCAT(p.plant_name SEPARATOR ', '), 'Заказ') AS order_products
    FROM orders o
    LEFT JOIN order_items oi ON o.id_order = oi.id_order
    LEFT JOIN plants p ON oi.id_plant = p.id_plant
    WHERE o.id_user = ?
    GROUP BY o.id_order, o.order_date, o.status, o.total_amount
    ORDER BY o.order_date DESC
");
mysqli_stmt_bind_param($orders_query, "i", $user_id);
mysqli_stmt_execute($orders_result = $orders_query);
$orders_result = mysqli_stmt_get_result($orders_query);

$favorites_query = mysqli_prepare($conn, "
    SELECT p.id_plant, p.plant_name, p.description, p.price, p.image
    FROM favorites f
    INNER JOIN plants p ON f.id_plant = p.id_plant
    WHERE f.id_user = ?
    ORDER BY f.date_added DESC
");
mysqli_stmt_bind_param($favorites_query, "i", $user_id);
mysqli_stmt_execute($favorites_query);
$favorites_result = mysqli_stmt_get_result($favorites_query);

include 'includes/header.php';
?>


    <main>
        <section class="account-hero-section">
            <div class="container">
                <div class="account-hero-box">
                    <div class="account-hero-text">
                        <h1>Личный кабинет</h1>
                        <h2>Просмотр заказов и управление избранными растениями</h2>
                        <p>В этом разделе вы можете просматривать историю покупок и отслеживать статус заказа.</p>
                    </div>
                    <div class="account-hero-icon">
                        <img src="images/profile.svg" alt="Личный кабинет">
                    </div>
                </div>
            </div>
        </section>

        <section class="account-main-section">
            <div class="container">
                <div class="account-layout">
                    <aside class="account-user-card">
                        <div class="account-user-top">
                            <div class="account-avatar">
                                <img src="images/profile.svg" alt="Профиль">
                            </div>
                            <div>
                                <h2><?= htmlspecialchars($user['name']) ?></h2>
                                <p><?= ($_SESSION['user_role'] == 2) ? 'Администратор' : 'Покупатель'; ?></p>
                            </div>
                        </div>

                        <div class="account-user-info">
                            <h3>E-mail</h3>
                            <p><?= htmlspecialchars($user['email']) ?></p>
                        </div>

                        <div class="account-user-info">
                            <h3>Телефон</h3>
                            <p><?= htmlspecialchars($user['phone'] ?: 'Не указан') ?></p>
                        </div>

                        <a class="account-edit-btn" href="actions/logout.php">Выйти</a>
                    </aside>

                    <div class="account-content-area">
                        <div class="account-stats-card">
                            <div class="account-stat-item">
                                <b><?= (int)$orders_stats['count_orders'] ?></b>
                                <span>Заказа</span>
                            </div>
                            <div class="account-stat-item">
                                <b data-account-favorites-count><?= (int)$favorites_stats['count_favorites'] ?></b>
                                <span>В избранном</span>
                            </div>
                            <div class="account-stat-item account-stat-wide">
                                <b><?= number_format($orders_stats['total_sum'], 0, '', ' ') ?> ₽</b>
                                <span>Сумма покупок</span>
                            </div>
                            <a href="catalog.php" class="account-catalog-btn">В каталог</a>
                        </div>

                        <section class="account-orders-card">
                            <h2>Мои заказы</h2>
                            <p>Последние покупки и текущий статус доставки</p>

                            <?php if (!empty($_SESSION['checkout_success'])): ?>
                                <div class="empty-account-message" style="margin-bottom:20px;">
                                    <?= htmlspecialchars($_SESSION['checkout_success']) ?>
                                </div>
                                <?php unset($_SESSION['checkout_success']); ?>
                            <?php endif; ?>

                            <div class="orders-table">
                                <div class="orders-head">
                                    <span>№ заказа</span>
                                    <span>Дата</span>
                                    <span>Товар</span>
                                    <span>Сумма</span>
                                    <span>Статус</span>
                                </div>

                                <?php if (mysqli_num_rows($orders_result) > 0): ?>
                                    <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>
                                        <div class="order-row">
                                            <span class="order-number">
                                                #<?= $order['id_order'] ?>
                                            </span>
                                            <span>
                                                <?= date('d.m.Y', strtotime($order['order_date'])) ?>
                                            </span>
                                            <span class="order-products">
                                                <?= htmlspecialchars($order['order_products']) ?>
                                            </span>
                                            <span class="order-price">
                                                <?= number_format($order['total_amount'], 0, '', ' ') ?> ₽
                                            </span>
                                            <span class="order-status">
                                                <?= htmlspecialchars($order['status']) ?>
                                            </span>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="empty-account-message">
                                        У вас пока нет заказов.
                                    </div>
                                <?php endif; ?>

                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>

        <section class="account-favorites-section">
            <div class="container">
                <div class="account-favorites-card">
                    <h2>Избранные растения</h2>

                    <?php if (mysqli_num_rows($favorites_result) > 0): ?>

                        <div class="favorite-plants-grid">

                            <?php while ($plant = mysqli_fetch_assoc($favorites_result)): ?>

                                <article class="favorite-plant-item" data-favorite-item>

                                    <img 
                                        src="images/<?= htmlspecialchars($plant['image']) ?>" 
                                        alt="<?= htmlspecialchars($plant['plant_name']) ?>"
                                    >

                                    <div class="favorite-info">
                                        <h3><?= htmlspecialchars($plant['plant_name']) ?></h3>

                                        <p><?= htmlspecialchars($plant['description']) ?></p>

                                        <div class="favorite-bottom favorite-bottom--account">
                                            <b><?= number_format($plant['price'], 0, '', ' ') ?> ₽</b>

                                            <div class="favorite-actions">

                                                <button
                                                    class="catalog-cart-btn"
                                                    type="button"
                                                    data-plant-id="<?= (int)$plant['id_plant'] ?>"
                                                >
                                                    В корзину
                                                </button>

                                                <button
                                                    class="favorite-remove-btn"
                                                    type="button"
                                                    data-plant-id="<?= (int)$plant['id_plant'] ?>"
                                                >
                                                    Удалить
                                                </button>

                                            </div>
                                        </div>
                                    </div>

                                </article>

                            <?php endwhile; ?>

                        </div>

                    <?php else: ?>

                        <div class="empty-account-message">
                            Вы пока не добавили растения в избранное.
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </section>
    </main>

    
<?php include 'includes/footer.php'; ?>
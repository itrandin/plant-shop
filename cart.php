<?php
require_once 'config/config.php';
requireLogin();

$user_id = getCurrentUserId();

$user_query = mysqli_prepare($conn, "SELECT name, phone FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($user_query, "i", $user_id);
mysqli_stmt_execute($user_query);
$user_data = mysqli_fetch_assoc(mysqli_stmt_get_result($user_query));

$cart_query = mysqli_prepare($conn, "
    SELECT 
        c.id_cart,
        c.quantity,
        p.id_plant,
        p.plant_name,
        p.description,
        p.price,
        p.image
    FROM cart c
    INNER JOIN plants p ON c.id_plant = p.id_plant
    WHERE c.id_user = ?
    ORDER BY c.date_added DESC
");

mysqli_stmt_bind_param($cart_query, "i", $user_id);
mysqli_stmt_execute($cart_query);
$cart_result = mysqli_stmt_get_result($cart_query);

$cart_items = [];
$cart_total = 0;
$cart_count = 0;

while ($item = mysqli_fetch_assoc($cart_result)) {
    $item['item_total'] = (float)$item['price'] * (int)$item['quantity'];
    $cart_total += $item['item_total'];
    $cart_count += (int)$item['quantity'];
    $cart_items[] = $item;
}

$delivery_price = $cart_count > 0 ? 350 : 0;
$final_total = $cart_total + $delivery_price;

function productWord($count) {
    $lastDigit = $count % 10;
    $lastTwoDigits = $count % 100;

    if ($lastDigit === 1 && $lastTwoDigits !== 11) {
        return 'товар';
    }

    if (in_array($lastDigit, [2, 3, 4], true) && !in_array($lastTwoDigits, [12, 13, 14], true)) {
        return 'товара';
    }

    return 'товаров';
}

include 'includes/header.php';
?>

    <main>
        <section class="cart-hero-section">
            <div class="container">
                <div class="cart-hero-box">
                    <div>
                        <h1>Корзина</h1>
                        <h2>Проверьте выбранные растения и оформите доставку</h2>
                        <p>На этой странице можно изменить количество товаров, указать адрес доставки и подготовить заказ к оформлению.</p>
                    </div>
                    <div class="cart-hero-icon">
                        <img src="images/cart_plant.svg" alt="Корзина">
                    </div>
                </div>
            </div>
        </section>

        <section class="cart-main-section">
            <div class="container">
                <?php if (!empty($_SESSION['cart_error'])): ?>
                    <div class="empty-account-message" style="margin-bottom:24px;">
                        <?= htmlspecialchars($_SESSION['cart_error']) ?>
                    </div>
                    <?php unset($_SESSION['cart_error']); ?>
                <?php endif; ?>

                <div class="cart-layout">
                    <div class="cart-products-card">
                        <div class="cart-section-head">
                            <h2>Товары в корзине</h2>
                            <span data-cart-count-text><?= (int)$cart_count ?> <?= productWord((int)$cart_count) ?></span>
                        </div>

                        <div data-cart-list>
                            <?php if (!empty($cart_items)): ?>

                                <?php foreach ($cart_items as $index => $item): ?>
                                    <article class="cart-product-item <?= $index % 2 ? 'cart-product-green' : '' ?>" data-cart-id="<?= (int)$item['id_cart'] ?>">
                                        <img 
                                            src="images/<?= htmlspecialchars($item['image']) ?>" 
                                            alt="<?= htmlspecialchars($item['plant_name']) ?>"
                                        >

                                        <div class="cart-product-info">
                                            <h3><?= htmlspecialchars($item['plant_name']) ?></h3>
                                            <p><?= htmlspecialchars($item['description']) ?></p>
                                            <b><?= number_format($item['price'], 0, '', ' ') ?> ₽</b>
                                        </div>

                                        <div class="cart-counter">
                                            <button class="cart-qty-btn" type="button" data-action="minus" data-cart-id="<?= (int)$item['id_cart'] ?>">−</button>
                                            <span data-cart-quantity><?= (int)$item['quantity'] ?></span>
                                            <button class="cart-qty-btn" type="button" data-action="plus" data-cart-id="<?= (int)$item['id_cart'] ?>">+</button>
                                        </div>

                                        <strong data-item-total><?= number_format($item['item_total'], 0, '', ' ') ?> ₽</strong>

                                        <button class="cart-remove" type="button" data-cart-id="<?= (int)$item['id_cart'] ?>">×</button>
                                    </article>
                                <?php endforeach; ?>

                            <?php else: ?>

                                <div class="empty-account-message">
                                    Ваша корзина пока пуста.
                                </div>

                            <?php endif; ?>
                        </div>

                        <a class="cart-back-link" href="catalog.php">← Продолжить покупки</a>
                    </div>

                    <aside class="cart-order-card">
                        <h2>Оформление заказа</h2>
                        <p>Заполните данные получателя и адрес доставки.</p>

                        <form class="cart-order-form" action="actions/checkout_action.php" method="POST">
                            <div class="cart-form-row">
                                <label>
                                    <span>Имя получателя</span>
                                    <input type="text" name="name" placeholder="Введите имя" value="<?= htmlspecialchars($user_data['name'] ?? '') ?>" required>
                                </label>
                                <label>
                                    <span>Телефон</span>
                                    <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" value="<?= htmlspecialchars($user_data['phone'] ?? '') ?>" required>
                                </label>
                            </div>

                            <label>
                                <span>Город</span>
                                <input type="text" name="city" placeholder="Введите город" required>
                            </label>

                            <label>
                                <span>Улица</span>
                                <input type="text" name="street" placeholder="Введите улицу" required>
                            </label>

                            <div class="cart-form-row cart-address-row">
                                <label>
                                    <span>Дом</span>
                                    <input type="text" name="house" placeholder="Дом" required>
                                </label>
                                <label>
                                    <span>Квартира</span>
                                    <input type="text" name="apartment" placeholder="Квартира">
                                </label>
                            </div>

                            <label>
                                <span>Комментарий к заказу</span>
                                <textarea name="comment" placeholder="Например: позвонить за 30 минут до доставки"></textarea>
                            </label>

                            <div class="cart-payment-box">
                                <h3>Способ оплаты</h3>
                                <label class="cart-radio">
                                    <input type="radio" name="payment" value="online" checked>
                                    <span>Оплата картой онлайн</span>
                                </label>
                                <label class="cart-radio">
                                    <input type="radio" name="payment" value="cash">
                                    <span>Оплата при получении</span>
                                </label>
                            </div>

                            <div class="cart-total-box">
                                <div>
                                    <span>Товары</span>
                                    <b data-cart-total><?= number_format($cart_total, 0, '', ' ') ?> ₽</b>
                                </div>
                                <div>
                                    <span>Доставка</span>
                                    <b data-delivery-price><?= number_format($delivery_price, 0, '', ' ') ?> ₽</b>
                                </div>
                                <div class="cart-total-final">
                                    <span>Итого</span>
                                    <b data-final-total><?= number_format($final_total, 0, '', ' ') ?> ₽</b>
                                </div>
                            </div>

                            <button class="cart-submit" type="submit" <?= empty($cart_items) ? 'disabled' : '' ?>>Оформить заказ</button>
                        </form>
                    </aside>
                </div>
            </div>
        </section>

        <section class="cart-info-section">
            <div class="container">
                <div class="cart-info-panel">
                    <div>
                        <h2>Мы бережно доставим растения по указанному адресу</h2>
                        <p>Перед отправкой растения аккуратно упаковываются и фиксируются в коробке, чтобы сохранить листья и горшок во время перевозки.</p>
                    </div>
                    <a href="delivery.php">Подробнее о доставке</a>
                </div>
            </div>
        </section>
    </main>

<?php include 'includes/footer.php'; ?>

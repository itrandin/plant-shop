<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зеленый уголок</title>
    <link rel="stylesheet" href="css/layout.css">
</head>
<body>
<div class="page page-home">
    <span class="decor decor-left"></span>
    <span class="decor decor-top"></span>
    <span class="decor decor-bottom"></span>

    <header class="header">
        <div class="container header__inner">

            <a class="logo" href="index.php" aria-label="Зеленый уголок">
                <img src="images/logo.svg" alt="Зеленый уголок" class="logo-full">
            </a>

            <nav class="nav">
                <a class="<?= $currentPage == 'index.php' ? 'active' : '' ?>" href="index.php">Главная</a>
                <a class="<?= $currentPage == 'catalog.php' ? 'active' : '' ?>" href="catalog.php">Каталог</a>
                <a class="<?= $currentPage == 'care.php' ? 'active' : '' ?>" href="care.php">Уход</a>
                <a class="<?= $currentPage == 'delivery.php' ? 'active' : '' ?>" href="delivery.php">Доставка</a>
                <a class="<?= $currentPage == 'reviews.php' ? 'active' : '' ?>" href="reviews.php">Отзывы</a>
                <a class="<?= $currentPage == 'contacts.php' ? 'active' : '' ?>" href="contacts.php">Контакты</a>
            </nav>

            <div class="header-icons">

                <?php if (isLoggedIn()): ?>

                    <div class="profile-menu">
                        <button class="icon-link profile-menu__btn" type="button" aria-label="Профиль">
                            <img src="images/profile.svg" alt="Профиль">
                        </button>

                        <div class="profile-menu__dropdown">
                            <div class="profile-menu__name">
                                <?= htmlspecialchars(getCurrentUserName()) ?>
                            </div>

                            <?php if (isAdmin()): ?>
                                <a href="admin.php">Админ-панель</a>
                            <?php endif; ?>

                            <a href="account.php">Личный кабинет</a>
                            <a href="actions/logout.php">Выйти</a>
                        </div>
                    </div>

                <?php else: ?>

                    <a class="icon-link" href="login.php" aria-label="Профиль">
                        <img src="images/profile.svg" alt="Профиль">
                    </a>

                <?php endif; ?>

                <?php
                $cartCount = 0;

                if (isLoggedIn()) {
                    $userId = getCurrentUserId();

                    $cartQuery = mysqli_prepare($conn, "
                        SELECT COALESCE(SUM(quantity), 0)
                        FROM cart
                        WHERE id_user = ?
                    ");

                    mysqli_stmt_bind_param($cartQuery, "i", $userId);
                    mysqli_stmt_execute($cartQuery);
                    mysqli_stmt_bind_result($cartQuery, $cartCount);
                    mysqli_stmt_fetch($cartQuery);
                    mysqli_stmt_close($cartQuery);
                }
                ?>

                <a class="icon-link icon-link--cart" href="cart.php" aria-label="Корзина">
                    <img src="images/cart.svg" alt="Корзина">

                    <span 
                        id="cart-counter" 
                        class="header-cart-counter"
                        <?= $cartCount == 0 ? 'style="display:none;"' : '' ?>
                    >
                        <?= (int)$cartCount ?>
                    </span>
                </a>

            </div>
            
        </div>
    </header>
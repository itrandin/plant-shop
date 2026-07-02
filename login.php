<?php
require_once 'config/config.php';
include 'includes/header.php';
?>


    <main>
        <section class="login-hero-section">
            <div class="container">
                <div class="login-hero-box">
                    <div class="login-hero-text">
                        <h1>Авторизация</h1>
                        <h2>Вход в личный кабинет покупателя</h2>
                        <p>Введите e-mail и пароль, чтобы перейти к заказам, избранным растениям и настройкам профиля.</p>
                    </div>
                    <div class="login-hero-icon">
                        <img src="images/profile-auth.svg" alt="Авторизация">
                    </div>
                </div>
            </div>
        </section>

        <section class="login-main-section">
            <div class="container">
                <div class="login-panel">
                    <aside class="login-info-card">
                        <div>
                            <h2>Ваш зеленый<br>кабинет</h2>
                            <p>Здесь можно смотреть заказы, сохранять растения и быстрее оформлять новые покупки.</p>
                        </div>
                        <img src="images/leaves.svg" alt="Листья">
                    </aside>

                    <form class="login-form" action="actions/login_action.php" method="POST">
                        <div class="login-form-head">
                            <h2>Вход в аккаунт</h2>
                            <p>Укажите данные, которые были введены при регистрации</p>
                        </div>

                        <?php if (!empty($_SESSION['login_error'])): ?>
                            <div class="form-message form-message--error">
                                <?= htmlspecialchars($_SESSION['login_error']) ?>
                            </div>
                            <?php unset($_SESSION['login_error']); ?>
                        <?php endif; ?>


                        <label class="auth-field">
                            <span>E-mail</span>
                            <div class="auth-input-wrap">
                                <img src="images/envelope.svg" alt="">
                                <input type="email" name="email" placeholder="Введите ваш e-mail" required autocomplete="email">
                            </div>
                        </label>

                        <label class="auth-field">
                            <span>Пароль</span>
                            <div class="auth-input-wrap">
                                <img src="images/lock.svg" alt="">
                                <input type="password" name="password" placeholder="Введите пароль" required autocomplete="current-password">
                            </div>
                        </label>

                        <div class="login-options">
                            <label class="remember-check">
                                <input type="checkbox" checked>
                                <span>Запомнить меня</span>
                            </label>
                            <a href="#">Забыли пароль?</a>
                        </div>

                        <button class="login-submit" type="submit">Войти</button>

                        <p class="login-register-link">
                            Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
                        </p>
                    </form>
                </div>
            </div>
        </section>

        <section class="login-benefits-section">
            <div class="container login-benefits-grid">
                <article class="login-benefit-card">
                    <img src="images/story.svg" alt="История заказов">
                    <div>
                        <h3>История заказов</h3>
                        <p>Все покупки в одном месте</p>
                    </div>
                </article>

                <article class="login-benefit-card">
                    <img src="images/favoritesV2.svg" alt="Избранные растения">
                    <div>
                        <h3>Избранные растения</h3>
                        <p>Быстрый доступ к товарам</p>
                    </div>
                </article>

                <article class="login-benefit-card">
                    <img src="images/profileV2.svg" alt="Личные данные">
                    <div>
                        <h3>Личные данные</h3>
                        <p>Редактирование профиля</p>
                    </div>
                </article>
            </div>
        </section>
    </main>

    
<?php include 'includes/footer.php'; ?>
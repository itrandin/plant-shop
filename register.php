<?php
require_once 'config/config.php';
include 'includes/header.php';
?>

    <main>
        <section class="register-hero-section">
            <div class="container">
                <div class="register-hero-box">
                    <div class="register-hero-text">
                        <h1>Регистрация</h1>
                        <h2>Создайте аккаунт для покупок и управления заказами</h2>
                        <p>После регистрации пользователь сможет добавлять растения в избранное, просматривать историю заказов и редактировать личные данные.</p>
                    </div>
                    <div class="register-hero-icon">
                        <img src="images/reg.svg" alt="Регистрация">
                    </div>
                </div>
            </div>
        </section>

        <section class="register-main-section">
            <div class="container">
                <div class="register-panel">
                    <aside class="register-info-card">
                        <div>
                            <h2>Новый аккаунт для<br>ухода и покупок</h2>
                            <p>Создайте профиль, чтобы быстрее оформлять заказы, сохранять понравившиеся растения и получать доступ к личному кабинету.</p>
                        </div>
                        <img src="images/leaves.svg" alt="Листья">
                    </aside>

                    <form class="register-form" action="actions/register_action.php" method="POST">
                        <div class="register-form-head">
                            <h2>Создание аккаунта</h2>
                            <p>Заполните данные для регистрации в магазине</p>
                        </div>

                        <label class="register-field">
                            <span>Имя пользователя</span>
                            <div class="register-input-wrap">
                                <img src="images/profileV2.svg" alt="">
                                <input
                                    type="text"
                                    name="name"
                                    placeholder="Введите ваше имя"
                                    required
                                >
                            </div>
                        </label>

                        <label class="register-field">
                            <span>E-mail</span>
                            <div class="register-input-wrap">
                                <img src="images/envelope.svg" alt="">
                                <input
                                    type="email"
                                    name="email"
                                    placeholder="Введите ваш e-mail"
                                    required
                                >
                            </div>
                        </label>

                        <label class="register-field">
                            <span>Телефон</span>
                            <div class="register-input-wrap">
                                <img src="images/phone.svg" alt="">
                                <input
                                    type="tel"
                                    name="phone"
                                    value="+7"
                                    pattern="^\+7\d{10}$"
                                    placeholder="+79991234567"
                                    required
                                >
                            </div>
                        </label>

                        <div class="register-password-grid">
                            <label class="register-field">
                                <span>Пароль</span>
                                <div class="register-input-wrap register-input-wrap--small">
                                    <img src="images/lock.svg" alt="">
                                    <input
                                        type="password"
                                        name="password"
                                        placeholder="Введите пароль"
                                        required
                                    >
                                </div>
                            </label>

                            <label class="register-field">
                                <span>Повтор пароля</span>
                                <div class="register-input-wrap register-input-wrap--small">
                                    <img src="images/lock.svg" alt="">
                                    <input
                                        type="password"
                                        name="password_confirm"
                                        placeholder="Повторите пароль"
                                        required
                                    >
                                </div>
                            </label>
                        </div>

                        <label class="register-agree">
                            <input type="checkbox" checked>
                            <span>Согласен с обработкой персональных данных</span>
                        </label>

                        <button class="register-submit" type="submit">Зарегистрироваться</button>

                        <p class="register-login-link">
                            Уже есть аккаунт? <a href="login.php">Войти</a>
                        </p>
                    </form>
                </div>
            </div>
        </section>

        <section class="register-benefits-section">
            <div class="container register-benefits-grid">
                <article class="register-benefit-card">
                    <img src="images/story.svg" alt="История заказов">
                    <div>
                        <h3>История заказов</h3>
                        <p>Все покупки в одном месте</p>
                    </div>
                </article>

                <article class="register-benefit-card">
                    <img src="images/favoritesV2.svg" alt="Избранные растения">
                    <div>
                        <h3>Избранные растения</h3>
                        <p>Быстрый доступ к товарам</p>
                    </div>
                </article>

                <article class="register-benefit-card">
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
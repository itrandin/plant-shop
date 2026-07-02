<?php
require_once 'config/config.php';
include 'includes/header.php';

$contactSuccess = $_SESSION['contact_success'] ?? '';
$contactError = $_SESSION['contact_error'] ?? '';
$oldContact = $_SESSION['contact_old'] ?? [];

unset($_SESSION['contact_success'], $_SESSION['contact_error'], $_SESSION['contact_old']);
?>

    <main>
        <section class="contacts-hero-section">
            <div class="container">
                <div class="contacts-hero-box">
                    <h1>Контакты</h1>
                    <h2>Свяжитесь с нами удобным способом</h2>
                    <p>Мы всегда готовы помочь с выбором растений, оформлением заказа, доставкой и рекомендациями по уходу.</p>
                </div>
            </div>
        </section>

        <section class="contacts-info-section">
            <div class="container">
                <h2 class="contacts-title">Контактная информация</h2>

                <div class="contacts-info-grid">
                    <article class="contact-info-card">
                        <h3>Телефон</h3>
                        <a href="tel:+79000000000">+7 (900) 000-00-00</a>
                        <p>Консультации по заказам и уходу</p>
                    </article>

                    <article class="contact-info-card">
                        <h3>E-mail</h3>
                        <a href="mailto:info@greenroom.ru">info@greenroom.ru</a>
                        <p>Ответим в течение рабочего дня</p>
                    </article>

                    <article class="contact-info-card">
                        <h3>Режим работы</h3>
                        <p>Ежедневно 09:00–21:00</p>
                        <p>Доставка по согласованию</p>
                    </article>
                </div>

                <article class="contact-address-card">
                    <h3>Адрес магазина</h3>
                    <a href="https://yandex.ru/maps/213/moscow/house/nezhinskaya_ulitsa_7/Z04YcgBmQUEFQFtvfXtwc3hkYw==/?ll=37.476843%2C55.712477&z=17" target="_blank" rel="noopener">
                        г. Москва, ул. Нежинская, д. 7
                    </a>
                    <p>Самовывоз доступен после подтверждения заказа оператором.</p>
                </article>
            </div>
        </section>

        <section class="contacts-map-section">
            <div class="container">
                <h2 class="contacts-title">Как нас найти</h2>
                <div class="contacts-map">
                    <iframe
                        title="Карта расположения магазина Зеленый уголок"
                        src="https://yandex.ru/map-widget/v1/?um=constructor%3A50aa9ba766d927de560af0e2b75e509feda75af2e074e646363338286cd14356&amp;source=constructor"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </section>

        <section class="contacts-form-section" id="contact-form">
            <div class="container">
                <h2 class="contacts-title">Форма обратной связи</h2>

                <?php if ($contactSuccess): ?>
                    <div class="contact-form-message contact-form-message--success">
                        <?= h($contactSuccess) ?>
                    </div>
                <?php endif; ?>

                <?php if ($contactError): ?>
                    <div class="contact-form-message contact-form-message--error">
                        <?= h($contactError) ?>
                    </div>
                <?php endif; ?>

                <form class="contacts-form" action="actions/contact_action.php" method="POST">
                    <div class="contacts-form-row">
                        <input
                            type="text"
                            name="name"
                            placeholder="Ваше имя"
                            value="<?= h($oldContact['name'] ?? getCurrentUserName() ?? '') ?>"
                            required
                        >

                        <input
                            type="email"
                            name="email"
                            placeholder="Электронная почта"
                            value="<?= h($oldContact['email'] ?? '') ?>"
                            required
                        >
                    </div>

                    <textarea
                        name="message"
                        placeholder="Сообщение"
                        required
                    ><?= h($oldContact['message'] ?? '') ?></textarea>

                    <button class="btn contacts-submit" type="submit">Отправить</button>
                </form>
            </div>
        </section>
    </main>

<?php include 'includes/footer.php'; ?>

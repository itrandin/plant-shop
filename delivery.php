<?php
require_once 'config/config.php';
include 'includes/header.php';
?>

    <main>
        <section class="delivery-hero-section">
            <div class="container">
                <div class="delivery-hero-box">
                    <div class="delivery-hero-text">
                        <h1>Доставка</h1>
                        <h2>Бережная доставка комнатных растений по городу</h2>
                        <p>Мы аккуратно упаковываем растения, подбираем подходящий способ перевозки и доставляем заказ в удобное для покупателя время.</p>
                        <a class="btn" href="cart.php">Оформить заказ</a>
                    </div>
                    <img class="delivery-hero-icon" src="images/icon-delivery.svg" alt="Доставка растений">
                </div>
            </div>
        </section>

        <section class="delivery-section delivery-methods-section">
            <div class="container">
                <div class="delivery-section-head">
                    <h2>Способы доставки</h2>
                    <p>Выберите удобный вариант получения заказа</p>
                </div>

                <div class="delivery-cards delivery-cards--three">
                    <article class="delivery-info-card">
                        <img src="images/icon-delivery.svg" alt="Курьерская доставка">
                        <div>
                            <h3>Курьерская доставка</h3>
                            <p>Доставка по городу осуществляется курьером. Растения перевозятся аккуратно, с учетом их устойчивости и защиты от повреждений.</p>
                        </div>
                    </article>

                    <article class="delivery-info-card">
                        <img src="images/icon-stock.svg" alt="Самовывоз">
                        <div>
                            <h3>Самовывоз</h3>
                            <p>Вы можете самостоятельно забрать заказ в согласованное время. Это удобный вариант, если хотите получить растения в ближайший день.</p>
                        </div>
                    </article>

                    <article class="delivery-info-card">
                        <img src="images/icon-package.svg" alt="Безопасная упаковка">
                        <div>
                            <h3>Безопасная упаковка</h3>
                            <p>Для каждого растения подбирается подходящая упаковка: защитная бумага, фиксация горшка и дополнительная защита листьев.</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="delivery-section delivery-steps-section">
            <div class="container">
                <div class="delivery-section-head">
                    <h2>Как проходит доставка</h2>
                    <p>Пошаговый путь заказа от оформления до получения</p>
                </div>

                <div class="delivery-steps-grid">
                    <article class="delivery-step-card">
                        <span>1</span>
                        <h3>Оформление</h3>
                        <p>Покупатель выбирает растения в каталоге, добавляет их в корзину и указывает контактные данные.</p>
                    </article>

                    <article class="delivery-step-card">
                        <span>2</span>
                        <h3>Подтверждение</h3>
                        <p>Менеджер уточняет состав заказа, адрес доставки, желаемое время получения и способ оплаты.</p>
                    </article>

                    <article class="delivery-step-card">
                        <span>3</span>
                        <h3>Подготовка</h3>
                        <p>Растения осматриваются, при необходимости поливаются и аккуратно упаковываются для перевозки.</p>
                    </article>

                    <article class="delivery-step-card">
                        <span>4</span>
                        <h3>Получение</h3>
                        <p>Курьер доставляет заказ по адресу или покупатель забирает его самовывозом в согласованное время.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="delivery-package-section">
            <div class="container">
                <div class="delivery-package-box">
                    <div class="delivery-package-text">
                        <h2>Безопасная упаковка растений</h2>
                        <p>Мы учитываем особенности каждого растения: фиксируем горшок, защищаем листья и используем упаковочные материалы, которые помогают сохранить растение в хорошем состоянии при перевозке.</p>
                        <div class="delivery-package-tags">
                            <span><img src="images/fixation.svg" alt="Фиксация горшка">Фиксация горшка</span>
                            <span><img src="images/protection.svg" alt="Защита листьев">Защита листьев</span>
                            <span><img src="images/carefully.svg" alt="Бережная перевозка">Бережная перевозка</span>
                        </div>
                    </div>
                    <img class="delivery-package-icon" src="images/icon-package.svg" alt="Упаковка растений">
                </div>
            </div>
        </section>

        <section class="delivery-section payment-section">
            <div class="container">
                <div class="delivery-section-head">
                    <h2>Оплата и условия</h2>
                    <p>Информация о способах оплаты и времени доставки</p>
                </div>

                <div class="delivery-cards delivery-cards--three">
                    <article class="delivery-info-card payment-card">
                        <img src="images/card.svg" alt="Оплата картой">
                        <div>
                            <h3>Оплата картой</h3>
                            <p>Заказ можно оплатить банковской картой при оформлении на сайте или после подтверждения заказа менеджером.</p>
                        </div>
                    </article>

                    <article class="delivery-info-card payment-card">
                        <img src="images/wallet.svg" alt="Оплата при получении">
                        <div>
                            <h3>Оплата при получении</h3>
                            <p>Для некоторых заказов доступна оплата при получении. Возможность этого способа уточняется при подтверждении заказа.</p>
                        </div>
                    </article>

                    <article class="delivery-info-card payment-card">
                        <img src="images/clock.svg" alt="Сроки доставки">
                        <div>
                            <h3>Сроки доставки</h3>
                            <p>Обычно доставка выполняется в течение 1–2 дней после подтверждения заказа. Точное время зависит от адреса и загруженности.</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="delivery-faq-section">
            <div class="container">
                <div class="delivery-faq-box">
                    <h2>Часто задаваемые вопросы</h2>
                    <div class="delivery-faq-grid">
                        <div class="delivery-faq-item">
                            <h3>Можно ли выбрать удобное время доставки?</h3>
                            <p>Да, при подтверждении заказа менеджер уточняет желаемый интервал времени и старается подобрать удобный вариант доставки.</p>
                        </div>
                        <div class="delivery-faq-item">
                            <h3>Можно ли заказать доставку в подарок?</h3>
                            <p>Да, можно оформить заказ как подарок. По запросу можно добавить аккуратную упаковку и сопроводительную открытку.</p>
                        </div>
                        <div class="delivery-faq-item">
                            <h3>Что делать, если растение повредилось?</h3>
                            <p>Если при получении вы заметили повреждение, свяжитесь с магазином. Мы рассмотрим ситуацию и предложим решение.</p>
                        </div>
                        <div class="delivery-faq-item">
                            <h3>Есть ли самовывоз в день заказа?</h3>
                            <p>Если нужный товар есть в наличии, самовывоз может быть доступен уже в день оформления. Это уточняется при подтверждении.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="delivery-cta-section">
            <div class="container">
                <div class="delivery-cta-box">
                    <h2>Оформите заказ, и мы бережно доставим растения к вам домой или в офис</h2>
                    <a class="btn" href="catalog.php">Перейти в каталог</a>
                </div>
            </div>
        </section>
    </main>

    
<?php include 'includes/footer.php'; ?>
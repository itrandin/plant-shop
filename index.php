<?php
require_once 'config/config.php';
include 'includes/header.php';
?>


    <main>
        <section class="hero">
            <div class="container">
                <div class="hero__card">
                    <div class="hero__content">
                        <h1>Зеленый уголок</h1>
                        <h2>Комнатные растения,<br>которые делают дом уютнее</h2>
                        <p>Выбирайте растения на ваш вкус для домашнего озеленения. Получайте рекомендации по уходу бесплатно и оформляйте заказ онлайн.</p>
                        <div class="hero__buttons">
                            <a class="btn" href="catalog.php">Перейти в каталог</a>
                            <a class="btn btn--outline" href="care.php">Узнать об уходе</a>
                        </div>
                    </div>
                    <div class="hero__image">
                        <img src="images/hero.jpg" alt="Комнатные растения в интерьере">
                    </div>
                </div>
            </div>
        </section>

        <section class="new-products">
            <div class="container">
                <div class="section-header">
                    <h2>Новинки</h2>
                    <a href="catalog.php">Смотреть все</a>
                </div>

                <div class="product-grid">

                    <article class="product-card">
                        <img src="images/monstera.png" alt="Монстера делициоза">
                        <h3>Монстера делициоза</h3>
                        <p>Крупное декоративное растение</p>

                        <div>
                            <b>2 490 ₽</b>

                            <button class="product-cart catalog-cart-btn" data-plant-id="1" type="button">
                                <img src="images/cartV2.svg" alt="Добавить в корзину">
                            </button>

                        </div>
                    </article>

                    <article class="product-card">
                        <img src="images/ficus.png" alt="Фикус эластика">
                        <h3>Фикус эластика</h3>
                        <p>Неприхотливое растение для дома</p>

                        <div>
                            <b>1 890 ₽</b>

                            <button class="product-cart catalog-cart-btn" data-plant-id="2" type="button">
                                <img src="images/cartV2.svg" alt="Добавить в корзину">
                            </button>

                        </div>
                    </article>

                    <article class="product-card">
                        <img src="images/zamioculcas.png" alt="Замиокулькас">
                        <h3>Замиокулькас</h3>
                        <p>Идеален для начинающих</p>

                        <div>
                            <b>1 690 ₽</b>

                            <button class="product-cart catalog-cart-btn" data-plant-id="3" type="button">
                                <img src="images/cartV2.svg" alt="Добавить в корзину">
                            </button>

                        </div>
                    </article>

                    <article class="product-card">
                        <img src="images/sansevieria.png" alt="Сансевиерия">
                        <h3>Сансевиерия</h3>
                        <p>Стильное растение для интерьера</p>

                        <div>
                            <b>1 390 ₽</b>

                            <button class="product-cart catalog-cart-btn" data-plant-id="4" type="button">
                                <img src="images/cartV2.svg" alt="Добавить в корзину">
                            </button>

                        </div>
                    </article>

                </div>

            </div>
        </section>

        <section class="features">
            <div class="container features__grid">
                <article><img src="images/icon-delivery.svg" alt=""><div><h3>Быстрая доставка</h3><p>Доставляем растения аккуратно</p></div></article>
                <article><img src="images/icon-care.svg" alt=""><div><h3>Рекомендации по уходу</h3><p>Подсказки для каждого растения</p></div></article>
                <article><img src="images/icon-stock.svg" alt=""><div><h3>Растения в наличии</h3><p>Актуальный каталог товаров</p></div></article>
                <article><img src="images/icon-package.svg" alt=""><div><h3>Безопасная упаковка</h3><p>Защита растений при доставке</p></div></article>
            </div>
        </section>

        <section class="bottom-info">
            <div class="container bottom-info__grid">

                <article class="care-box">
                    <div class="care-box-content">
                        <h2>Уход за растениями</h2>
                        <p>
                            Полезные рекомендации по поливу, освещению,
                            пересадке и удобрению комнатных растений.
                        </p>
                        <a class="btn" href="care.php">Перейти к уходу</a>
                    </div>
                    <div class="care-box-image">
                        <img src="images/icon-care-V2.svg" alt="Уход за растениями">
                    </div>
                </article>

                <article class="reviews-box">
                    <h2>Отзывы покупателей</h2>
                    <p>Более 1000 растений доставлено покупателям. Свыше 400 отзывов о качестве растений и сервиса.</p>
                    <a href="reviews.php">Перейти к отзывам</a>
                </article>
            </div>
        </section>
    </main>

    
<?php include 'includes/footer.php'; ?>
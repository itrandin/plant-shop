<?php
require_once 'config/config.php';
include 'includes/header.php';
?>

    <main>
        <section class="care-hero-section">
            <div class="container">
                <div class="care-hero-box">
                    <div class="care-hero-text">
                        <h1>Уход за растениями</h1>
                        <h2>Подробные рекомендации для каждого растения</h2>
                        <p>На странице собраны сведения по поливу, освещению, температуре, пересадке, грунту и удобрению для всех растений, представленных в каталоге магазина.</p>
                    </div>
                    <img class="care-hero-icon" src="images/icon-care-page.svg" alt="Уход за растениями">
                </div>
            </div>
        </section>

        <section class="care-search-section">
            <div class="container">
                <div class="care-search-panel">
                    <label class="care-search">
                        <img src="images/search.svg" alt="Поиск">
                        <input type="text" id="care-search-input" placeholder="Поиск рекомендаций" autocomplete="off">
                    </label>
                </div>
            </div>
        </section>

        <section class="care-list-section">
            <div class="container care-list">

                <article class="care-plant-card care-plant-card--white">
                    <div class="care-plant-image"><img src="images/sansevieria.png" alt="Сансевиерия"></div>
                    <div class="care-plant-content">
                        <h2>Сансевиерия</h2>
                        <span class="care-tag">Легкий уход</span>
                        <p class="care-desc">Неприхотливое комнатное растение с плотными вертикальными листьями. Хорошо подходит для дома и офиса, спокойно переносит редкий полив и разный уровень освещения.</p>
                        <div class="care-tips">
                            <div class="care-tip"><img src="images/water.svg" alt="Полив"><div><h3>Полив</h3><p>Поливать умеренно, после подсыхания верхнего слоя грунта. Зимой полив сокращают.</p></div></div>
                            <div class="care-tip"><img src="images/sun.svg" alt="Освещение"><div><h3>Освещение</h3><p>Переносит яркий свет и полутень. При хорошем освещении листья становятся более яркими.</p></div></div>
                            <div class="care-tip"><img src="images/temperature.svg" alt="Температура"><div><h3>Температура</h3><p>Комфортная температура — от 18 до 27 °C. Не рекомендуется ставить на холодный сквозняк.</p></div></div>
                            <div class="care-tip"><img src="images/dirt.svg" alt="Пересадка и грунт"><div><h3>Пересадка и грунт</h3><p>Грунт должен быть легким и рыхлым. Пересадку проводят по мере роста корней.</p></div></div>
                            <div class="care-tip"><img src="images/fertilizer.svg" alt="Удобрение"><div><h3>Удобрение</h3><p>Подкармливать весной и летом 1 раз в месяц. Лучше использовать удобрения для декоративных растений.</p></div></div>
                        </div>
                        <button class="api-care-btn" type="button" data-plant="Sansevieria">
                            Получить данные из API
                        </button>
                        <div class="api-care-result" aria-live="polite"></div>
                    </div>
                </article>

                <article class="care-plant-card care-plant-card--green">
                    <div class="care-plant-image"><img src="images/zamioculcas.png" alt="Замиокулькас"></div>
                    <div class="care-plant-content">
                        <h2>Замиокулькас</h2>
                        <span class="care-tag">Для начинающих</span>
                        <p class="care-desc">Декоративное растение с плотными глянцевыми листьями. Хорошо смотрится в современном интерьере и подходит людям, которые не всегда могут часто ухаживать за растениями.</p>
                        <div class="care-tips">
                            <div class="care-tip"><img src="images/waterV2.svg" alt="Полив"><div><h3>Полив</h3><p>Полив редкий и аккуратный. Между поливами грунт должен хорошо просохнуть.</p></div></div>
                            <div class="care-tip"><img src="images/sunV2.svg" alt="Освещение"><div><h3>Освещение</h3><p>Лучше размещать на светлом месте без прямых солнечных лучей. Также может адаптироваться к умеренному свету.</p></div></div>
                            <div class="care-tip"><img src="images/temperatureV2.svg" alt="Температура"><div><h3>Температура</h3><p>Оптимальная температура — 20–25 °C. Растение плохо переносит переохлаждение.</p></div></div>
                            <div class="care-tip"><img src="images/dirtV2.svg" alt="Пересадка и грунт"><div><h3>Пересадка и грунт</h3><p>Подходит рыхлый грунт с дренажем. Пересадку проводят, когда корневой системе становится тесно.</p></div></div>
                            <div class="care-tip"><img src="images/fertilizerV2.svg" alt="Удобрение"><div><h3>Удобрение</h3><p>Удобрять можно с весны до осени 1 раз в 3–4 недели. Избыток подкормки нежелателен.</p></div></div>
                        </div>
                        <button class="api-care-btn" type="button" data-plant="Zamioculcas">
                            Получить дополнительные данные
                        </button>
                        <div class="api-care-result" aria-live="polite"></div>
                    </div>
                </article>

                <article class="care-plant-card care-plant-card--white">
                    <div class="care-plant-image"><img src="images/ficus.png" alt="Фикус эластика"></div>
                    <div class="care-plant-content">
                        <h2>Фикус эластика</h2>
                        <span class="care-tag">Рассеянный свет</span>
                        <p class="care-desc">Комнатное растение с крупными темно-зелеными листьями. Делает интерьер более стильным и живым, подходит для жилых комнат и рабочих помещений.</p>
                        <div class="care-tips">
                            <div class="care-tip"><img src="images/water.svg" alt="Полив"><div><h3>Полив</h3><p>Полив умеренный, после подсыхания верхнего слоя почвы. Лишнюю воду из поддона нужно сливать.</p></div></div>
                            <div class="care-tip"><img src="images/sun.svg" alt="Освещение"><div><h3>Освещение</h3><p>Любит яркий рассеянный свет. При недостатке света рост замедляется.</p></div></div>
                            <div class="care-tip"><img src="images/temperature.svg" alt="Температура"><div><h3>Температура</h3><p>Комфортная температура — 18–25 °C. Растение не любит резкие перепады температуры.</p></div></div>
                            <div class="care-tip"><img src="images/dirt.svg" alt="Пересадка и грунт"><div><h3>Пересадка и грунт</h3><p>Нужен питательный грунт с устойчивым дренажем. Молодые растения пересаживают чаще.</p></div></div>
                            <div class="care-tip"><img src="images/fertilizer.svg" alt="Удобрение"><div><h3>Удобрение</h3><p>Весной и летом растение подкармливают 1–2 раза в месяц. Подходят составы для декоративно-лиственных растений.</p></div></div>
                        </div>
                        <button class="api-care-btn" type="button" data-plant="Ficus elastica">
                            Получить дополнительные данные
                        </button>
                        <div class="api-care-result" aria-live="polite"></div>
                    </div>
                </article>

                <article class="care-plant-card care-plant-card--green">
                    <div class="care-plant-image"><img src="images/monstera.png" alt="Монстера делициоза"></div>
                    <div class="care-plant-content">
                        <h2>Монстера делициоза</h2>
                        <span class="care-tag">Крупное растение</span>
                        <p class="care-desc">Эффектное декоративное растение с крупными резными листьями. Хорошо подходит для просторных комнат, гостиных и офисов, создавая ощущение уюта и природной свежести.</p>
                        <div class="care-tips">
                            <div class="care-tip"><img src="images/waterV2.svg" alt="Полив"><div><h3>Полив</h3><p>Поливать нужно регулярно, но без переувлажнения. Верхний слой грунта должен немного просыхать.</p></div></div>
                            <div class="care-tip"><img src="images/sunV2.svg" alt="Освещение"><div><h3>Освещение</h3><p>Предпочитает яркий рассеянный свет. Прямые солнечные лучи могут оставлять ожоги на листьях.</p></div></div>
                            <div class="care-tip"><img src="images/temperatureV2.svg" alt="Температура"><div><h3>Температура</h3><p>Оптимальная температура — 20–28 °C. Для хорошего роста растению нужно достаточно свободного места.</p></div></div>
                            <div class="care-tip"><img src="images/dirtV2.svg" alt="Пересадка и грунт"><div><h3>Пересадка и грунт</h3><p>Подходит рыхлый питательный грунт с хорошим дренажем. По мере роста желательно использовать опору.</p></div></div>
                            <div class="care-tip"><img src="images/fertilizerV2.svg" alt="Удобрение"><div><h3>Удобрение</h3><p>С весны до осени удобряют 1 раз в 2–3 недели. Подкормки помогают формировать крупные здоровые листья.</p></div></div>
                        </div>
                        <button class="api-care-btn" type="button" data-plant="Monstera deliciosa">
                            Получить дополнительные данные
                        </button>
                        <div class="api-care-result" aria-live="polite"></div>
                    </div>
                </article>

                <article class="care-plant-card care-plant-card--white">
                    <div class="care-plant-image"><img src="images/spathiphyllum.png" alt="Спатифиллум"></div>
                    <div class="care-plant-content">
                        <h2>Спатифиллум</h2>
                        <span class="care-tag">Цветущее</span>
                        <p class="care-desc">Красивое цветущее комнатное растение с зелеными листьями и белыми соцветиями. Подходит для дома, так как выглядит аккуратно и делает пространство более уютным.</p>
                        <div class="care-tips">
                            <div class="care-tip"><img src="images/water.svg" alt="Полив"><div><h3>Полив</h3><p>Любит регулярный полив и умеренно влажный грунт. Нельзя допускать длительного пересыхания почвы.</p></div></div>
                            <div class="care-tip"><img src="images/sun.svg" alt="Освещение"><div><h3>Освещение</h3><p>Предпочитает рассеянный свет или легкую полутень. На прямом солнце листья могут получать ожоги.</p></div></div>
                            <div class="care-tip"><img src="images/temperature.svg" alt="Температура"><div><h3>Температура</h3><p>Комфортная температура — 18–26 °C. Растение любит стабильные условия без холодных сквозняков.</p></div></div>
                            <div class="care-tip"><img src="images/dirt.svg" alt="Пересадка и грунт"><div><h3>Пересадка и грунт</h3><p>Грунт должен быть питательным и влагоемким, но не плотным. Пересадку выполняют весной при необходимости.</p></div></div>
                            <div class="care-tip"><img src="images/fertilizer.svg" alt="Удобрение"><div><h3>Удобрение</h3><p>В период активного роста подкармливают 1 раз в 2–3 недели. Лучше использовать удобрения для цветущих растений.</p></div></div>
                        </div>
                        <button class="api-care-btn" type="button" data-plant="Spathiphyllum">
                            Получить дополнительные данные
                        </button>
                        <div class="api-care-result" aria-live="polite"></div>
                    </div>
                </article>

                <article class="care-plant-card care-plant-card--green">
                    <div class="care-plant-image"><img src="images/chlorophytum.png" alt="Хлорофитум"></div>
                    <div class="care-plant-content">
                        <h2>Хлорофитум</h2>
                        <span class="care-tag">Компактное</span>
                        <p class="care-desc">Компактное и простое в уходе растение, которое хорошо подходит для дома, кухни или рабочего стола. Быстро растет, легко адаптируется к разным условиям и не требует сложного ухода.</p>
                        <div class="care-tips">
                            <div class="care-tip"><img src="images/waterV2.svg" alt="Полив"><div><h3>Полив</h3><p>Полив регулярный, но умеренный. Летом растение нуждается чаще, зимой — реже.</p></div></div>
                            <div class="care-tip"><img src="images/sunV2.svg" alt="Освещение"><div><h3>Освещение</h3><p>Хорошо растет при ярком рассеянном свете, но может переносить и полутень. При достаточном освещении листья выглядят ярче.</p></div></div>
                            <div class="care-tip"><img src="images/temperatureV2.svg" alt="Температура"><div><h3>Температура</h3><p>Оптимальная температура — 18–25 °C. Растение достаточно выносливое, но не любит сильный холод.</p></div></div>
                            <div class="care-tip"><img src="images/dirtV2.svg" alt="Пересадка и грунт"><div><h3>Пересадка и грунт</h3><p>Предпочитает легкий грунт для комнатных растений. Пересадку проводят, когда куст сильно разрастается.</p></div></div>
                            <div class="care-tip"><img src="images/fertilizerV2.svg" alt="Удобрение"><div><h3>Удобрение</h3><p>Подкормки проводят весной и летом 1 раз в месяц. Можно использовать универсальные комнатные удобрения.</p></div></div>
                        </div>
                        <button class="api-care-btn" type="button" data-plant="Chlorophytum">
                            Получить дополнительные данные
                        </button>
                        <div class="api-care-result" aria-live="polite"></div>
                    </div>
                </article>

            </div>
        </section>
    </main>

    
<?php include 'includes/footer.php'; ?>
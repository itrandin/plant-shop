<?php
require_once 'config/config.php';

$isLogged = isLoggedIn();
$id_user = $isLogged ? (int)getCurrentUserId() : 0;

function renderStars($rating) {
    $rating = (int)$rating;
    $rating = max(1, min(5, $rating));
    return str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
}

function formatReviewDate($date) {
    if (!$date) {
        return '';
    }

    return date('d.m.Y', strtotime($date));
}

$stats = [
    'avg_rating' => 0,
    'reviews_count' => 0,
    'orders_count' => 0,
    'recommended_percent' => 0
];

$statsResult = mysqli_query($conn, "
    SELECT
        COALESCE(AVG(rating), 0) AS avg_rating,
        COUNT(*) AS reviews_count,
        SUM(CASE WHEN rating >= 4 THEN 1 ELSE 0 END) AS recommended_count
    FROM reviews
");

if ($statsResult) {
    $row = mysqli_fetch_assoc($statsResult);
    $stats['avg_rating'] = (float)$row['avg_rating'];
    $stats['reviews_count'] = (int)$row['reviews_count'];

    if ($stats['reviews_count'] > 0) {
        $stats['recommended_percent'] = round(((int)$row['recommended_count'] / $stats['reviews_count']) * 100);
    }
}

$ordersResult = mysqli_query($conn, "SELECT COUNT(*) AS orders_count FROM orders");

if ($ordersResult) {
    $row = mysqli_fetch_assoc($ordersResult);
    $stats['orders_count'] = (int)$row['orders_count'];
}

$avgDisplay = $stats['reviews_count'] > 0 ? number_format($stats['avg_rating'], 1, '.', '') : '0.0';
$reviewsCountDisplay = $stats['reviews_count'];
$ordersCountDisplay = $stats['orders_count'];
$recommendedDisplay = $stats['recommended_percent'];

$plantsForReview = [];

if ($isLogged) {
    $plantsStmt = mysqli_prepare($conn, "
        SELECT DISTINCT
            p.id_plant,
            p.plant_name
        FROM orders o
        INNER JOIN order_items oi ON oi.id_order = o.id_order
        INNER JOIN plants p ON p.id_plant = oi.id_plant
        WHERE o.id_user = ?
          AND o.status = 'Доставлен'
          AND NOT EXISTS (
              SELECT 1
              FROM reviews r
              WHERE r.id_user = ?
                AND r.id_plant = p.id_plant
          )
        ORDER BY p.plant_name
    ");

    mysqli_stmt_bind_param($plantsStmt, "ii", $id_user, $id_user);
    mysqli_stmt_execute($plantsStmt);
    $plantsResult = mysqli_stmt_get_result($plantsStmt);

    while ($plant = mysqli_fetch_assoc($plantsResult)) {
        $plantsForReview[] = $plant;
    }
}

$reviewsSql = "
    SELECT
        r.id_review,
        r.rating,
        r.comment,
        r.review_date,
        r.id_user,
        u.name AS user_name,
        p.plant_name
    FROM reviews r
    INNER JOIN users u ON u.id_user = r.id_user
    INNER JOIN plants p ON p.id_plant = r.id_plant
    ORDER BY r.review_date DESC, r.id_review DESC
";

$reviewsResult = mysqli_query($conn, $reviewsSql);
$reviews = [];

if ($reviewsResult) {
    while ($review = mysqli_fetch_assoc($reviewsResult)) {
        $reviews[] = $review;
    }
}

include 'includes/header.php';
?>

<main>
    <section class="reviews-hero-section">
        <div class="container">
            <div class="reviews-hero-box">
                <div class="reviews-hero-text">
                    <h1>Отзывы покупателей</h1>
                    <h2>Мнения клиентов о растениях, доставке и качестве обслуживания</h2>
                    <p>На этой странице собраны отзывы покупателей, которые уже заказали комнатные растения в магазине «Зеленый уголок».</p>
                </div>
                <img class="reviews-hero-icon" src="images/review.svg" alt="Отзывы покупателей">
            </div>
        </div>
    </section>

    <section class="reviews-stats-section">
        <div class="container">
            <div class="reviews-stats-grid">
                <article class="review-stat-card">
                    <b><?= $avgDisplay ?> / 5</b>
                    <h3>Средняя оценка</h3>
                    <p>Показатель рассчитывается автоматически на основе всех опубликованных отзывов.</p>
                </article>
                <article class="review-stat-card">
                    <b><?= (int)$reviewsCountDisplay ?></b>
                    <h3>Отзывов</h3>
                    <p>На сайте размещены реальные отзывы покупателей о заказанных растениях.</p>
                </article>
                <article class="review-stat-card">
                    <b><?= (int)$ordersCountDisplay ?></b>
                    <h3>Заказов</h3>
                    <p>Количество оформленных заказов автоматически берется из базы данных.</p>
                </article>
                <article class="review-stat-card">
                    <b><?= (int)$recommendedDisplay ?>%</b>
                    <h3>Рекомендуют</h3>
                    <p>Процент покупателей, которые поставили оценку 4 или 5 звезд.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="reviews-list-section">
        <div class="container">
            <div class="reviews-section-head reviews-section-head--tools">
                <div>
                    <h2>Последние отзывы</h2>
                    <p>Отзывы оформлены карточками, чтобы вы могли быстро оценить опыт других покупателей.</p>
                </div>

                <?php if (!empty($reviews)): ?>
                    <div class="reviews-tools">
                        <input id="reviews-search-input" type="text" placeholder="Поиск по отзывам">

                        <select id="reviews-sort-select" aria-label="Сортировка отзывов">
                            <option value="new">Сначала новые</option>
                            <option value="old">Сначала старые</option>
                            <option value="rating_desc">По рейтингу 5→1</option>
                            <option value="rating_asc">По рейтингу 1→5</option>
                            <option value="plant">По названию растения</option>
                        </select>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION['review_success'])): ?>
                <div class="review-alert review-alert--success">
                    <?= htmlspecialchars($_SESSION['review_success']) ?>
                </div>
                <?php unset($_SESSION['review_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['review_error'])): ?>
                <div class="review-alert review-alert--error">
                    <?= htmlspecialchars($_SESSION['review_error']) ?>
                </div>
                <?php unset($_SESSION['review_error']); ?>
            <?php endif; ?>

            <?php if (!empty($reviews)): ?>
                <div class="reviews-grid" id="reviews-grid">
                    <?php foreach ($reviews as $index => $review): ?>
                        <?php
                            $userName = $review['user_name'] ?: 'Пользователь';
                            $avatar = mb_substr($userName, 0, 1, 'UTF-8');
                            $cardClass = $index % 2 ? 'review-card review-card--green' : 'review-card';
                            $reviewTimestamp = strtotime($review['review_date']);
                            $searchText = mb_strtolower($userName . ' ' . $review['plant_name'] . ' ' . $review['comment'], 'UTF-8');
                        ?>

                        <article
                            class="<?= $cardClass ?>"
                            data-review-card
                            data-search="<?= htmlspecialchars($searchText) ?>"
                            data-rating="<?= (int)$review['rating'] ?>"
                            data-date="<?= (int)$reviewTimestamp ?>"
                            data-plant="<?= htmlspecialchars(mb_strtolower($review['plant_name'], 'UTF-8')) ?>"
                        >
                            <div class="review-card-top">
                                <span class="review-avatar"><?= htmlspecialchars(mb_strtoupper($avatar, 'UTF-8')) ?></span>
                                <div class="review-meta">
                                    <h3><?= htmlspecialchars($userName) ?></h3>
                                    <div class="review-line">
                                        <span><?= formatReviewDate($review['review_date']) ?></span>
                                        <span class="stars"><?= renderStars($review['rating']) ?></span>
                                        <span class="plant-badge"><?= htmlspecialchars($review['plant_name']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>

                            <?php if (isAdmin() || ($isLogged && (int)$review['id_user'] === $id_user)): ?>
                                <form class="review-delete-form" action="actions/review_delete.php" method="POST">
                                    <input type="hidden" name="id_review" value="<?= (int)$review['id_review'] ?>">
                                    <button class="review-delete-btn" type="submit">Удалить отзыв</button>
                                </form>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="empty-account-message reviews-empty-message" id="reviews-empty-message" style="display:none;">
                    По вашему запросу отзывы не найдены.
                </div>
            <?php else: ?>
                <div class="empty-account-message">
                    Пока нет отзывов. Первый отзыв появится здесь после покупки и доставки заказа.
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="reviews-rating-section">
        <div class="container">
            <div class="reviews-rating-box">
                <div>
                    <h2><span><?= $stats['reviews_count'] > 0 ? renderStars(round($stats['avg_rating'])) : '☆☆☆☆☆' ?></span> <?= $avgDisplay ?> из 5</h2>
                    <h3><?= (int)$recommendedDisplay ?>% покупателей рекомендуют магазин «Зеленый уголок» своим друзьям и знакомым.</h3>
                    <p>Чаще всего клиенты отмечают здоровый вид растений, бережную доставку и полезные советы по уходу.</p>
                </div>
                <a class="btn" href="catalog.php">В каталог</a>
            </div>
        </div>
    </section>

    <section class="review-form-section" id="review-form-section">
        <div class="container">
            <div class="review-form-box">
                <?php if (!$isLogged): ?>

                    <div class="review-login-message">
                        <h2>Оставить отзыв</h2>
                        <p class="rep">Чтобы оставить отзыв, необходимо авторизоваться.</p>
                        <a class="btn" href="login.php">Войти</a>
                    </div>

                <?php elseif (empty($plantsForReview)): ?>

                    <div class="review-login-message">
                        <h2>Оставить отзыв</h2>
                        <p>У вас пока нет доставленных заказов или все купленные растения уже оценены.</p>
                    </div>

                <?php else: ?>

                    <div class="review-form-head">
                        <div>
                            <h2>Оставить отзыв</h2>
                            <p>Выберите растение из доставленных заказов и поделитесь впечатлением о покупке.</p>
                        </div>
                    </div>

                    <form class="review-form" action="actions/review_add.php" method="POST">
                        <div class="review-form-row review-form-row--dynamic">
                            <select name="id_plant" required>
                                <option value="">Выберите растение</option>
                                <?php foreach ($plantsForReview as $plant): ?>
                                    <option value="<?= (int)$plant['id_plant'] ?>">
                                        <?= htmlspecialchars($plant['plant_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <select name="rating" required>
                                <option value="">Оценка</option>
                                <option value="5">★★★★★ — 5</option>
                                <option value="4">★★★★☆ — 4</option>
                                <option value="3">★★★☆☆ — 3</option>
                                <option value="2">★★☆☆☆ — 2</option>
                                <option value="1">★☆☆☆☆ — 1</option>
                            </select>
                        </div>

                        <textarea
                            name="comment"
                            required
                            placeholder="Текст отзыва&#10;Например: растение приехало в хорошем состоянии, упаковка была аккуратной, доставка быстрая..."
                        ></textarea>

                        <button class="btn review-submit-btn" type="submit">
                            Отправить отзыв
                        </button>
                    </form>

                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="reviews-cta-section">
        <div class="container">
            <div class="reviews-cta-box">
                <h2>Спасибо покупателям за доверие к магазину комнатных растений</h2>
                <a class="btn" href="catalog.php">Перейти в каталог</a>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

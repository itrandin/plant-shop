<?php
require_once 'config/config.php';

$user_id = isLoggedIn() ? getCurrentUserId() : 0;

$search = trim($_GET['q'] ?? '');
$sort = $_GET['sort'] ?? 'popular';
$selected_categories = array_filter(array_map('intval', $_GET['categories'] ?? []));
$selected_care_levels = array_filter(array_map('intval', $_GET['care_levels'] ?? []));

$allowed_sort = ['popular', 'price_asc', 'price_desc', 'name_asc'];
if (!in_array($sort, $allowed_sort, true)) {
    $sort = 'popular';
}

$categories_result = mysqli_query($conn, "SELECT id_category, category_name FROM categories ORDER BY id_category ASC");
$care_levels_result = mysqli_query($conn, "SELECT id_care_level, level_name FROM care_levels ORDER BY id_care_level ASC");

$where = [];

if ($search !== '') {
    $safeSearch = mysqli_real_escape_string($conn, $search);
    $where[] = "(p.plant_name LIKE '%$safeSearch%' OR p.description LIKE '%$safeSearch%')";
}

if (!empty($selected_categories)) {
    $categoryIds = implode(',', $selected_categories);
    $where[] = "p.id_category IN ($categoryIds)";
}

if (!empty($selected_care_levels)) {
    $careIds = implode(',', $selected_care_levels);
    $where[] = "p.id_care_level IN ($careIds)";
}

$orderBy = "p.popularity DESC, p.id_plant ASC";
if ($sort === 'price_asc') {
    $orderBy = "p.price ASC, p.id_plant ASC";
} elseif ($sort === 'price_desc') {
    $orderBy = "p.price DESC, p.id_plant ASC";
} elseif ($sort === 'name_asc') {
    $orderBy = "p.plant_name ASC";
}

$whereSql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$plants_sql = "
    SELECT 
        p.id_plant,
        p.plant_name,
        p.description,
        p.price,
        p.image,
        p.popularity,
        c.category_name,
        cl.level_name,
        f.id_favorite
    FROM plants p
    INNER JOIN categories c ON p.id_category = c.id_category
    INNER JOIN care_levels cl ON p.id_care_level = cl.id_care_level
    LEFT JOIN favorites f 
        ON p.id_plant = f.id_plant 
        AND f.id_user = $user_id
    $whereSql
    ORDER BY $orderBy
";

$plants_result = mysqli_query($conn, $plants_sql);

include 'includes/header.php';
?>

    <main>
        <section class="catalog-title-section">
            <div class="container">
                <h1>Каталог растений</h1>
                <p>Выберите комнатное растение для дома, офиса или подарка</p>
            </div>
        </section>

        <section class="catalog-tools-section">
            <div class="container">
                <form class="catalog-tools" method="GET" action="catalog.php">
                    <?php foreach ($selected_categories as $category_id): ?>
                        <input type="hidden" name="categories[]" value="<?= (int)$category_id ?>">
                    <?php endforeach; ?>

                    <?php foreach ($selected_care_levels as $care_id): ?>
                        <input type="hidden" name="care_levels[]" value="<?= (int)$care_id ?>">
                    <?php endforeach; ?>

                    <label class="catalog-search">
                        <img src="images/search.svg" alt="">
                        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Поиск растений">
                    </label>

                    <select class="catalog-sort" name="sort" onchange="this.form.submit()">
                        <option value="popular" <?= $sort === 'popular' ? 'selected' : '' ?>>Сортировка: по популярности</option>
                        <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Сортировка: по возрастанию цены</option>
                        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Сортировка: по убыванию цены</option>
                        <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Сортировка: по названию</option>
                    </select>
                </form>
            </div>
        </section>

        <section class="catalog-main-section">
            <div class="container catalog-main-grid">
                <aside class="catalog-sidebar">
                    <form method="GET" action="catalog.php">
                        <input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>">
                        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">

                        <h2>Фильтры</h2>

                        <div class="catalog-filter-block">
                            <h3>Категория</h3>
                            <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                                <label>
                                    <input 
                                        type="checkbox" 
                                        name="categories[]" 
                                        value="<?= (int)$category['id_category'] ?>"
                                        <?= in_array((int)$category['id_category'], $selected_categories, true) ? 'checked' : '' ?>
                                    >
                                    <span><?= htmlspecialchars($category['category_name']) ?></span>
                                </label>
                            <?php endwhile; ?>
                        </div>

                        <div class="catalog-filter-block">
                            <h3>Сложность ухода</h3>
                            <?php while ($care = mysqli_fetch_assoc($care_levels_result)): ?>
                                <label>
                                    <input 
                                        type="checkbox" 
                                        name="care_levels[]" 
                                        value="<?= (int)$care['id_care_level'] ?>"
                                        <?= in_array((int)$care['id_care_level'], $selected_care_levels, true) ? 'checked' : '' ?>
                                    >
                                    <span><?= htmlspecialchars($care['level_name']) ?></span>
                                </label>
                            <?php endwhile; ?>
                        </div>

                        <button class="catalog-apply" type="submit">Применить</button>
                        <a class="catalog-reset" href="catalog.php">Сбросить фильтры</a>
                    </form>
                </aside>

                <div class="catalog-products">
                    <?php if (mysqli_num_rows($plants_result) > 0): ?>
                        <?php while ($plant = mysqli_fetch_assoc($plants_result)): ?>

                            <article class="catalog-card">

                                <div class="catalog-card__image">
                                    <img 
                                        src="images/<?= htmlspecialchars($plant['image']) ?>" 
                                        alt="<?= htmlspecialchars($plant['plant_name']) ?>"
                                    >
                                </div>

                                <button 
                                    class="favorite-btn" 
                                    aria-label="Добавить в избранное"
                                    data-plant-id="<?= (int)$plant['id_plant'] ?>"
                                >
                                    <img 
                                        src="images/<?= $plant['id_favorite'] ? 'heartV2.svg' : 'heart.svg' ?>" 
                                        alt=""
                                    >
                                </button>

                                <h3><?= htmlspecialchars($plant['plant_name']) ?></h3>

                                <p><?= htmlspecialchars($plant['description']) ?></p>

                                <div class="catalog-card__bottom">
                                    <b><?= number_format($plant['price'], 0, '', ' ') ?> ₽</b>

                                    <button 
                                        class="catalog-cart-btn"
                                        data-plant-id="<?= (int)$plant['id_plant'] ?>"
                                    >
                                        В корзину
                                    </button>
                                </div>

                            </article>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="empty-account-message catalog-empty-message">
                            По выбранным параметрам растения не найдены.
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </section>

        <section class="catalog-help-section">
            <div class="container">
                <div class="catalog-help">
                    <div>
                        <h2>Не знаете, какое растение выбрать?</h2>
                        <p>Откройте раздел ухода за растениями и подберите вариант по освещению, поливу и сложности содержания.</p>
                    </div>
                    <a class="btn" href="care.php">Узнать об уходе</a>
                </div>
            </div>
        </section>
    </main>

<?php include 'includes/footer.php'; ?>

<?php
require_once 'config/config.php';
requireAdmin();

$reviewsQuery = mysqli_query($conn, "
    SELECT 
        r.id_review,
        r.rating,
        r.comment,
        r.review_date,
        u.name AS user_name,
        u.email,
        p.plant_name
    FROM reviews r
    INNER JOIN users u ON r.id_user = u.id_user
    INNER JOIN plants p ON r.id_plant = p.id_plant
    ORDER BY r.review_date DESC
");

include 'includes/header.php';
?>

<main>
    <section class="admin-section">
        <div class="container">
            <div class="admin-page-head">
                <div>
                    <h1>Отзывы</h1>
                    <p>Просмотр отзывов покупателей и удаление некорректных записей.</p>
                </div>
                <a class="admin-btn admin-btn-light" href="admin.php">← Назад</a>
            </div>

            <?php if (!empty($_SESSION['admin_success'])): ?>
                <div class="admin-message"><?= h($_SESSION['admin_success']) ?></div>
                <?php unset($_SESSION['admin_success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['admin_error'])): ?>
                <div class="admin-message admin-message-error"><?= h($_SESSION['admin_error']) ?></div>
                <?php unset($_SESSION['admin_error']); ?>
            <?php endif; ?>

            <div class="admin-data-card">
                <div class="admin-table-wrap">
                    <table class="admin-data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Дата</th>
                                <th>Пользователь</th>
                                <th>Растение</th>
                                <th>Оценка</th>
                                <th>Комментарий</th>
                                <th>Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($review = mysqli_fetch_assoc($reviewsQuery)): ?>
                                <tr>
                                    <td><?= (int)$review['id_review'] ?></td>
                                    <td><?= h(date('d.m.Y', strtotime($review['review_date']))) ?></td>
                                    <td>
                                        <strong><?= h($review['user_name']) ?></strong><br>
                                        <?= h($review['email']) ?>
                                    </td>
                                    <td><?= h($review['plant_name']) ?></td>
                                    <td><?= str_repeat('★', (int)$review['rating']) ?></td>
                                    <td><?= h($review['comment']) ?></td>
                                    <td>
                                        <form action="actions/admin_review_delete.php" method="POST" onsubmit="return confirm('Удалить отзыв?');">
                                            <input type="hidden" name="id_review" value="<?= (int)$review['id_review'] ?>">
                                            <button class="admin-small-btn admin-danger-btn" type="submit">Удалить</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

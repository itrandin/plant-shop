<?php
require_once 'config/config.php';
requireAdmin();

$usersQuery = mysqli_query($conn, "
    SELECT u.id_user, u.name, u.email, u.phone, u.registration_date, r.role_name, u.id_role,
           COUNT(DISTINCT o.id_order) AS orders_count,
           COALESCE(SUM(o.total_amount), 0) AS total_spent
    FROM users u
    INNER JOIN roles r ON u.id_role = r.id_role
    LEFT JOIN orders o ON u.id_user = o.id_user
    GROUP BY u.id_user, u.name, u.email, u.phone, u.registration_date, r.role_name, u.id_role
    ORDER BY u.id_user DESC
");

$rolesQuery = mysqli_query($conn, "SELECT id_role, role_name FROM roles ORDER BY id_role ASC");
$roles = [];
while ($role = mysqli_fetch_assoc($rolesQuery)) {
    $roles[] = $role;
}

include 'includes/header.php';
?>

<main>
    <section class="admin-section">
        <div class="container">
            <div class="admin-page-head">
                <div>
                    <h1>Пользователи</h1>
                    <p>Просмотр пользователей, смена роли и анализ покупательской активности.</p>
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
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Телефон</th>
                                <th>Роль</th>
                                <th>Заказов</th>
                                <th>Сумма покупок</th>
                                <th>Дата регистрации</th>
                                <th>Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = mysqli_fetch_assoc($usersQuery)): ?>
                                <tr>
                                    <td><?= (int)$user['id_user'] ?></td>
                                    <td><strong><?= h($user['name']) ?></strong></td>
                                    <td><?= h($user['email']) ?></td>
                                    <td><?= h($user['phone'] ?: 'Не указан') ?></td>
                                    <td>
                                        <form class="admin-inline-form" action="actions/admin_user_role.php" method="POST">
                                            <input type="hidden" name="id_user" value="<?= (int)$user['id_user'] ?>">
                                            <select name="id_role">
                                                <?php foreach ($roles as $role): ?>
                                                    <option value="<?= (int)$role['id_role'] ?>" <?= (int)$user['id_role'] === (int)$role['id_role'] ? 'selected' : '' ?>>
                                                        <?= h($role['role_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                    </td>
                                    <td><?= (int)$user['orders_count'] ?></td>
                                    <td><?= number_format((float)$user['total_spent'], 0, '', ' ') ?> ₽</td>
                                    <td><?= h(date('d.m.Y', strtotime($user['registration_date']))) ?></td>
                                    <td>
                                            <button class="admin-small-btn" type="submit">Сохранить</button>
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

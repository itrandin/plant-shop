<?php
require_once 'config/config.php';
requireAdmin();

$messagesQuery = mysqli_query($conn, "
    SELECT id_message, name, email, message, created_at
    FROM contact_messages
    ORDER BY created_at DESC, id_message DESC
");

include 'includes/header.php';
?>

<main>
    <section class="admin-section">
        <div class="container">
            <div class="admin-page-head">
                <div>
                    <h1>Сообщения</h1>
                    <p>Обращения покупателей, отправленные через форму обратной связи.</p>
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
                    <table class="admin-data-table admin-messages-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Дата</th>
                                <th>Имя</th>
                                <th>E-mail</th>
                                <th>Сообщение</th>
                                <th>Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($messagesQuery && mysqli_num_rows($messagesQuery) > 0): ?>
                                <?php while ($message = mysqli_fetch_assoc($messagesQuery)): ?>
                                    <tr>
                                        <td><?= (int)$message['id_message'] ?></td>
                                        <td><?= h(date('d.m.Y H:i', strtotime($message['created_at']))) ?></td>
                                        <td><strong><?= h($message['name']) ?></strong></td>
                                        <td><a href="mailto:<?= h($message['email']) ?>"><?= h($message['email']) ?></a></td>
                                        <td><?= nl2br(h($message['message'])) ?></td>
                                        <td>
                                            <form action="actions/admin_message_delete.php" method="POST" onsubmit="return confirm('Удалить сообщение?');">
                                                <input type="hidden" name="id_message" value="<?= (int)$message['id_message'] ?>">
                                                <button class="admin-small-btn admin-danger-btn" type="submit">Удалить</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">Сообщений пока нет.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

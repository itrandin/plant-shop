<?php

require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contacts.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

$_SESSION['contact_old'] = [
    'name' => $name,
    'email' => $email,
    'message' => $message,
];

if ($name === '' || $email === '' || $message === '') {
    $_SESSION['contact_error'] = 'Заполните все поля формы.';
    header('Location: ../contacts.php#contact-form');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['contact_error'] = 'Введите корректный e-mail.';
    header('Location: ../contacts.php#contact-form');
    exit;
}

$stmt = mysqli_prepare($conn, "
    INSERT INTO contact_messages (name, email, message, created_at)
    VALUES (?, ?, ?, NOW())
");

if (!$stmt) {
    $_SESSION['contact_error'] = 'Не удалось подготовить запрос.';
    header('Location: ../contacts.php#contact-form');
    exit;
}

mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $message);

if (mysqli_stmt_execute($stmt)) {
    unset($_SESSION['contact_old']);
    $_SESSION['contact_success'] = 'Спасибо! Ваше сообщение отправлено.';
} else {
    $_SESSION['contact_error'] = 'Ошибка при отправке сообщения. Попробуйте позже.';
}

header('Location: ../contacts.php#contact-form');
exit;

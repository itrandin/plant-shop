<?php

require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = trim($_POST['password'] ?? '');
$password_confirm = trim($_POST['password_confirm'] ?? '');

if (
    $name === '' ||
    $email === '' ||
    $phone === '' ||
    $password === '' ||
    $password_confirm === ''
) {
    $_SESSION['register_error'] = 'Заполните все обязательные поля.';
    header('Location: ../register.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['register_error'] = 'Введите корректный email.';
    header('Location: ../register.php');
    exit;
}

if (!preg_match('/^\+7\d{10}$/', $phone)) {
    $_SESSION['register_error'] = 'Введите корректный номер телефона.';
    header('Location: ../register.php');
    exit;
}

if ($password !== $password_confirm) {
    $_SESSION['register_error'] = 'Пароли не совпадают.';
    header('Location: ../register.php');
    exit;
}

if (mb_strlen($password) < 5) {
    $_SESSION['register_error'] = 'Пароль должен быть не короче 5 символов.';
    header('Location: ../register.php');
    exit;
}

$check = mysqli_prepare($conn, "SELECT id_user FROM users WHERE email = ?");
mysqli_stmt_bind_param($check, "s", $email);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);

if (mysqli_stmt_num_rows($check) > 0) {
    $_SESSION['register_error'] = 'Пользователь с таким email уже существует.';
    header('Location: ../register.php');
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$id_role = 1;

$stmt = mysqli_prepare($conn, "
    INSERT INTO users (id_role, name, email, password, phone)
    VALUES (?, ?, ?, ?, ?)
");

mysqli_stmt_bind_param($stmt, "issss", $id_role, $name, $email, $hash, $phone);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['register_success'] = 'Регистрация прошла успешно. Теперь войдите в аккаунт.';
    header('Location: ../login.php');
    exit;
}

$_SESSION['register_error'] = 'Ошибка регистрации. Попробуйте еще раз.';
header('Location: ../register.php');
exit;
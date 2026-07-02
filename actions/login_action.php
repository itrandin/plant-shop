<?php

require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($email === '' || $password === '') {
    $_SESSION['login_error'] = 'Заполните все поля.';
    header('Location: ../login.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error'] = 'Введите корректный e-mail.';
    header('Location: ../login.php');
    exit;
}

$stmt = mysqli_prepare($conn, "
    SELECT id_user, id_role, name, password
    FROM users
    WHERE email = ?
");

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {

    if (password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['id_role'];

        header('Location: ../account.php');
        exit;
    }
}

$_SESSION['login_error'] = 'Неверный e-mail или пароль.';
header('Location: ../login.php');
exit;

<?php
session_start();
require __DIR__ . '/../../src/Helpers.php';
require __DIR__ . '/../../src/Auth.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(Auth::checkLogin($username, $password)){
        header("Location: index.php");
        exit;
    } else {
        $error = 'Неверный логин или пароль';
    }
}
?>

<h1>Вход в админку</h1>
<?php if($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="post">
    <input type="text" name="username" placeholder="Имя пользователя" required><br><br>
    <input type="password" name="password" placeholder="Пароль" required><br><br>
    <button type="submit">Войти</button>
</form>

<?php
class Auth {
    public static function checkLogin($username, $password){
        $envUser = env('ADMIN_USERNAME');
        $envPass = env('ADMIN_PASSWORD_HASH');

        if($username === $envUser && password_verify($password, $envPass)){
            $_SESSION['admin_logged'] = true;
            return true;
        }
        return false;
    }

    public static function logout(){
        unset($_SESSION['admin_logged']);
        session_destroy();
    }

    public static function isLogged(){
        return isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true;
    }

    public static function requireLogin(){
        if(!self::isLogged()){
            header("Location: login.php");
            exit;
        }
    }
}

<?php
session_start();
require __DIR__ . '/../../src/Auth.php';
Auth::logout();
header("Location: login.php");
exit;
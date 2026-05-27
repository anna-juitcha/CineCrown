<?php
session_start();

$host = 'localhost';
$db   = 'gestion_cinema';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

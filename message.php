<?php
require "db.php";

$id_user = $_SESSION['id_user'];
$stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = ?");
$stmt->execute([$id_user]);
$user = $stmt->fetch();

$state = $pdo->prepare("SELECT * FROM messages WHERE recepteur = ?");
$state->execute([$id_user]);

$message = $state->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="admin/admin.css">

</head>
<body>
    
    <?php require 'index.php'; ?>
        <div class="container">
            <div class ="lien" style ="display: flex; justify-content: space-around;">
                <a href="envoie_messages.php">Envoyer un message</a>
                <a href="modifier.php">Modifier information du compte</a>
            </div>
        <h1>Message de <?= $user["nom_user"] ?></h1>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Emeteur</th>
                    <th>Messages</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($message as $m):?>
                <tr>
                    <td><?= $m["date_envoie"]?></td>
                    <td><?= $m["emetteur"]?></td>
                    <td><?= $m["messages"]?></td>
                </tr>
                <?php endforeach ;?>
            </tbody>
        </table>
    </div>
</body>
</html>
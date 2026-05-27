<?php
require '../db.php';
$reservation = $pdo->query("SELECT * FROM reservation WHERE etat_reservation = 'validée'")->fetchAll();


$querySum = $pdo->query("SELECT SUM(prix_total) AS Recette FROM reservation WHERE etat_reservation = 'validée' ");
$resultSum = $querySum->fetch();
$totalGeneral = $resultSum['Recette'] ?? 0;
?>

<!-- tableau.html -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des Utilisateurs</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php require 'index_admin.php'; ?>
    <div class="container">
        <div class="card-recette" style=" margin-top: 20px ;background: #ffffff; padding: 20px; border-radius: 12px; max-width: 300px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                <span style="font-size: 14px; color: #666; font-weight: 500; text-transform: uppercase;">Recette total</span>
                <h3 style="font-size: 24px; color: #222; margin-top: 5px; font-weight: 700;">
                    <?= number_format($totalGeneral ?? 0,0,',','') ?>
                </h3>
        </div>
        <h1>Liste des Utilisateurs</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID du client</th>
                    <th>ID de la seance</th>
                    <th>Nombre de place</th>
                    <th>Ville</th>
                    <th>Prix_total</th>
                </tr>
            </thead>
            <?php foreach ($reservation as $r): ?>
            <tbody>
                <tr>
                    <td><?= $r["id_reservation"]?></td>
                    <td><?= $r["id_user"]?></td>
                    <td><?= $r["id_seance"]?></td>
                    <td><?= $r["place"]?></td>
                    <td><?= $r["ville"]?></td>
                    <td><?= $r["prix_total"]?></td>
                </tr>
            </tbody>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
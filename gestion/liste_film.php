<?php
require '../db.php';
$film = $pdo->query("SELECT * FROM film")->fetchAll();


$querySum = $pdo->query("SELECT COUNT(*) AS tout_film FROM film");
$resultSum = $querySum->fetch();
$totalGeneral = $resultSum['tout_film'] ?? 0;
?>

<!-- tableau.html -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des Utilisateurs</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="../admin/admin.css">
</head>
<body>
    <?php require 'index_gestion.php'; ?>
    <div class="container">
        <div class="card-recette" style=" margin-top: 20px ;background: #ffffff; padding: 20px; border-radius: 12px; max-width: 300px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                <span style="font-size: 14px; color: #666; font-weight: 500; text-transform: uppercase;">tout les films</span>
                <h3 style="font-size: 24px; color: #222; margin-top: 5px; font-weight: 700;">
                    <?= number_format($totalGeneral ?? 0,0,',','') ?>
                </h3>
        </div>
        <h1>Liste des films</h1>

        <table>
            <thead>
                <tr>
                    <th>ID du film</th>
                    <th>titre</th>
                    <th>genre</th>
                    <th>realisateur</th>
                    <th>duree</th>
                    <th>bande_d'annonce</th>
                </tr>
            </thead>
            <?php foreach ($film as $f): ?>
            <tbody>
                <tr>
                    <td><?= $f["id_film"]?></td>
                    <td><?= $f["titre"]?></td>
                    <td><?= $f["genre"]?></td>
                    <td><?= $f["realisateur"]?></td>
                    <td><?= $f["duree"]?></td>
                    <td><?= $f["bande_annonce"]?></td>
                </tr>
            </tbody>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
<?php
require '../db.php';
$salle = $pdo->query("SELECT * FROM salle")->fetchAll();


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM  salle  WHERE id_salle = ?")->execute([$id]);

    header ("Location: gestion_salle.php");
exit();
}

$querySum = $pdo->query("SELECT COUNT(*) AS toute_salle FROM salle");
$resultSum = $querySum->fetch();
$totalGeneral = $resultSum['toute_salle'] ?? 0;
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
        <div class ="lien" style ="display: flex; justify-content: space-around;">
            <div class="card-recette" style=" margin-top: 20px ;background: #ffffff; padding: 20px; border-radius: 12px; max-width: 300px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                <span style="font-size: 14px; color: #666; font-weight: 500; text-transform: uppercase;">Salle total</span>
                <h3 style="font-size: 24px; color: #222; margin-top: 5px; font-weight: 700;">
                    <?= number_format($totalGeneral ?? 0,0,',','') ?>
                </h3>
            </div>
            <a href="ajouter_salle.php">Ajouter une salle</a>
        </div>
        <h1>Liste des Salle</h1>

        <table>
            <thead>
                <tr>
                    <th>ID salle</th>
                    <th>Nom de le salle</th>
                    <th>capaciter</th>
                    <th>Ville</th>
                    <th>supprimer</th>
                    <th>Modifier</th>
                </tr>
            </thead>
            <?php foreach ($salle as $s): ?>
            <tbody>
                <tr>
                    <td><?= $s["id_salle"]?></td>
                    <td><?= $s["nom_salle"]?></td>
                    <td><?= $s["capacite"]?></td>
                    <td><?= $s["ville"]?></td>
                    <td><a href="gestion_salle.php?delete=<?= $s['id_salle'] ?>" onclick="return confirm('Supprimer ?')" class="status actif">Supprimer</a></td>
                    <td><a href="modifier_salle.php?id=<?= $s['id_salle'] ?>" onclick="return confirm('Modifier ?')" class="status actif">Modifier</a></td>
                </tr>
            </tbody>
            <?php endforeach;?>
        </table>
    </div>
</body>
</html>
<?php
require '../db.php';
$seance = $pdo->query("SELECT * FROM seance")->fetchAll();


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM  seance  WHERE id_seance = ?")->execute([$id]);
}

$querySum = $pdo->query("SELECT COUNT(*) AS toute_seance FROM seance");
$resultSum = $querySum->fetch();
$totalGeneral = $resultSum['toute_seance'] ?? 0;
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
                <span style="font-size: 14px; color: #666; font-weight: 500; text-transform: uppercase;">Seance total</span>
                <h3 style="font-size: 24px; color: #222; margin-top: 5px; font-weight: 700;">
                    <?= number_format($totalGeneral ?? 0,0,',','') ?>
                </h3>
            </div>
            <a href="ajouter_seance.php">Ajouter une seance</a>
        </div>
        <h1>Liste des Seance</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>id film</th>
                    <th>id salle</th>
                    <th>date seance</th>
                    <th>heure seance</th>
                    <th>prix simple</th>
                    <th>prix vip</th>
                    <th>type</th>
                    <th>supprimer</th>
                    <th>Modifier</th>
                </tr>
            </thead>
            <?php foreach ($seance as $s): ?>
            <tbody>
                <tr>
                    <td><?= $s["id_seance"]?></td>
                    <td><?= $s["id_film"]?></td>
                    <td><?= $s["id_salle"]?></td>
                    <td><?= $s["date_seance"]?></td>
                    <td><?= $s["heure_seance"]?></td>
                    <td><?= $s["prix_simple"]?></td>
                    <td><?= $s["prix_vip"]?></td>
                    <td><?= $s["types"]?></td>
                    <td><a href="gestion_seance.php?delete=<?= $s['id_seance'] ?>" onclick="return confirm('Supprimer ?')" class="status actif">Supprimer</a></td>
                    <td><a href="modifier_seance.php?id=<?= $s['id_seance'] ?>" onclick="return confirm('Modifier ?')" class="status actif">Modifier</a></td>
                </tr>
            </tbody>
            <?php endforeach;?>
        </table>
    </div>
</body>
</html>
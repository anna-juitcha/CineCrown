<?php
require '../db.php';
$film = $pdo->query("SELECT * FROM film")->fetchAll();


$querySum = $pdo->query("SELECT COUNT(*) AS tout_film FROM film");
$resultSum = $querySum->fetch();
$totalGeneral = $resultSum['tout_film'] ?? 0;

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM  film  WHERE id_film = ?")->execute([$id]);

    header ("Location: liste_film.php");
exit();
}

?>

<!-- tableau.html -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des Utilisateurs</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="modifier_film.css">
</head>
<body>
    <?php require 'index_admin.php'; ?>
    <div class="container">
        <div class ="lien" style ="display: flex; justify-content: space-around;">
            <div class="card-recette" style=" margin-top: 20px ;background: #ffffff; padding: 20px; border-radius: 12px; max-width: 300px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                <span style="font-size: 14px; color: #666; font-weight: 500; text-transform: uppercase;">Total film</span>
                <h3 style="font-size: 24px; color: #222; margin-top: 5px; font-weight: 700;">
                    <?= number_format($totalGeneral ?? 0,0,',','') ?>
                </h3>
            </div>
            <a href="ajouter_film.php">Ajouter un film</a>
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
                    <th>lien de la video</th>
                    <th>Supprimer</th>
                    <th>modifier</th>
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
                    <td class="image"><img src="../admin/<?= htmlspecialchars($f['bande_annonce']) ?>" 
                                     alt="Affiche de <?= htmlspecialchars($f['titre']) ?>" 
                                     class="movie-poster"></td>
                    <td><?= $f["lien_annonce"]?></td>
                    <td><a href="liste_film.php?delete=<?= $f['id_film'] ?>" onclick="return confirm('Supprimer ?')" class="status actif">Supprimer</a></td>
                    <td><a href="modifier_film.php?id=<?= $f['id_film'] ?>" onclick="return confirm('Modifier ?')" class="status actif">Modifier</a></td>
                
                </tr>
            </tbody>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
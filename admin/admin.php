<?php
require '../db.php';
$utilisateur = $pdo->query("SELECT * FROM user")->fetchAll();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM  user  WHERE id_user = ?")->execute([$id]);
}

$querySum = $pdo->query("SELECT COUNT(*) AS client_total FROM user WHERE statut = 'Client' ");
$resultSum = $querySum->fetch();
$totalGeneral = $resultSum['client_total'] ?? 0;
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
                <span style="font-size: 14px; color: #666; font-weight: 500; text-transform: uppercase;">client total</span>
                <h3 style="font-size: 24px; color: #222; margin-top: 5px; font-weight: 700;">
                    <?= number_format($totalGeneral ?? 0,0,',','') ?>
                </h3>
            </div>
            <a href="ajouter_employer.php">Ajouter un employer</a>
        </div>
        <h1>Liste des Utilisateurs</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Mot de passe</th>
                    <th>Ville</th>
                    <th>Statut</th>
                    <th>Suprime</th>
                </tr>
            </thead>
            <?php foreach ($utilisateur as $u): ?>
            <tbody>
                <tr>
                    <td><?= $u["id_user"]?></td>
                    <td><?= $u["nom_user"]?></td>
                    <td><?= $u["email"]?></td>
                    <td><?= $u["mot_passe"]?></td>
                    <td><?= $u["ville"]?></td>
                    <td><?= $u["statut"]?></td>
                    <td><a href="admin.php?delete=<?= $u['id_user'] ?>" onclick="return confirm('Supprimer ?')" class="status actif">Supprimer</a></td>
                </tr>
            </tbody>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
<?php
require '../db.php';

// 1. Vérifier si l'ID du film est bien présent dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: liste_film.php");
    exit();
}

$id_film = $_GET['id'];

// 2. Récupérer les données actuelles du film pour les afficher dans le formulaire
$query = $pdo->prepare("SELECT * FROM film WHERE id_film = ?");
$query->execute([$id_film]);
$film = $query->fetch(PDO::FETCH_ASSOC);

if (!$film) {
    die("Film introuvable.");
}

// 3. Traitement de la mise à jour lors de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $genre = $_POST['genre'];
    $realisateur = $_POST['realisateur'];
    $duree = $_POST['duree'];
    $lien_annonce = $_POST['lien_annonce'];
    
    // Par défaut, on garde l'ancien chemin de la bande-annonce
    $chemin_bande = $film['bande_annonce'];

    // Vérifier si l'utilisateur a chargé une NOUVELLE bande-annonce
    if (isset($_FILES['bande']) && $_FILES['bande']['error'] === UPLOAD_ERR_OK) {
        $extension = pathinfo($_FILES['bande']['name'], PATHINFO_EXTENSION);
        $nomFichier = time() . "." . $extension;
        $chemin_bande = "uploads/" . $nomFichier;
        
        move_uploaded_file($_FILES['bande']['tmp_name'], $chemin_bande);
    }

    // Requête de mise à jour SQL
    $update = $pdo->prepare("UPDATE film SET titre = ?, genre = ?, realisateur = ?, duree = ?, lien_annonce = ?, bande_annonce = ? WHERE id_film = ?");
    
    if ($update->execute([$titre, $genre, $realisateur, $duree, $lien_annonce, $chemin_bande, $id_film])) {
        header("Location: liste_film.php");
        exit();
    } else {
        $erreur = "Erreur lors de la modification du film.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le film</title>
    <link rel="stylesheet" href="../formulaire.css">
</head>
<body>


    <div class="auth-container">
        <h2>Modifier le film : <?= htmlspecialchars($film['titre']) ?></h2>
        
        <?php if (isset($erreur)): ?>
            <p style="color: red;"><?= $erreur ?></p>
        <?php endif; ?>

        <form action="modifier_film.php?id=<?= $id_film ?>" method="POST" enctype="multipart/form-data">
            
                <input type="text" name="titre" value="<?= htmlspecialchars($film['titre']) ?>" required>

                <input type="text" name="genre" value="<?= htmlspecialchars($film['genre']) ?>" required>

                <input type="text" name="realisateur" value="<?= htmlspecialchars($film['realisateur']) ?>" required>
    
                <input type="text" name="duree" value="<?= htmlspecialchars($film['duree']) ?>" required>

                <input type="url" name="lien_annonce" value="<?=$film['lien_annonce'] ?>" required>

                <input type="file" name="bande_annonce" value="<?=$film['bande_annonce'] ?>" required>

            <button type="submit" class="btn-submit">Enregistrer les modifications</button>
            <a href="liste_film.php" style="margin-left: 10px; color: gray;">Annuler</a>
        </form>
    </div>

</body>
</html>
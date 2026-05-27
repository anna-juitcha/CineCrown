<?php
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $genre = $_POST['genre'];
    $realisateur = $_POST['realisateur'];
    $dure = $_POST['duree'];
    $lien_annonce =$_POST['lien_annonce'];


    if (isset($_FILES['bande']) && $_FILES['bande']['error'] === UPLOAD_ERR_OK){
    $extension = pathinfo($_FILES['bande']['name'], PATHINFO_EXTENSION);
    $nomFichier = time() . "." . $extension;
    $chemin = "uploads/" . $nomFichier;
    move_uploaded_file($_FILES['bande']['tmp_name'], $chemin);
    

        #requete preparer
        $state = $pdo->prepare("INSERT INTO film (titre,genre,realisateur,duree,lien_annonce,bande_annonce) VALUES (?,?,?,?,?,?)");
        if($state->execute([$titre, $genre, $realisateur,$dure, $lien_annonce,$chemin])) {
            header("location: liste_film.php");
            exit;
        }else{
            echo"erreur enregistrement";
        }

    } else {
        die("ERREUR");
    }
}  
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../formulaire.css">
</head>
<body>
    <div class="auth-container">
        <h2>Ajouter un film</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="titre" placeholder="Titre du film" required>
            <input type="text" name="genre" placeholder="Genre du film" required>
            <input type="text" name="realisateur" placeholder="Qui es le realisateur" required>
            <input type="text" name="duree" placeholder="Durree du film" required>
            <input type="text" name="lien_annonce" placeholder="Lien vers l'annonce youtube" required>
            <input type="file" name="bande" placeholder="La bonde déannonce" required>
            <button type="submit">Ajouter</button>
        </form>
    </div>
</body>
</html>

<?php
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom_user'];
    $email = $_POST['email'];
    $ville = $_POST['ville'];
    $pass =$_POST['mot_passe'];
    $statut = $_POST['statut'];

    if (!empty($nom) && !empty($email) &&!empty($ville) &&!empty($pass)) {
        #requete preparer
        $state = $pdo->prepare("INSERT INTO user (nom_user,email,ville,statut,mot_passe) VALUES (?,?,?,?,?)");
        if($state->execute([$nom, $email, $ville,$statut, $pass])) {
            header("location: admin.php");
            exit;
        }
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
        <h2>Ajouter un employer</h2>
        <form method="POST">
            <input type="text" name="nom_user" placeholder="Nom complet" required>
            <input type="email" name="email" placeholder="Email" required>
            <select name="ville" id="ville">
                <option value="Douala">DOUALA</option>
                <option value="Yaounde">YAOUNDE</option>
            </select>
            <select name="statut" id="statut">
                <option value="Caissier">Caissier</option>
                <option value="Gestion_saence">Gestion_saencees</option>
            </select>
            <input type="password" name="mot_passe" placeholder="Mot de passe" required>
            <button type="submit">AJOUTER</button>
        </form>
    </div>
</body>
</html>

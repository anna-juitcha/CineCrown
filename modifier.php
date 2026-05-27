<?php
require 'db.php';

$id_user = $_SESSION['id_user'];

// 2. Récupérer les données actuelles de l'utilisateur pour les afficher dans le formulaire
$req = $pdo->prepare("SELECT * FROM user WHERE id_user = ?");
$req->execute([$id_user]);
$user = $req->fetch();

if (isset($_POST['enregistrer'])) {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_passe = $_POST['mot_passe'];
    $ville = $_POST['ville'];


    $update = $pdo->prepare("UPDATE user SET nom_user = ?, email = ?, ville = ?, mot_passe = ? WHERE id_user = ?");
    $update->execute([$nom, $email, $ville, $mot_passe, $id_user]);

    if ($user['statut'] == "Client"){
            header("Location: client/message_accueil.php");
        } elseif ($user['statut'] == "Gestion_seance"){
            header("Location: gestion/message_gestion.php");
        } elseif ($user['statut'] == "Admin"){
            header("Location: admin/message_admin.php");
        } elseif ($user['statut'] == "Caissier"){
            header("Location: caissier/message_caisse.php");
        }
        exit();

}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>modifier_compte</title>
    <link rel="stylesheet" href="formulaire.css">
</head>
<body>
<div class="container">
    <form class="form-box" action="#" method="post">
        <h2>Modifier vos informations</h2>

        <div class="input-group">
            <label for="nom">Nom</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($user['nom_user']) ?>">
        </div>

        <div class="input-group">
            <label for="email">Email</label>
            <input type="text" name="email" value="<?= htmlspecialchars($user['email']) ?>" >
        </div>
        <div class="input-group">
            <label for="ville">Ville</label>
            <select name="ville" id="ville">
                <option value="<?= htmlspecialchars($user['ville']) ?>"><?= htmlspecialchars($user['ville']) ?></option>
                <option value="Douala">Douala</option>
                <option value="Yaounde">Yaoundé</option>
            </select>
        </div>

        <div class="input-group">
            <label for="mot_passe">Mot de passe</label>
            <input type="text" name="mot_passe" value="<?= htmlspecialchars($user['mot_passe']) ?>" >
        </div>

        <button type="submit" class="btn" name="enregistrer">Enregistrer</button>

    </form>
</div>

</body>
</html>
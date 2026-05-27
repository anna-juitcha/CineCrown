<?php
require "db.php";

$id_user = $_SESSION['id_user'];
$stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = ?");
$stmt->execute([$id_user]);
$user = $stmt->fetch();

if ($user['statut'] == "Client"){
    $liste = $pdo->query("SELECT * FROM user WHERE statut = 'Caissier' OR  statut = 'Gestion_seance'")->fetchAll();
}else{
    $liste = $pdo->query("SELECT * FROM user ")->fetchAll();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emetteur = $id_user;
    $recepteur = $_POST['recepteur'];
    $message = $_POST['message'];

        #requete preparer
        $state = $pdo->prepare("INSERT INTO messages (date_envoie,emetteur,recepteur,messages) VALUES (NOW(),?,?,?)");
        if($state->execute([$emetteur,$recepteur, $message])) {
            

        // Redirection selon le statut
        if ($user['statut'] == "Client"){
            header("Location: client/accueil.php");
        } elseif ($user['statut'] == "Gestion_seance"){
            header("Location: gestion/liste_film.php");
        } elseif ($user['statut'] == "Admin"){
            header("Location: admin/admin.php");
        } elseif ($user['statut'] == "Caissier"){
            header("Location: caissier/gestion_caissier_cinema/caissier.php");
            exit();
        }
        exit();

        
        }
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="formulaire.css">
</head>
<body>
    <div class="auth-container">
        <h2>Envoyer messages</h2>
        

        <form method="POST" action="">
            
            <select name="recepteur" id="recepteur">
                <?php foreach ($liste as $l): ?>
                <option value="<?= $l["id_user"]?>"><?= $l["id_user"]?> | <?= $l["statut"]?> | <?= $l["ville"]?></option>
                <?php endforeach; ?>
            </select>
            <textarea name="message" id="message" placeholder="entrez votre message" rows="8" cols="35" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </div>
</body>
</html>
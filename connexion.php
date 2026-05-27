<?php
require 'db.php';

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $mot_passe = $_POST['mot_passe'];

    // Requête préparée
    $state = $pdo->prepare("SELECT * FROM user WHERE email = ? AND mot_passe = ?");
    $state->execute([$email, $mot_passe]);
    $utilisateur = $state->fetch();

    if ($utilisateur){
        $_SESSION['connecte'] = true;
        $_SESSION['id_user'] = $utilisateur['id_user'];

        // Redirection selon le statut
        if ($utilisateur['statut'] == "Client"){
            header("Location: client/accueil.php");
        } elseif ($utilisateur['statut'] == "Gestion_seance"){
            header("Location: gestion/liste_film.php");
        } elseif ($utilisateur['statut'] == "Admin"){
            header("Location: admin/admin.php");
        } elseif ($utilisateur['statut'] == "Caissier"){
            header("Location: caissier/gestion_caissier_cinema/caissier.php");
        }
        exit();
    } else {
        $erreur = "Email ou mot de passe incorrect !";
    }   
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="formulaire.css">
    <title>Connexion - CineCrown</title>
</head>
<body>
    <div class="auth-container">
        <h2>Connexion</h2>
        
        <?php if(isset($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mot_passe" placeholder="Mot de passe" required>
            <!-- Ajout du name="valider" ici ou utilisation de la méthode POST globale -->
            <button type="submit" name="valider">SE CONNECTER</button>
        </form>
        <a href="inscription.php">Créer un compte CineCrown</a>
    </div>
</body>
</html>

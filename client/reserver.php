<?php
require '../db.php';

// 1. VERIFICATION ID SEANCE ET SESSION
if (!isset($_GET['id']) || !isset($_SESSION['id_user'])) {
    header("Location: reservation.php");
    exit();
}

$id_seance = $_GET['id'];
$id_user = $_SESSION['id_user'];

// 2. RECUPERATION USER ET SEANCE EN SIMULTANÉ
$user = $pdo->prepare("SELECT nom_user, email FROM user WHERE id_user = ?");
$user->execute([$id_user]);
$user = $user->fetch();

$sqlSeance = "SELECT s.*, f.titre, sa.nom_salle, sa.ville 
              FROM seance s
              INNER JOIN film f ON s.id_film = f.id_film
              INNER JOIN salle sa ON s.id_salle = sa.id_salle
              WHERE s.id_seance = ?";
$seance = $pdo->prepare($sqlSeance);
$seance->execute([$id_seance]);
$seance = $seance->fetch();

// 3. ENREGISTRER RESERVATION
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sécurisation stricte des types numériques
    $place = (int)$_POST['place'];
    $prix_total = (int)$_POST['prix_total'];

    $insert = $pdo->prepare("INSERT INTO reservation (id_user, id_seance, place, ville, prix_total) VALUES (?, ?, ?, ?, ?)");
    $insert->execute([$id_user, $id_seance, $place, $seance['ville'], $prix_total]);

    echo "<script>alert('Réservation effectuée avec succès effectuer un depot au numero suivant : +237 6 88 88 88 88'); window.location='reservation.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="reserver.css">
    
</head>
<body>
    <?php require 'index_client.php'; ?>
    <div class="main-content">
        <div class="reserver-box">
            <h1 class="title">Réserver une séance</h1>
            <form method="POST">
                <div class="form-grid">
                    <div class="input-box"><label>Nom utilisateur</label><input type="text" value="<?= htmlspecialchars($user['nom_user']) ?>" readonly></div>
                    <div class="input-box"><label>Email</label><input type="text" value="<?= htmlspecialchars($user['email']) ?>" readonly></div>
                    <div class="input-box"><label>Film</label><input type="text" value="<?= htmlspecialchars($seance['titre']) ?>" readonly></div>
                    <div class="input-box"><label>Salle</label><input type="text" value="<?= htmlspecialchars($seance['nom_salle']) ?>" readonly></div>
                    <div class="input-box"><label>Ville</label><input type="text" value="<?= htmlspecialchars($seance['ville']) ?>" readonly></div>
                    <div class="input-box"><label>Date séance</label><input type="text" value="<?= htmlspecialchars($seance['date_seance']) ?>" readonly></div>
                    <div class="input-box"><label>Heure séance</label><input type="text" value="<?= htmlspecialchars($seance['heure_seance']) ?>" readonly></div>
                    
                    <div class="input-box">
                        <label>Choisir le type</label>
                        <select name="type_prix" id="type_prix" required>
                            <option value="">Choisir</option>
                            <option value="<?= $seance['prix_simple'] ?>">Prix simple (<?= $seance['prix_simple'] ?> FCFA)</option>
                            <option value="<?= $seance['prix_vip'] ?>">Prix VIP (<?= $seance['prix_vip'] ?> FCFA)</option>
                        </select>
                    </div>
                    <div class="input-box"><label>Nombre de places</label><input type="number" name="place" id="place" min="1" required></div>
                    <div class="input-box"><label>Prix total</label><input type="number" name="prix_total" id="prix_total" readonly required></div>
                </div>
                <button class="btn" type="submit">Confirmer la réservation</button>
            </form>
        </div>
    </div>
    <script>
        const typePrix = document.getElementById("type_prix");
        const place = document.getElementById("place");
        const total = document.getElementById("prix_total");

        function calculerPrix() {
            total.value = (parseInt(typePrix.value) || 0) * (parseInt(place.value) || 0);
        }
        typePrix.addEventListener("change", calculerPrix);
        place.addEventListener("input", calculerPrix); // "input" gère le clavier ET les flèches du champ numérique
    </script>
</body>
</html>
<?php
require '../db.php';

/* =========================
   RECHERCHE
========================= */

$film_id = $_GET['choix_film'] ?? '';
$ville = $_GET['choix_ville'] ?? '';
$date = $_GET['choix_date'] ?? '';

/* =========================
   LISTE DES FILMS
========================= */

$film = $pdo->query("SELECT * FROM film")->fetchAll();

/* =========================
   LISTE DES VILLES
========================= */

$villes = $pdo->query("SELECT DISTINCT ville FROM salle")->fetchAll();

/* =========================
   REQUETE PRINCIPALE
========================= */

$sql = "
SELECT 
    seance.*,
    film.titre,
    salle.nom_salle,
    salle.ville

FROM seance

INNER JOIN film 
ON seance.id_film = film.id_film

INNER JOIN salle
ON seance.id_salle = salle.id_salle

WHERE 1
";

/* =========================
   RECHERCHE FILM
========================= */

$params = [];

if (!empty($film_id)) {
    $sql .= " AND seance.id_film = ?";
    $params[] = $film_id;
}

/* =========================
   RECHERCHE VILLE
========================= */

if (!empty($ville)) {
    $sql .= " AND salle.ville = ?";
    $params[] = $ville;
}

/* =========================
   RECHERCHE DATE
========================= */

if (!empty($date)) {
    $sql .= " AND seance.date_seance = ?";
    $params[] = $date;
}

/* =========================
   EXECUTION
========================= */

$state = $pdo->prepare($sql);
$state->execute($params);

$seance = $state->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Réservation - CinéCrown</title>

<link rel="stylesheet" href="../index.css">
<link rel="stylesheet" href="reservation.css">

</head>
<body>

<?php require 'index_client.php'; ?>

<div class="main-content">

    <div class="reservation-container">

        <h1 class="title">Réservation de Séance</h1>

        <!-- FORMULAIRE -->
        <form method="GET">

            <div class="search-box">

                <!-- FILM -->
                <select name="choix_film">

                    <option value="">
                        Choisir un film
                    </option>

                    <?php foreach($film as $f): ?>

                        <option value="<?= $f['id_film'] ?>">

                            <?= $f['titre'] ?>

                        </option>

                    <?php endforeach; ?>

                </select>

                <!-- VILLE -->
                <select name="choix_ville">

                    <option value="">
                        Choisir la ville
                    </option>

                    <?php foreach($villes as $v): ?>

                        <option value="<?= $v['ville'] ?>">

                            <?= $v['ville'] ?>

                        </option>

                    <?php endforeach; ?>

                </select>

                <!-- DATE -->
                <input 
                    type="date"
                    name="choix_date"
                >

                <!-- BOUTON -->
                <button type="submit">

                    Rechercher

                </button>

            </div>

        </form>

        <!-- TABLEAU -->
        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Film</th>
                        <th>Salle</th>
                        <th>Ville</th>
                        <th>Date séance</th>
                        <th>Heure séance</th>
                        <th>Prix simple</th>
                        <th>Prix vip</th>
                        <th>Type</th>
                        <th>Réserver</th>

                    </tr>

                </thead>

                <tbody>

                <?php foreach ($seance as $s): ?>

                    <tr>

                        <td><?= $s["id_seance"] ?></td>

                        <!-- TITRE FILM -->
                        <td><?= $s["titre"] ?></td>

                        <!-- NOM SALLE -->
                        <td><?= $s["nom_salle"] ?></td>

                        <!-- VILLE -->
                        <td><?= $s["ville"] ?></td>

                        <td><?= $s["date_seance"] ?></td>

                        <td><?= $s["heure_seance"] ?></td>

                        <td><?= $s["prix_simple"] ?> FCFA</td>

                        <td><?= $s["prix_vip"] ?> FCFA</td>

                        <td><?= $s["types"] ?></td>

                        <td>

                            <a 
                            href="reserver.php?id=<?= $s['id_seance'] ?>" 

                            onclick="return confirm('Réserver cette séance ?')"

                            class="btn-reserver">

                                RESERVER

                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>
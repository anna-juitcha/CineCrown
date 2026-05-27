<?php
session_start();
require_once 'db.php';

// Récupérer la ville du caissier connecté via la session
$ville_caissier = $_SESSION['ville'] ?? null;

try {
    // On récupère aussi etat_reservation pour l'afficher
    // On filtre par ville du caissier si elle est définie en session
    // On montre : en_attente, NULL, et validée (pour voir le changement d'état)
    $sql = "SELECT r.id_reservation, u.nom_user, f.titre, s.date_seance, s.heure_seance, 
                   r.place, r.prix_total, sa.ville, r.etat_reservation
            FROM reservation r
            JOIN user u ON r.id_user = u.id_user
            JOIN seance s ON r.id_seance = s.id_seance
            JOIN film f ON s.id_film = f.id_film
            JOIN salle sa ON s.id_salle = sa.id_salle
            WHERE (r.etat_reservation = 'en_attente' OR r.etat_reservation IS NULL OR r.etat_reservation = 'validée')";

    // Filtrer par ville du caissier si la session contient sa ville
    if ($ville_caissier) {
        $sql .= " AND sa.ville = :ville";
        $sql .= " ORDER BY s.date_seance ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':ville' => $ville_caissier]);
    } else {
        $sql .= " ORDER BY s.date_seance ASC";
        $stmt = $pdo->query($sql);
    }

    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}


$reservation = $pdo->query("SELECT * FROM reservation WHERE etat_reservation = 'validée'")->fetchAll();


$querySum = $pdo->query("SELECT SUM(prix_total) AS Recette FROM reservation WHERE etat_reservation = 'validée' ");
$resultSum = $querySum->fetch();
$totalGeneral = $resultSum['Recette'] ?? 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Cinéma - Panel Caissier</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../index.css">

    <style>
        /* ============================================================
   style.css — CinéCrown Panel Caissier
   Palette : Noir #121212 | Bordeaux #800020 | Or #D4AF37
   ============================================================ */



/* ── Zone de contenu ────────────────────────────────────────── */
.main-content,
.container {
    margin-left: 300px;
    padding: 40px;
    max-width: 1100px;
    background: linear-gradient(90deg, #efc967 0%, #efe7bb 50%, #dbbb6c 100%);

}

/* ── Titre principal ────────────────────────────────────────── */
h1 {
    color: #D4AF37;
    font-size: 2.4rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    border-bottom: 2px solid #272526;
    padding-bottom: 12px;
    margin-bottom: 28px;
}

/* ── Info ville du caissier ─────────────────────────────────── */
.info-ville {
    text-align: right;
    font-size: 0.85rem;
    color: #D4AF37;
    margin-bottom: 12px;
}
.info-ville span { color: #171616; font-weight: 600; }

/* ── Filtre ville ───────────────────────────────────────────── */
.filter-container {
    margin-bottom: 20px;
    text-align: center;
    
    padding: 14px;
    border-radius: 8px;
    border: 1px solid #141414;
}

select#filterVille {
    background: #1a1a1a;
    color: #D4AF37;
    border: 1px solid #D4AF37;
    padding: 8px 14px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 600;
    font-family: inherit;
}

/* ── Tableau ────────────────────────────────────────────────── */
.styled-table {
    width: 100%;
    border-collapse: collapse;
    background: linear-gradient(105deg, #6e5c22, #dcc16a);
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #D4AF37;
}

.styled-table thead tr {
    background-color: #292828;
    color: #78621b;
    text-align: left;
    font-weight: 600;
    font-size: 0.88rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}

.styled-table th,
.styled-table td {
    padding: 14px 18px;
    border-bottom: 1px solid #2a2a2a;
    font-size: 1.1rem;
}

.styled-table tbody tr:hover {
    background: #424242;
}

.styled-table tbody tr:last-child td {
    border-bottom: none;
}

/* ── Prix ───────────────────────────────────────────────────── */
.price { color: #D4AF37; font-weight: 700; }

/* ── Texte doré (ville) ─────────────────────────────────────── */
.text-gold { color: #D4AF37; font-weight: 500; }

/* ── Badges état ────────────────────────────────────────────── */
.badge-attente {
    background: #2a1f00;
    color: #D4AF37;
    border: 1px solid #D4AF37;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}

.badge-validee {
    background: #0a2a14;
    color: #4ade80;
    border: 1px solid #4ade80;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}

.badge-refusee {
    background: #2a0a0a;
    color: #f87171;
    border: 1px solid #800020;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}

/* ── Ligne validée (légèrement estompée) ────────────────────── */
tr.ligne-validee { opacity: 0.55; }

/* ── Boutons ────────────────────────────────────────────────── */
button {
    font-family: inherit;
    font-weight: 600;
    font-size: 0.78rem;
    text-transform: uppercase;
    padding: 7px 14px;
    border-radius: 5px;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.25s;
    letter-spacing: 0.5px;
}

.flex-actions { display: flex; gap: 8px; }

.btn-valid {
    background: #D4AF37;
    color: #121212;
    border-color: #D4AF37;
}
.btn-valid:hover {
    background: #b8952e;
    transform: translateY(-1px);
}

.btn-refuse {
    background: transparent;
    color: #800020;
    border-color: #800020;
}
.btn-refuse:hover {
    background: #800020;
    color: #f1f1f1;
    transform: translateY(-1px);
}

/* ── Message vide ───────────────────────────────────────────── */
.no-data {
    text-align: center;
    padding: 24px;
    color: #D4AF37;
    font-style: italic;
}
    </style>
</head>
<body>
<?php require 'index_caisse.php'; ?>


<div class="container">

<div class="card-recette" style=" margin-top: 20px ;background: #000000; padding: 20px; border-radius: 12px; max-width: 300px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                <span style="font-size: 14px; color: #969494; font-weight: 500; text-transform: uppercase;">Recette total</span>
                <h3 style="font-size: 24px; color: #eae4e4; margin-top: 5px; font-weight: 700;">
                    <?= number_format($totalGeneral ?? 0,0,',','') ?>
                </h3>
        </div>
    <h1>Bienvenue à la Caisse : Réservations</h1>

    <!-- Affichage de la ville du caissier connecté -->
    <p class="info-ville">
        <?php if ($ville_caissier): ?>
            Affichage pour la ville : <span><?= htmlspecialchars($ville_caissier) ?></span>
        <?php else: ?>
            <span>Toutes les villes</span> (aucune ville en session)
        <?php endif; ?>
    </p>

    <!-- Filtre par ville (sert si le caissier n'a pas de ville en session) -->
    <?php if (!$ville_caissier): ?>
    <div class="filter-container">
        <label for="filterVille" class="text-gold">Filtrer par ville : </label>
        <select id="filterVille" onchange="filtrerParVille()">
            <option value="toutes">Toutes les villes</option>
            <option value="Douala">Douala</option>
            <option value="Yaounde">Yaoundé</option>
        </select>
    </div>
    <?php endif; ?>

    <table class="styled-table">
        <thead>
            <tr>
                <th>Client</th>
                <th>Film</th>
                <th>Ville</th>
                <th>Date / Heure</th>
                <th>Places</th>
                <th>Total (FCFA)</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($reservations)): ?>
            <tr>
                <td colspan="8" class="no-data">Aucune réservation à afficher.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($reservations as $res):
                $etat = $res['etat_reservation'] ?? 'en_attente';
                $estValidee = ($etat === 'validée');
                $estRefusee = ($etat === 'refusée');
            ?>
            <tr class="ligne-reservation <?= $estValidee ? 'ligne-validee' : '' ?>"
                id="resa-<?= $res['id_reservation'] ?>"
                data-ville="<?= htmlspecialchars($res['ville']) ?>"
                data-etat="<?= htmlspecialchars($etat) ?>">

                <td><?= htmlspecialchars($res['nom_user']) ?></td>
                <td><?= htmlspecialchars($res['titre']) ?></td>
                <td><span class="text-gold"><?= htmlspecialchars($res['ville']) ?></span></td>
                <td>
                    Le <?= date('d/m/Y', strtotime($res['date_seance'])) ?>
                    à <?= date('H:i', strtotime($res['heure_seance'])) ?>
                </td>
                <td style="text-align:center;"><?= $res['place'] ?></td>
                <td class="price"><strong><?= number_format($res['prix_total'], 0, ',', ' ') ?></strong></td>

                <!-- Colonne état avec badge coloré -->
                <td id="etat-<?= $res['id_reservation'] ?>">
                    <?php if ($etat === 'validée'): ?>
                        <span class="badge-validee">✔ Validée</span>
                    <?php elseif ($etat === 'refusée'): ?>
                        <span class="badge-refusee">✘ Refusée</span>
                    <?php else: ?>
                        <span class="badge-attente">⏳ En attente</span>
                    <?php endif; ?>
                </td>

                <!-- Actions : masquées si déjà traitée -->
                <td>
                    <?php if (!$estValidee && !$estRefusee): ?>
                    <div class="flex-actions">
                        <button class="btn-valid"  onclick="valider(<?= $res['id_reservation'] ?>)">Accepter</button>
                        <button class="btn-refuse" onclick="refuser(<?= $res['id_reservation'] ?>)">Refuser</button>
                    </div>
                    <?php else: ?>
                        <span style="color:#555; font-size:0.8rem;">—</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>

        <tr id="no-match-row" style="display:none;">
            <td colspan="8" class="no-data">Aucune réservation pour cette ville.</td>
        </tr>
        </tbody>
    </table>
</div>

<script>
// Quand on valide : la ligne ne disparaît plus, le badge passe à "Validée"
// et les boutons sont masqués. C'est liens.js qui appelle traitement_reservation.php
// On surcharge ici la fonction de callback après succès.

// Écoute l'événement personnalisé déclenché par liens.js après validation
document.addEventListener('reservationValidee', function(e) {
    const id = e.detail.id;
    const etatCell = document.getElementById('etat-' + id);
    const row = document.getElementById('resa-' + id);

    if (etatCell) etatCell.innerHTML = '<span class="badge-validee">✔ Validée</span>';
    if (row) {
        row.classList.add('ligne-validee');
        // Masquer les boutons
        const actions = row.querySelector('.flex-actions');
        if (actions) actions.innerHTML = '<span style="color:#555;font-size:0.8rem;">—</span>';
    }
});

// Écoute après refus
document.addEventListener('reservationRefusee', function(e) {
    const id = e.detail.id;
    const etatCell = document.getElementById('etat-' + id);
    const row = document.getElementById('resa-' + id);

    if (etatCell) etatCell.innerHTML = '<span class="badge-refusee">✘ Refusée</span>';
    if (row) {
        const actions = row.querySelector('.flex-actions');
        if (actions) actions.innerHTML = '<span style="color:#555;font-size:0.8rem;">—</span>';
    }
});
</script>

<script src="liens.js"></script>
</body>
</html>
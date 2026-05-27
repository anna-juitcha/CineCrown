<?php
require_once 'db.php';

// 1. IMPORTATION ET CHARGEMENT DE PHPMAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$id_res = $_POST['id'] ?? null;
$action = $_POST['action'] ?? null;
$raison = $_POST['raison'] ?? '';

if (!$id_res || !$action) {
    echo "Erreur : Données incomplètes.";
    exit;
}

// Préparer l'objet Mail globalement
$mail = new PHPMailer(true);

try {
    // Configuration SMTP commune pour Gmail
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; 
    $mail->SMTPAuth   = true;
    $mail->Username   = 'angelaloumou158@gmail.com';         // Ton adresse qui a cree le compte CineCrown
    $mail->Password   = 'rikvgsztssaarxyk';  // Ta clé d'application à 16 caractères
    $mail->SMTPSecure = 'tls';                         // Sécurisé sans bug de constante
    $mail->Port       = 587; 
    $mail->CharSet    = 'UTF-8';
    $mail->setFrom('CineCrown@gmail.com', 'CineCrown');

    if ($action == 'accepter') {
        // --- CAS : ACCEPTER ---
        
        // 1. RÉCUPÉRATION DES INFOS
        $sqlInfo = "SELECT r.id_seance, s.capacite, r.place, u.email, u.nom_user, f.titre, se.date_seance, se.heure_seance, sa.ville, sa.nom_salle, r.prix_total
                    FROM reservation r 
                    JOIN seance se ON r.id_seance = se.id_seance 
                    JOIN salle s ON se.id_salle = s.id_salle
                    JOIN user u ON r.id_user = u.id_user
                    JOIN film f ON se.id_film = f.id_film
                    JOIN salle sa ON se.id_salle = sa.id_salle
                    WHERE r.id_reservation = ?";
        $stmtInfo = $pdo->prepare($sqlInfo);
        $stmtInfo->execute([$id_res]);
        $info = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        if (!$info) {
            echo "Erreur : Réservation introuvable.";
            exit;
        }

        // 2. Vérification des places disponibles
        $sqlCount = "SELECT SUM(place) as total FROM reservation WHERE id_seance = ? AND etat_reservation = 'validée'";
        $stmtCount = $pdo->prepare($sqlCount);
        $stmtCount->execute([$info['id_seance']]);
        $places_occupees = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        if (($places_occupees + $info['place']) > $info['capacite']) {
            echo "Refus : Salle pleine. Il reste " . ($info['capacite'] - $places_occupees) . " places.";
            exit;
        }

        // 3. Validation dans la base de données
        $update = $pdo->prepare("UPDATE reservation SET etat_reservation = 'validée' WHERE id_reservation = ?");
        $update->execute([$id_res]);

        // 4. ENVOI DU TICKET PAR EMAIL
        $mail->addAddress($info['email'], $info['nom_user']); 
        $mail->isHTML(true);
        $mail->Subject = "Votre Ticket Numérique - " . $info['titre'];
        
        $mail->Body = "
        <div style='background: #121212; padding: 20px; font-family: Arial, sans-serif; text-align: center; color: #fff;'>
            <div style='max-width: 500px; margin: 0 auto; background: #1c1c1c; border: 2px solid #D4AF37; border-radius: 10px; overflow: hidden;'>
                <div style='background: #800020; padding: 15px;'>
                    <h2 style='color: #D4AF37; margin: 0; letter-spacing: 1px;'>CINÉCROWN - TICKET NUMÉRIQUE</h2>
                </div>
                <div style='padding: 20px; text-align: left; line-height: 1.6;'>
                    <p><strong>Client :</strong> {$info['nom_user']}</p>
                    <p><strong>Film :</strong> <span style='color: #D4AF37; font-size: 18px;'>{$info['titre']}</span></p>
                    <p><strong>Lieu :</strong> CineCrown ({$info['ville']}) - {$info['nom_salle']}</p>
                    <p><strong>Séance :</strong> Le ".date('d/m/Y', strtotime($info['date_seance']))." à ".date('H:i', strtotime($info['heure_seance']))."</p>
                    <p><strong>Nombre de places :</strong> {$info['place']}</p>
                    <hr style='border: 0; border-top: 1px dashed #D4AF37; margin: 20px 0;'>
                    <p style='text-align: center; font-size: 20px; color: #D4AF37; font-weight: bold;'>Montant Payé : {$info['prix_total']} FCFA</p>
                    <p style='text-align: center; font-size: 12px; color: #aaa;'>Présentez cet email à l'entrée de la salle.</p>
                </div>
            </div>
        </div>";

        $mail->send();
        echo "Succès";

    } else {
        // --- CAS : REFUSER ---

        // 1. SÉCURITÉ : VÉRIFIER LE MOTIF
        if (empty(trim($raison))) {
            $raison = "Motif non spécifié par le service de caisse.";
        }

        // 2. RÉCUPÉRATION DES INFOS CLIENT & FILM
        $sqlInfo = "SELECT u.email, u.nom_user, f.titre 
                    FROM reservation r 
                    JOIN user u ON r.id_user = u.id_user
                    JOIN seance se ON r.id_seance = se.id_seance
                    JOIN film f ON se.id_film = f.id_film
                    WHERE r.id_reservation = ?";
        $stmtInfo = $pdo->prepare($sqlInfo);
        $stmtInfo->execute([$id_res]);
        $info = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        if (!$info) {
            echo "Erreur : Réservation introuvable.";
            exit;
        }

        // 3. MISE À JOUR DE LA BASE DE DONNÉES (Correction ici : les deux paramètres sont bien envoyés)
        $update = $pdo->prepare("UPDATE reservation SET etat_reservation = 'refusée', motif_refus = ? WHERE id_reservation = ?");
        $update->execute([$raison, $id_res]);

        // 4. ENVOI DE L'EMAIL DE REFUS
        $mail->addAddress($info['email'], $info['nom_user']); 
        $mail->isHTML(true);
        $mail->Subject = "Évolution de votre réservation - " . $info['titre'];
        
        $mail->Body = "
        <div style='background: #121212; padding: 20px; font-family: Arial, sans-serif; text-align: center; color: #fff;'>
            <div style='max-width: 500px; margin: 0 auto; background: #1c1c1c; border: 2px solid #800020; border-radius: 10px; overflow: hidden;'>
                <div style='background: #800020; padding: 15px;'>
                    <h2 style='color: #fff; margin: 0;'>Présentation Annulée</h2>
                </div>
                <div style='padding: 20px; text-align: left; line-height: 1.6;'>
                    <p>Bonjour <strong>{$info['nom_user']}</strong>,</p>
                    <p>Nous avons le regret de vous informer que votre demande de réservation pour le film <span style='color: #D4AF37;'><strong>{$info['titre']}</strong></span> n'a pas pu être validée par notre équipe.</p>
                    
                    <div style='background: #262626; border-left: 4px solid #800020; padding: 10px; margin: 20px 0;'>
                        <p style='margin: 0; font-weight: bold; color: #800020;'>Motif du refus :</p>
                        <p style='margin: 5px 0 0 0; font-style: italic; color: #ccc;'>\" {$raison} \"</p>
                    </div>
                    
                    <p>Votre compte n'a pas été débité. Nous vous invitons à choisir une autre séance depuis notre application.</p>
                    <hr style='border: 0; border-top: 1px dashed #333; margin: 20px 0;'>
                    <p style='text-align: center; font-size: 12px; color: #aaa;'>L'équipe de gestion de CineCrown.</p>
                </div>
            </div>
        </div>";

        $mail->send();
        echo "Succès";
    }
} 
catch (Exception $e) {
    echo "Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
}
?>
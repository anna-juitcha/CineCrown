<?php
require '../db.php';

$film = $pdo->query("SELECT * FROM film")->fetchAll();
$salle = $pdo->query("SELECT * FROM salle")->fetchAll();
$id_seance = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM seance WHERE id_seance = ?");
$stmt->execute([$id_seance]);
$seance = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_film = $_POST['id_film'];
    $id_salle = $_POST['id_salle'];
    $date = $_POST['date_seance'];
    $heure =$_POST['heure_seance'];
    $prixs = $_POST['prix_simple'];
    $prixv = $_POST['prix_vip'];
    $type = $_POST['types'];

        
        $sql =("UPDATE seance SET id_film = ?,id_salle = ?,date_seance = ?,heure_seance = ?,prix_simple = ?,prix_vip = ?,types = ? WHERE id_seance = ?");
                
        $state = $pdo->prepare($sql);
        $state->execute([$id_film, $id_salle,$date,$heure, $prixs,$prixv, $type, $id_seance]);
header("location: gestion_seance.php");

    
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
            <select name="id_film" id="id_film" required>
                
                <option value="<?= $seance["id_film"]?>"><?= $seance["id_film"]?></option>
                <?php foreach ($film as $f): ?>
                <option value="<?= $f["id_film"]?>"><?= $f["id_film"]?> --- <?= $f["titre"]?></option>
                <?php endforeach; ?>
            </select>
            
            <select name="id_salle" id="id_sale" required>
                
                <option value="<?= $seance["id_salle"]?>"><?= $seance["id_salle"]?></option>
                <?php foreach ($salle as $sa): ?>
                <option value="<?= $sa["id_salle"]?>"><?= $sa["id_salle"]?> --- <?= $sa["nom_salle"]?></option>
                <?php endforeach; ?>
            </select>
            
            <input type="date" name="date_seance" value="<?= $seance["date_seance"]?>" required>
            <input type="time" name="heure_seance" value="<?= $seance["heure_seance"]?>" required>
            <input type="number" name="prix_simple" value="<?= $seance["prix_simple"]?>" required>
            <input type="number" name="prix_vip" value="<?= $seance["prix_vip"]?>" required>
            <input type="text" name="types" value="<?= $seance["types"]?>" required>
            <button type="submit">MODIFIER</button>
            </form>
    </div>
</body>
</html>

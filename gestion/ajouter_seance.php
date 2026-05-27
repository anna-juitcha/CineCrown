<?php
require '../db.php';

$film = $pdo->query("SELECT * FROM film")->fetchAll();
$salle = $pdo->query("SELECT * FROM salle")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_film = $_POST['id_film'];
    $id_salle = $_POST['id_salle'];
    $date = $_POST['date_seance'];
    $heure =$_POST['heure_seance'];
    $prixs = $_POST['prix_simple'];
    $prixv = $_POST['prix_vip'];
    $type = $_POST['types'];

        #requete preparer
        $state = $pdo->prepare("INSERT INTO seance (id_film,id_salle,date_seance,heure_seance,prix_simple,prix_vip,types) VALUES (?,?,?,?,?,?,?)");
        if($state->execute([$id_film, $id_salle,$date,$heure, $prixs,$prixv, $type])) {
            header("location: gestion_seance.php");
            exit;
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
        <h2>Ajouter une seance</h2>
        <form method="POST">
            <select name="id_film" id="id_film" required>
                <?php foreach ($film as $f): ?>
                
                <option value="<?= $f["id_film"]?>"><?= $f["id_film"]?> --- <?= $f["titre"]?></option>
                <?php endforeach; ?>
            </select>
            
            <select name="id_salle" id="id_sale" required>
                <?php foreach ($salle as $sa): ?>
                <option value="<?= $sa["id_salle"]?>"><?= $sa["id_salle"]?> --- <?= $sa["nom_salle"]?></option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="date_seance" placeholder="Date de la seance" required>
            <input type="time" name="heure_seance" placeholder="Heure de le seance" required>
            <input type="number" name="prix_simple" placeholder="prix d'une place simple " required>
            <input type="number" name="prix_vip" placeholder="prix d'une place VIP" required>
            <input type="text" name="types" placeholder="type" required>
            <button type="submit">AJOUTER</button>
        </form>
    </div>
</body>
</html>

<?php
require '../db.php';

$id_salle = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM salle WHERE id_salle = ?");
$stmt->execute([$id_salle]);
$salle = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom_salle'];
    $capacite = $_POST['capacite'];
    $ville = $_POST['ville'];

        #requete preparer
        $state = $pdo->prepare("UPDATE salle SET nom_salle = ?,capacite = ?,ville= ? WHERE id_salle = ?");
        if($state->execute([$nom,$capacite, $ville,$id_salle])) {
            header("location: gestion_salle.php");
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
        <h2>Ajouter une salle</h2>
        <form method="POST">
            <input type="text" name="nom_salle" value="<?= $salle["nom_salle"]?>"  required>
            <input type="number" name="capacite" value="<?= $salle["capacite"]?>" required>
            <select name="ville" id="ville">
                <option value="<?= $salle["ville"]?>"><?= $salle["ville"]?></option>
                <option value="Douala">DOUALA</option>
                <option value="Yaounde">YAOUNDE</option>
            </select>
            <button type="submit">AJOUTER</button>
        </form>
    </div>
</body>
</html>

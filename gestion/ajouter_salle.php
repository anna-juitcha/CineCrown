<?php
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom_salle'];
    $capacite = $_POST['capacite'];
    $ville = $_POST['ville'];

        #requete preparer
        $state = $pdo->prepare("INSERT INTO salle (nom_salle,capacite,ville) VALUES (?,?,?)");
        if($state->execute([$nom,$capacite, $ville])) {
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
            <input type="text" name="nom_salle" placeholder="entrez le nom de la salle" required>
            <input type="number" name="capacite" placeholder="entrez la capacite de la salle" required>
            <select name="ville" id="ville">
                <option value="Douala">DOUALA</option>
                <option value="Yaounde">YAOUNDE</option>
            </select>
            <button type="submit">AJOUTER</button>
        </form>
    </div>
</body>
</html>

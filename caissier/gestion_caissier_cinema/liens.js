  // Fonction pour envoyer la validation en AJAX
  function valider(id) {
    if(confirm("Voulez-vous valider cette réservation et envoyer le ticket ?")) {
        envoyerAction(id, 'accepter');
    }
}

// Fonction pour envoyer le refus en AJAX
function refuser(id) {
    let raison = prompt("Raison du refus (ex: Informations incomplètes) :");
    if (raison !== null) {
        envoyerAction(id, 'refuser', raison);
    }
}

function envoyerAction(id, action, raison = "") {
    // FormData pour envoyer les données au PHP
    let formData = new FormData();
    formData.append('id', id);
    formData.append('action', action);
    formData.append('raison', raison);

    fetch('traitement_reservation.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "Succès") {
            // Animation de sortie
            let ligne = document.getElementById('resa-' + id);
            ligne.style.transition = "0.5s";
            ligne.style.transform = "translateX(50px)";
            ligne.style.opacity = "0";
            setTimeout(() => ligne.remove(), 500);
        } else {
            alert("Alerte : " + data);
        }
    })
    .catch(error => alert("Erreur de connexion au serveur"));
}

function filtrerParVille() {
    // 1. Récupérer la ville sélectionnée
    const villeSelectionnee = document.getElementById('filterVille').value;
    
    // 2. Récupérer toutes les lignes de réservations et la ligne "aucun résultat"
    const lignes = document.querySelectorAll('.ligne-reservation');
    const ligneAucunResultat = document.getElementById('no-match-row');
    
    let compteurLignesVisibles = 0;

    // 3. Boucle sur chaque ligne pour l'afficher ou la masquer
    lignes.forEach(ligne => {
        const villeLigne = ligne.getAttribute('data-ville');

        if (villeSelectionnee === 'toutes' || villeLigne === villeSelectionnee) {
            ligne.style.display = ''; // Affiche la ligne
            compteurLignesVisibles++;
        } else {
            ligne.style.display = 'none'; // Masque la ligne
        }
    });

    // 4. Si le compteur est à 0, on affiche le message "Aucune réservation pour cette ville"
    if (compteurLignesVisibles === 0) {
        ligneAucunResultat.style.display = '';
    } else {
        ligneAucunResultat.style.display = 'none';
    }
}
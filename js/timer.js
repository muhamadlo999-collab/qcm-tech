
// timer.js — Compte à rebours QCM

// Crée une fonction qui s'exécute toute seule
(function() {
    // Récupère l'élément qui affiche le temps restant
    const display = document.getElementById('timer-display');

    // Récupère le bloc complet du chronomètre
    const timerEl = document.getElementById('timer');

    // Récupère le formulaire du QCM
    const form = document.getElementById('qcm-form');

    // Si l'affichage du temps ou le formulaire n'existe pas, on arrête le script
    if (!display || !form) return;

    // Définit le nombre de secondes du QCM
    let secondes = (typeof dureeMinutes !== 'undefined' ? dureeMinutes : 10) * 60;

    // Fonction qui ajoute un zéro devant les nombres inférieurs à 10
    function pad(n) {
        // Retourne le nombre avec au moins 2 chiffres
        return String(n).padStart(2, '0');
    }

    // Fonction qui met à jour le chronomètre
    function update() {
        // Calcule les minutes restantes
        const m = Math.floor(secondes / 60);

        // Calcule les secondes restantes
        const s = secondes % 60;

        // Affiche le temps au format minutes:secondes
        display.textContent = pad(m) + ':' + pad(s);

        // Si le temps restant est entre 1 et 2 minutes
        if (secondes <= 120 && secondes > 60) {
            // Ajoute une classe d'avertissement au chronomètre
            timerEl.classList.add('timer-warning');
        }

        // Si le temps restant est inférieur ou égal à 1 minute
        if (secondes <= 60) {
            // Retire la classe d'avertissement
            timerEl.classList.remove('timer-warning');

            // Ajoute une classe de danger au chronomètre
            timerEl.classList.add('timer-danger');
        }

        // Si le temps est terminé
        if (secondes <= 0) {
            // Arrête le compte à rebours
            clearInterval(interval);

            // Affiche 00:00
            display.textContent = '00:00';

            // Envoie automatiquement le formulaire
            form.submit();

            // Arrête la fonction
            return;
        }

        // Retire une seconde au compteur
        secondes--;
    }

    // Lance une première mise à jour immédiatement
    update();

    // Relance la fonction update toutes les secondes
    const interval = setInterval(update, 1000);
})();

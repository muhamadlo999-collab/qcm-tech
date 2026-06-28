// antitricherie.js — Système anti-triche QCM
(function() {
    const overlay     = document.getElementById('overlay-anticheat');
    const overlayMsg  = document.getElementById('overlay-msg');
    const overlayTitle = document.getElementById('overlay-title');
    const flag        = document.getElementById('invalide-flag');
    if (!overlay) return;

    let avertissements = 0;

    function showOverlay(titre, msg) {
        overlayTitle.textContent = titre;
        overlayMsg.textContent   = msg;
        overlay.style.display    = 'flex';
    }

    // Plein écran
    document.addEventListener('fullscreenchange', function() {
        if (!document.fullscreenElement) {
            avertissements++;
            if (avertissements >= 3) {
                flag.value = 1;
                showOverlay('⚠️ Tentative invalidée', 'Tu as quitté le plein écran 3 fois. Cette tentative est invalidée.');
            } else {
                showOverlay('⚠️ Plein écran quitté', 'Tu as quitté le plein écran (' + avertissements + '/3). Reviens sinon ta tentative sera invalidée.');
            }
        }
    });

    // Changement d'onglet
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            avertissements++;
            flag.value = 1;
            showOverlay('⚠️ Changement d\'onglet détecté', 'Tu as changé d\'onglet. Cette tentative a été invalidée.');
        }
    });

    // Clic droit désactivé
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // Copier/coller désactivé
    document.addEventListener('copy',  function(e) { e.preventDefault(); });
    document.addEventListener('paste', function(e) { e.preventDefault(); });
    document.addEventListener('cut',   function(e) { e.preventDefault(); });

    // Raccourcis clavier suspects
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && ['c','v','x','u','s','p'].includes(e.key.toLowerCase())) {
            e.preventDefault();
        }
        if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
            e.preventDefault();
        }
    });
})();
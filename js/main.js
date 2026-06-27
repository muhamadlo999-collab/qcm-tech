document.addEventListener('DOMContentLoaded', function() {

    // Validation formulaires
    document.querySelectorAll('form.auth-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            let ok = true;
            form.querySelectorAll('input[required]').forEach(function(input) {
                if (!input.value.trim()) {
                    input.closest('.form-group').classList.add('error');
                    ok = false;
                } else {
                    input.closest('.form-group').classList.remove('error');
                }
            });
            if (!ok) e.preventDefault();
        });
        form.querySelectorAll('input').forEach(function(input) {
            input.addEventListener('input', function() {
                input.closest('.form-group')?.classList.remove('error');
            });
        });
    });

    // Sélection réponse QCM — feedback visuel
    document.querySelectorAll('.choice-label').forEach(function(label) {
        label.addEventListener('click', function() {
            const group = label.closest('.q-choices');
            group.querySelectorAll('.choice-label').forEach(l => l.classList.remove('selected'));
            label.classList.add('selected');
        });
    });

    // Animation cartes catégorie
    document.querySelectorAll('.launch-card').forEach(function(card) {
        card.addEventListener('change', function() {
            document.querySelectorAll('.launch-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
        });
    });

    // Alertes auto-close
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 400);
        }, 4000);
    });
});
/* ==========================================================
   LEBON — Shared JavaScript
   Fonctions communes à toutes les vues
   ========================================================== */

/* ---- Mobile sidebar ---- */
function toggleMobileMenu() {
    document.getElementById('mobileSidebar')?.classList.toggle('open');
    document.getElementById('overlay')?.classList.toggle('open');
}

/* ---- Modal de déconnexion ---- */
function openLogoutModal() {
    document.getElementById('logoutModal')?.classList.add('open');
}

function closeLogoutModal() {
    document.getElementById('logoutModal')?.classList.remove('open');
}

/* ---- Initialisation au chargement ---- */
document.addEventListener('DOMContentLoaded', () => {

    /* Fermer la modal en cliquant en dehors */
    const logoutModal = document.getElementById('logoutModal');
    if (logoutModal) {
        logoutModal.addEventListener('click', e => {
            if (e.target === logoutModal) closeLogoutModal();
        });
    }

    /* Auto-hide des messages flash après 5 s */
    setTimeout(() => {
        const flash = document.querySelector('.flash');
        if (flash) {
            flash.style.transition = 'opacity 0.3s';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 300);
        }
    }, 5000);

    /* Fermer le menu mobile au clic sur un lien */
    document.querySelectorAll('.mobile-sidebar .nav-item, .mobile-sidebar .logout-btn')
        .forEach(el => el.addEventListener('click', toggleMobileMenu));

    /* Filtres annonces (page index) */
    const filterBtns = document.querySelectorAll('.filter-btn');
    if (filterBtns.length) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const filter = btn.dataset.filter;
                document.querySelectorAll('.ad-card').forEach(card => {
                    card.style.display = (filter === 'all' || card.dataset.status === filter) ? '' : 'none';
                });
            });
        });
    }

    /* Copie du lien de partage (page show) */
    window.copyShare = function () {
        const input = document.getElementById('shareInput');
        if (!input) return;
        input.select();
        navigator.clipboard.writeText(input.value).then(() => {
            const btn = document.getElementById('copyBtn');
            if (btn) {
                btn.textContent = 'Copié !';
                setTimeout(() => btn.textContent = 'Copier', 2000);
            }
        });
    };
});

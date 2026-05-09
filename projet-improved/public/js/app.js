/**
 * ESEN E-Learn – Scripts JavaScript principaux
 * Animations, interactions, validation formulaires
 */

document.addEventListener('DOMContentLoaded', function () {

  // ── Compteurs animés (page d'accueil) ──
  const counters = document.querySelectorAll('.stat-item h3[data-target]');
  if (counters.length > 0) {
    const animateCounter = (el) => {
      const target = parseInt(el.getAttribute('data-target'), 10);
      let current = 0;
      const step = Math.ceil(target / 60);
      const timer = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = current.toLocaleString('fr-FR');
        if (current >= target) clearInterval(timer);
      }, 30);
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    counters.forEach(c => observer.observe(c));
  }

  // ── Filtres de cours (page catalogue) ──
  const filtresBtns = document.querySelectorAll('.filtre-btn');
  const coursCards  = document.querySelectorAll('.cours-card');

  filtresBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      // Mettre à jour le bouton actif
      filtresBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');

      const filtre = this.getAttribute('data-filtre');

      // Filtrer les cartes avec animation
      coursCards.forEach(card => {
        const categorie = card.getAttribute('data-categorie');
        if (filtre === 'tout' || categorie === filtre) {
          card.style.display = 'block';
          card.style.animation = 'fadeIn 0.3s ease';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  // ── Fermeture automatique des alertes flash ──
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.opacity = '0';
      alert.style.transition = 'opacity 0.5s';
      setTimeout(() => alert.remove(), 500);
    }, 5000);
  });

  // ── Confirmation de suppression ──
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', function (e) {
      if (!confirm(this.getAttribute('data-confirm'))) {
        e.preventDefault();
      }
    });
  });

  // ── Sidebar admin toggle (mobile) ──
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.querySelector('.admin-sidebar');
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('sidebar-open');
    });
  }

  // ── Recherche en temps réel (tableau admin) ──
  const searchInput = document.getElementById('liveSearch');
  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const terme = this.value.toLowerCase();
      const rows  = document.querySelectorAll('.data-table tbody tr');
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(terme) ? '' : 'none';
      });
    });
  }

  // ── Validation formulaire inscription ──
  const formInscription = document.getElementById('formInscription');
  if (formInscription) {
    formInscription.addEventListener('submit', function (e) {
      const mdp  = document.getElementById('mot_de_passe');
      const conf = document.getElementById('confirmation');
      if (mdp && conf && mdp.value !== conf.value) {
        e.preventDefault();
        showError(conf, 'Les mots de passe ne correspondent pas');
      }
    });
  }

  // ── Barres de progression animées ──
  document.querySelectorAll('.progress-bar[data-width]').forEach(bar => {
    const width = bar.getAttribute('data-width');
    setTimeout(() => { bar.style.width = width + '%'; }, 200);
  });

  // ── Helpers ──
  function showError(input, message) {
    const existing = input.parentElement.querySelector('.error-msg');
    if (existing) existing.remove();
    const err = document.createElement('div');
    err.className = 'error-msg';
    err.style.cssText = 'color:#dc3545;font-size:0.83rem;margin-top:4px';
    err.textContent = message;
    input.parentElement.appendChild(err);
    input.style.borderColor = '#dc3545';
    input.focus();
  }
});

// ── Styles animation CSS injectés ──
const style = document.createElement('style');
style.textContent = `
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .sidebar-open { transform: translateX(0) !important; }
`;
document.head.appendChild(style);

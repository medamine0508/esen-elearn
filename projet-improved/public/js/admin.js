// ESEN E-Learn – Admin JS
document.addEventListener('DOMContentLoaded', function () {
  const toggle  = document.getElementById('sidebarToggle');
  const sidebar = document.querySelector('.admin-sidebar');
  if (toggle && sidebar) {
    toggle.addEventListener('click', function () {
      // Mobile: slide in/out; Desktop: collapse/expand
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle('open');
      } else {
        sidebar.classList.toggle('collapsed');
      }
    });
  }
});


document.addEventListener('DOMContentLoaded', function() {
    // Detecta se está fora da home
    const isHome = window.location.pathname.endsWith('home.php') || window.location.pathname === '/' || window.location.pathname === '/index.php';

    // Links do menu
    const homeLink = document.getElementById('home-menu');
    const catalogoLink = document.getElementById('catalogo-menu');

    if (!isHome) {
        homeLink.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '/pages/home.php';
        });
        catalogoLink.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '/pages/home.php#catalogo';
        });
    }
});

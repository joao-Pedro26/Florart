document.addEventListener("DOMContentLoaded", () => {
  const menu = document.getElementById('menu-inferior');
  const menuItems = document.querySelectorAll('.menu-item');
  const currentPath = window.location.pathname.replace(/\/$/, "");
  const currentHash = window.location.hash;

  // Função: destacar item ativo
  menuItems.forEach(item => {
    const href = item.getAttribute("href");
    if (!href || href === "#") return; // ignora botões e href="#" do home

    const link = new URL(href, window.location.origin);
    const linkPath = link.pathname.replace(/\/$/, "");
    const linkHash = link.hash;

    const isHome = (currentPath === "/" || currentPath === "" || currentPath.endsWith("home.php")) && linkPath.endsWith("home.php" );

    if (currentPath === linkPath || isHome) {
      item.classList.add("ativo");
    } else {
      item.classList.remove("ativo");
    }
  });

  // Redirecionamento especial para home
  const homeLink = document.getElementById('home-menu-inferior');
  if (homeLink && !(currentPath === "/" || currentPath.endsWith("home.php") || currentPath.endsWith("index.php"))) {
    homeLink.addEventListener('click', e => {
      e.preventDefault();
      window.location.href = '/pages/home.php';
    });
  }

  // Scroll menu hide/show
  let lastScrollTop = 0;
  window.addEventListener('scroll', function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop) {
      menu.classList.add('hide');
    } else {
      menu.classList.remove('hide');
    }
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
  });

  // Clique nos itens do menu: marcar ativo
  menuItems.forEach(item => {
    item.addEventListener('click', () => {
      menuItems.forEach(i => i.classList.remove('ativo'));
      item.classList.add('ativo');
    });
  });
});

const check = document.getElementById('check');
const menuLinks = document.querySelectorAll('.menu a');

menuLinks.forEach(link => {
    link.addEventListener('click', () => {
        check.checked = false;
    });
});
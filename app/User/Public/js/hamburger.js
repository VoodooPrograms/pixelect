

const menuButton = document.getElementById('menu-button');
const menu = document.querySelector('nav');
menuButton.addEventListener('click', () => {
    menu.classList.toggle('show');
    menuButton.classList.toggle('show');
});
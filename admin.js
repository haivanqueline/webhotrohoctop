let body = document.body;
let profile = document.querySelector('.header .flex .profile');
let sidebar = document.querySelector('.side-bar');
let searchform = document.querySelector('.header .flex .search-form');
let togglebt = document.querySelector('#toggle-bt');
let darkmode = localStorage.getItem('dark-mode');
document.querySelector('#user-bt').onclick = () => {
    profile.classList.toggle('active');
    searchform.classList.remove('active');
};
document.querySelector('#search-bt').onclick = () => {
    searchform.classList.toggle('active');
    profile.classList.remove('active');
};

document.querySelector('#menu-bt').onclick = () => {
    sidebar.classList.toggle('active');
    body.classList.toggle('active');
};
document.querySelector('#close-bar').onclick = () => {
    sidebar.classList.remove('active');
};
window.onscroll = () => {
    profile.classList.remove('active');
    searchform.classList.remove('active');
    if (window.innerWidth < 1200) {
        sidebar.classList.remove('active');
        body.classList.remove('active');
    }
};
const enabledarkmode = () => {
    togglebt.classList.replace('fa-sun', 'fa-moon');
    body.classList.add('dark');
    localStorage.setItem('dark-mode', 'enabled');
};
const disabledarkmode = () => {
    togglebt.classList.replace('fa-moon', 'fa-sun');
    body.classList.remove('dark');
    localStorage.setItem('dark-mode', 'disabled');
};

togglebt.onclick = (e) => {
    let darkmode = localStorage.getItem('dark-mode');
    if (darkmode === 'disabled') {
        enabledarkmode();
    } else {
        disabledarkmode();
    }
}
if (darkmode === 'enabled') {
    enabledarkmode();
}
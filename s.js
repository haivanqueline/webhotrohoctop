let body = document.body;
let profile = document.querySelector('.header .flex .profile');
let searchform = document.querySelector('.header .flex .search-form');
let sidebar = document.querySelector('.side-bar');
document.querySelector('#user-bt').onclick = () => {
    profile.classList.toggle('active');
    searchform.classList.remove('active');
}

document.querySelector('#search-bt').onclick = () => {
    searchform.classList.toggle('active');
    profile.classList.remove('active');

}
document.querySelector('#menu-bt').onclick = () => {
    sidebar.classList.toggle('active');
    body.classList.toggle('active')
}
document.querySelector('.side-bar .close-side-bar').onclick = () => {
    sidebar.classList.remove('active');
    body.classList.remove('active');
}

window.onscroll = () => {
    profile.classList.remove('active');
    searchform.classList.remove('active');
    if (window.innerWidth < 1200) {
        sidebar.classList.remove('active');
        body.classList.remove('active');
    }
}
let togglebt = document.querySelector('#sun-bt');
let darkmode = localStorage.getItem('dark-mode');
const enabledarkmode = () => {
    togglebt.classList.replace('fa-sun', 'fa-moon');
    body.classList.add('dark');
    localStorage.setItem('dark-mode', 'enable')
};
const disabledarkmode = () => {
    togglebt.classList.replace('fa-moon', 'fa-sun');
    body.classList.remove('dark');
    localStorage.setItem('dark-mode', 'disable')
};
if (darkmode === 'enable') {
    enabledarkmode();
}
togglebt.onclick = (e) => {
    let darkmode = localStorage.getItem('dark-mode');
    if (darkmode === 'disable') {
        enabledarkmode();
    }
    else {
        disabledarkmode();
    }
}
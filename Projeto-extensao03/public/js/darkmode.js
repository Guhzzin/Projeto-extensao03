document.addEventListener('DOMContentLoaded', () => {
    const htmlElement = document.documentElement; 
    const btnDarkMode = document.getElementById('btn-dark-mode');
    
    // Se o botão não existir nesta página, para o script aqui
    if (!btnDarkMode) return; 

    const iconDarkMode = btnDarkMode.querySelector('i');

    //  Verifica se o usuário já tinha escolhido o tema escuro antes
    const temaSalvo = localStorage.getItem('tema');
    if (temaSalvo === 'dark') {
        htmlElement.setAttribute('data-bs-theme', 'dark');
        iconDarkMode.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
    }

    //  Quando o usuário clicar no botao
    btnDarkMode.addEventListener('click', () => {
        const temaAtual = htmlElement.getAttribute('data-bs-theme');
        
        if (temaAtual === 'dark') {
            // Volta pro Claro
            htmlElement.setAttribute('data-bs-theme', 'light');
            localStorage.setItem('tema', 'light');
            iconDarkMode.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
        } else {
            // Vai pro Escuro
            htmlElement.setAttribute('data-bs-theme', 'dark');
            localStorage.setItem('tema', 'dark');
            iconDarkMode.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
        }
    });
});
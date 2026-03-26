
document.getElementById('formCadastro').addEventListener('submit', function(event) {
    const senha = document.getElementById('password').value;
    const confirmaSenha = document.getElementById('confirmPassword').value;
    const msgErro = document.getElementById('msgErro');


    if (senha !== confirmaSenha) {
        // Impede o envio do formulário
        event.preventDefault();
        

        // msg de erro
        msgErro.classList.remove('d-none');
        

        // Destaca os campos em vermelho
        document.getElementById('confirmPassword').style.borderColor = "#f73f3f";
    } else {
        msgErro.classList.add('d-none');
    }
});
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', function(event) {
        var correo = document.getElementById('correoLogin').value;
        var password = document.getElementById('passwordLogin').value;

        if (!correo || !password) {
            event.preventDefault();
            alert('Por favor, completa todos los campos.');
        }
    });
}

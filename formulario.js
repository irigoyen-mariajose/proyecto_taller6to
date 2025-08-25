const registroForm = document.getElementById('registroForm');
const mensajeErrorDiv = document.getElementById('mensajeError');
const mensajeExitoDiv = document.getElementById('mensajeExito');

registroForm.addEventListener('submit', function(event) {
});
event.preventDefault();

const nombre = document.getElementById('nombre').value;
const email = document.getElementById('email').value;
const contraseña = document.getElementById('contraseña').value;

mensajeErrorDiv.textContent = '';
mensajeExitoDiv.textContent = ''; 

if (nombre === '' || email === '' || contraseña === '') {
    mensajeErrorDiv.textContent = 'Por favor, completa todos los campos.';
    return; // Detiene la ejecución si hay errores
}

mensajeExitoDiv.textContent = '¡Registro exitoso! Bienvenido a Matezen, ' + nombre + '.';

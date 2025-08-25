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
fetch('http://localhost/guardar_datos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // o 'application/json' si el PHP lo espera así
        },
        body: new URLSearchParams(datosParaEnviar) // Convierte el objeto a un formato compatible
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta del servidor:', data);
        // Puedes mostrar un mensaje al usuario o actualizar la interfaz
    })
    .catch(error => {
        console.error('Error al enviar los datos:', error);
    });

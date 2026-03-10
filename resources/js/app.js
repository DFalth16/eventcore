import './bootstrap';


// Función para obtener el clima
async function cargarClima() {
    try {
        // Hacemos el llamado a tu endpoint de Laravel
        const response = await fetch('/api/external-todos?city=La Paz');
        const data = await response.json();

        if (data.status === 'success') {
            const weather = data.weather;
            // Suponiendo que tienes un <div id="weather-widget"></div> en tu HTML
            const widget = document.getElementById('weather-widget');
            widget.innerHTML = `
                <p>${data.city}: ${weather.main.temp}°C</p>
                <p>${weather.weather[0].description}</p>
            `;
        }
    } catch (error) {
        console.error("Error al obtener el clima:", error);
    }
}

// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', cargarClima);
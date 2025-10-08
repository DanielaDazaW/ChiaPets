
<?php include("template/cabecera.php"); ?>

<div class="container mt-0.0">
    <div class="text-center">
        <h1 class="display-4">Nosotros</h1>
        <!-- Logo debajo del título -->
        <img src="img/chiapet3.png" alt="Chiapet Logo" class="img-fluid my-3" style="max-width: 180px; animation: zoomIn 1s;">
        
        <p class="lead">
            Chiapet es un prototipo de sistema de información desarrollado para el municipio de Chía, 
            diseñado para centralizar y gestionar la información de las mascotas del municipio. 
            Nuestro propósito es contribuir al bienestar animal y facilitar la interacción entre la 
            comunidad y las autoridades locales.
        </p>
    </div>
    <hr class="my-4">

    <div class="row text-center">
        <!-- Misión -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-body">
                    <h3 class="card-title">🐾 Misión</h3>
                    <p class="card-text">
                        Promover el cuidado responsable de las mascotas, garantizando su protección y bienestar 
                        mediante el uso de herramientas tecnológicas que permitan un registro confiable y actualizado.
                    </p>
                </div>
            </div>
        </div>

        <!-- Visión -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-body">
                    <h3 class="card-title">🌟 Visión</h3>
                    <p class="card-text">
                        Ser la plataforma líder en el municipio de Chía para la gestión integral de información sobre 
                        mascotas, campañas de vacunación y búsqueda de animales extraviados, fomentando una comunidad 
                        más consciente y protectora de los animales.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Animación CSS -->
<style>
@keyframes zoomIn {
    from { transform: scale(0.5); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>
<?php include("template/pie.php"); ?>
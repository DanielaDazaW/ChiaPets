<?php include("template/cabecera.php"); ?>

<div class="container mt-1">
    <div class="text-center">
        <h1 class="display-4">Nuestros Servicios</h1>
        <p class="lead">
            En <strong>Chiapet</strong> ponemos a tu disposición herramientas tecnológicas 
            para garantizar el bienestar animal y fortalecer el vínculo entre la comunidad y la secretaría de salud del municipio de Chía.
        </p>
    </div>
    <hr class="my-4">

    <!-- FILA 1: Registro y Vacunación -->
    <div class="row justify-content-center text-center">
        <!-- Registro de mascotas -->
        <div class="col-md-5 mb-4 d-flex">
            <div class="card shadow-lg border-0 w-100 animate-card">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="bi bi-journal-plus" style="font-size: 2rem; color: #28a745;"></i>
                    </div>
                    <h4 class="card-title">Registro de Mascotas</h4>
                    <p class="card-text">
                        Crea un perfil para tu mascota con datos básicos, vacunas y características únicas.
                    </p>
                </div>
            </div>
        </div>

        <!-- Campañas de vacunación -->
        <div class="col-md-5 mb-4 d-flex">
            <div class="card shadow-lg border-0 w-100 animate-card">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="bi bi-syringe" style="font-size: 2rem; color: #17a2b8;"></i>
                    </div>
                    <h4 class="card-title">Campañas de Vacunación</h4>
                    <p class="card-text">
                        Consulta fechas y lugares de jornadas de vacunación y esterilización promovidas por la Alcaldía del Municipio, Fundaciones y demás.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- FILA 2: Consultas y Notificaciones -->
    <div class="row justify-content-center text-center">
        <!-- Consultas y Reportes -->
        <div class="col-md-5 mb-4 d-flex">
            <div class="card shadow-lg border-0 w-100 animate-card">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="bi bi-bar-chart" style="font-size: 2rem; color: #ffc107;"></i>
                    </div>
                    <h4 class="card-title">Consultas y Reportes</h4>
                    <p class="card-text">
                        Consulta reportes acerca de tu mascota.
                    </p>
                </div>
            </div>
        </div>

        <!-- Notificaciones -->
        <div class="col-md-5 mb-4 d-flex">
            <div class="card shadow-lg border-0 w-100 animate-card">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="bi bi-bell" style="font-size: 2rem; color: #007bff;"></i>
                    </div>
                    <h4 class="card-title">Notificaciones</h4>
                    <p class="card-text">
                        Recibe alertas sobre campañas, noticias y actualizaciones relacionadas con el bienestar animal.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Logo animado -->
    <div class="text-center mt-1">
        <img src="img/chiapet3.png" alt="Chiapet Logo" class="img-fluid" style="max-width: 170px; animation: zoomIn 1s;">
    </div>
</div>

<!-- Estilos extra -->
<style>
@keyframes zoomIn {
    from { transform: scale(0.5); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* Animación hover */
.animate-card {
    transition: transform 0.3s;
}
.animate-card:hover {
    transform: translateY(-8px);
}
</style>

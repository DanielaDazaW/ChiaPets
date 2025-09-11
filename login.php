<?php include("template/cabecera.php"); ?>

<h1 class="text-center">Bienvenido Administrador 🐾</h1>
<p class="text-center">Desde aquí podrás gestionar el sistema Chiapet.</p>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card shadow text-center">
            <div class="card-body">
                <h5>Mascotas</h5>
                <p>Gestiona el registro de mascotas.</p>
                <a href="mascotas.php" class="btn btn-success">Ir</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow text-center">
            <div class="card-body">
                <h5>Usuarios</h5>
                <p>Administra los usuarios del sistema.</p>
                <a href="usuarios.php" class="btn btn-primary">Ir</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow text-center">
            <div class="card-body">
                <h5>Vacunas</h5>
                <p>Control de campañas de vacunación.</p>
                <a href="vacunas.php" class="btn btn-info">Ir</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow text-center">
            <div class="card-body">
                <h5>Reportes</h5>
                <p>Reportes de mascotas extraviadas.</p>
                <a href="reportes.php" class="btn btn-warning">Ir</a>
            </div>
        </div>
    </div>
</div>

<?php include("template/pie.php"); ?>
<?php include('template/cabecera.php'); ?>

<div class="container mt-4">
    <div class="row align-items-center mb-4">
        <div class="col-auto">
            <img src="../img/chiapet3.png" alt="Logo CHIAPETS" style="height: 130px;">


        </div>
        <div class="col">
            <h1 class="display-4 mb-0">Bienvenido, Administrador</h1>
            <p class="lead">Desde aquí puedes gestionar todos los aspectos de CHIAPETS</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Mascotas Registradas</h5>
                    <p class="card-text display-4">15</p>
                </div>
                <div class="card-footer">
                    <small>Actualizado hoy</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title">Eventos Próximos</h5>
                    <p class="card-text display-4">2</p>
                </div>
                <div class="card-footer">
                    <small>Revisa el calendario</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title">Usuarios Activos</h5>
                    <p class="card-text display-4">17</p>
                </div>
                <div class="card-footer">
                    <small>En esta semana</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h5 class="card-title">Alertas de Salud</h5>
                    <p class="card-text display-4">5</p>
                </div>
                <div class="card-footer">
                    <small>Requieren atención</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('template/pie.php'); ?>

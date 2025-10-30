<?php
include("../template/cabecera.php");
include("../config/bd.php");

// Totales simples
$totalMascotas = $conexion->query("SELECT COUNT(*) FROM mascotas WHERE estado=1")->fetchColumn();
$totalPersonas = $conexion->query("SELECT COUNT(*) FROM personas WHERE estado=1")->fetchColumn();
$totalOrganizaciones = $conexion->query("SELECT COUNT(*) FROM organizacion WHERE estado=1")->fetchColumn();
$totalReportes = $conexion->query("SELECT COUNT(*) FROM reportes WHERE estado=1")->fetchColumn();

// Mascotas por especie
$mascotasPorEspecie = $conexion->query("
    SELECT e.tipo_especie, COUNT(*) AS cantidad
    FROM mascotas m
    JOIN especie e ON m.id_especie = e.id_especie
    WHERE m.estado=1
    GROUP BY e.tipo_especie
")->fetchAll(PDO::FETCH_ASSOC);

// Mascotas esterilizadas
$esterilizadas = $conexion->query("
    SELECT CASE WHEN m.esterilizado=1 THEN 'Sí' ELSE 'No' END AS esterilizada, COUNT(*) as cantidad
    FROM mascotas m WHERE m.estado=1 GROUP BY esterilizada
")->fetchAll(PDO::FETCH_ASSOC);

// Mascotas por raza (top 5)
$mascotasPorRaza = $conexion->query("
    SELECT r.tipo_raza, COUNT(*) AS cantidad
    FROM mascotas m
    JOIN raza r ON m.id_raza = r.id_raza
    WHERE m.estado=1
    GROUP BY r.tipo_raza
    ORDER BY cantidad DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Mascotas por tamaño
$mascotasPorTamano = $conexion->query("
    SELECT t.tamano, COUNT(*) as cantidad
    FROM mascotas m
    JOIN tamano t ON m.id_tamano = t.id_tamano
    WHERE m.estado=1
    GROUP BY t.tamano
")->fetchAll(PDO::FETCH_ASSOC);

// Reportes por tipo
$reportesPorTipo = $conexion->query("
    SELECT tr.tipo_reporte, COUNT(*) as cantidad
    FROM reportes r
    JOIN tipo_reporte tr ON r.id_tipo_reporte = tr.id_tipo_reporte
    WHERE r.estado=1
    GROUP BY tr.tipo_reporte
")->fetchAll(PDO::FETCH_ASSOC);

// Vacunas por tipo
$vacunasPorTipo = $conexion->query("
    SELECT tv.tipo_vacuna, COUNT(*) as cantidad
    FROM vacunas v
    JOIN tipo_vacuna tv ON v.id_tipo_vacuna = tv.id_tipo_vacuna
    WHERE v.estado=1
    GROUP BY tv.tipo_vacuna
")->fetchAll(PDO::FETCH_ASSOC);

// Desparasitaciones por producto (top 5)
$desparaPorProducto = $conexion->query("
    SELECT p.producto, COUNT(*) as cantidad
    FROM desparasitaciones d
    JOIN producto p ON d.id_producto = p.id_producto
    WHERE d.estado=1
    GROUP BY p.producto
    ORDER BY cantidad DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Mascotas con más reportes (top 5)
$mascotasMasReportes = $conexion->query("
    SELECT m.nombre, COUNT(*) AS total_reportes
    FROM reportes r
    JOIN mascotas m ON r.id_mascota = m.id_mascota
    WHERE r.estado=1
    GROUP BY m.id_mascota
    ORDER BY total_reportes DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Personas con más mascotas (top 5)
$personasMasMascotas = $conexion->query("
    SELECT p.nombres, COUNT(*) AS total_mascotas
    FROM mascotas m
    JOIN personas p ON m.id_persona = p.id_persona
    WHERE m.estado=1
    GROUP BY p.id_persona
    ORDER BY total_mascotas DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html>
<style>
canvas {
  width: 100% !important;
  height: 300px !important;
}
</style>
<body>
<div class="container mt-4">
    <h1>Dashboard de Estadísticas</h1>
    <div class="row my-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Mascotas</h5>
                    <p class="card-text fs-2"><?= $totalMascotas ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Personas</h5>
                    <p class="card-text fs-2"><?= $totalPersonas ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Organizaciones</h5>
                    <p class="card-text fs-2"><?= $totalOrganizaciones ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Reportes</h5>
                    <p class="card-text fs-2"><?= $totalReportes ?></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Gráficas -->
    <div class="row">
        <div class="col-md-6">
           <canvas id="mascotasEspecie"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="esterilizadasTorta"></canvas>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="mascotasRaza"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="mascotasTamano"></canvas>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="reportesTipo"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="vacunasTipo"></canvas>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="desparasitacionesProductocto"></canvas>
        </div>
    </div>
    <!-- Tablas resumen -->
    <div class="row mt-4">
        <div class="col-md-6">
            <h5>Mascotas con más reportes</h5>
            <table class="table table-sm">
                <thead>
                    <tr><th>Mascota</th><th>Reportes</th></tr>
                </thead>
                <tbody>
                <?php foreach($mascotasMasReportes as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td><?= $row['total_reportes'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h5>Usuarios con más mascotas</h5>
            <table class="table table-sm">
                <thead>
                    <tr><th>Persona</th><th>Mascotas</th></tr>
                </thead>
                <tbody>
                <?php foreach($personasMasMascotas as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombres']) ?></td>
                        <td><?= $row['total_mascotas'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Mascotas por especie (Barras)
    const mascotasEspecie = new Chart(document.getElementById('mascotasEspecie'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($mascotasPorEspecie, 'tipo_especie')) ?>,
            datasets: [{
                label: 'Cantidad',
                data: <?= json_encode(array_column($mascotasPorEspecie, 'cantidad')) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        }
    });
    // Mascotas esterilizadas (Torta)
    const esterilizadasTorta = new Chart(document.getElementById('esterilizadasTorta'), {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($esterilizadas, 'esterilizada')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($esterilizadas, 'cantidad')) ?>,
                backgroundColor: ['#32cd32','#e74c3c']
            }]
        }
    });
    // Mascotas por raza (barras)
    const mascotasRaza = new Chart(document.getElementById('mascotasRaza'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($mascotasPorRaza, 'tipo_raza')) ?>,
            datasets: [{
                label: 'Top 5 Razas',
                data: <?= json_encode(array_column($mascotasPorRaza, 'cantidad')) ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.6)'
            }]
        }
    });
    // Mascotas por tamaño (barras)
    const mascotasTamano = new Chart(document.getElementById('mascotasTamano'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($mascotasPorTamano, 'tamano')) ?>,
            datasets: [{
                label: 'Por tamaño',
                data: <?= json_encode(array_column($mascotasPorTamano, 'cantidad')) ?>,
                backgroundColor: 'rgba(241, 196, 15, 0.7)'
            }]
        }
    });
    // Reportes por tipo (barras)
    const reportesTipo = new Chart(document.getElementById('reportesTipo'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($reportesPorTipo, 'tipo_reporte')) ?>,
            datasets: [{
                label: 'Reportes',
                data: <?= json_encode(array_column($reportesPorTipo, 'cantidad')) ?>,
                backgroundColor: 'rgba(52, 152, 219, 0.6)'
            }]
        }
    });
    // Vacunas por tipo (barras)
    const vacunasTipo = new Chart(document.getElementById('vacunasTipo'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($vacunasPorTipo, 'tipo_vacuna')) ?>,
            datasets: [{
                label: 'Vacunas aplicadas',
                data: <?= json_encode(array_column($vacunasPorTipo, 'cantidad')) ?>,
                backgroundColor: 'rgba(46, 204, 113, 0.7)'
            }]
        }
    });
    // Desparasitaciones por producto (barras)
    const desparasitacionesProducto = new Chart(document.getElementById('desparasitacionesProducto'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($desparaPorProducto, 'producto')) ?>,
            datasets: [{
                label: 'Productos más usados',
                data: <?= json_encode(array_column($desparaPorProducto, 'cantidad')) ?>,
                backgroundColor: 'rgba(155, 89, 182, 0.7)'
            }]
        }
    });

</script>

</body>
</html>
<?php include("../template/pie.php"); ?>

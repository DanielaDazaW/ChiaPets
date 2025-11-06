<?php
include("template/cabecera.php");
include("administrador/config/bd.php");
$stmt = $conexion->prepare("SELECT titulo, descripcion, fecha_inicio, fecha_fin, lugar FROM campanias");
$stmt->execute();
?>

<style>
/* Logo centrado */
.logo-container {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}
.logo-container img {
    max-width: 200px;
    height: auto;
}

/* Tabla estilizada */
.table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 2px 15px rgba(64,64,64,.1);
    border-radius: 12px 12px 0 0;
    overflow: hidden;
    font-size: 16px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.table thead tr {
    background-color: #009879;
    color: #ffffff;
    text-align: left;
    font-weight: bold;
}
.table th,
.table td {
    padding: 12px 15px;
}
.table tbody tr {
    border-bottom: 1px solid #dddddd;
}
.table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}
.table tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
}
.table tbody tr:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}
</style>

<!-- Logo centrado -->
<div class="logo-container">
   C alt="Logo de la organización">
</div>

<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
                <th>Lugar</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha_inicio']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha_fin']); ?></td>
                <td><?php echo htmlspecialchars($row['lugar']); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include("template/pie.php") ?>

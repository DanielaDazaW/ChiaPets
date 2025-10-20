<?php
include("template/cabecera.php");
include("administrador/config/bd.php");
$stmt = $conexion->prepare("SELECT titulo, descripcion, fecha_inicio, fecha_fin, lugar FROM campanias");
$stmt->execute();
include("template/pie.php");
?>
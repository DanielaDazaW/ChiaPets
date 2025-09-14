<?php include("../../template/cabecera.php"); ?>

<?php
$txtID = isset($_POST['txtID']) ? $_POST['txtID'] : "";
$txtTipoCampana=(isset($_POST['txtTipoCampana']))?$_POST['txtTipoCampana']:""; 
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include("../../config/bd.php");


switch ($accion) {
    case "Agregar":
        $sentenciaSQL = $conexion->prepare("INSERT INTO tipo_campana (tipo_campana,estado)  VALUES (:TipoCampana, 1)");
        $sentenciaSQL->bindParam(':TipoCampana', $txtTipoCampana);
        $sentenciaSQL->execute();
        header("Location: editorial.php");
        break;

    case "Editar":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_campana SET tipo_campana = :TipoCampana WHERE id_tipo_campana = :id");
        $sentenciaSQL->bindParam('TipoCampana', $txtTipoCampana);
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        header("Location: editorial.php");
        break;

    case "Cancelar":
        //header("Location: editorial.php");
        break;
    
    case "Seleccionar":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_campana WHERE id_tipo_campana = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $TipoCampana = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtTipoCampana = $TipoCampana['tipo_campana'];

        
        break;

    case "Mostrar":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_campana ");
        $sentenciaSQL->execute();
        $TipoCampana = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

        $txtNombreEditorial = $editorial['Nombre_Editorial'];
        break;

    case "Eliminar":
        // En lugar de eliminar, desactivamos la editorial
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_campana SET estado = 0 WHERE id_tipo_campana = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        header("Location: editorial.php");
        break;
}

$sentenciaSQL = $conexion->prepare("SELECT * FROM editorial");
$sentenciaSQL->execute();
$listaEditoriales = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

<div style="text-align:center; margin-top: 40px; margin-bottom: 30px;">
    <h1 style="font-size: 3em; color: #2c3e50;">Catálogos Administrativos</h1>
    <p style="font-size: 1.25em; color: #34495e;">
        Administración centralizada para gestionar los registros de Tipo Campaña, Tipo de Reporte, Producto de Desparasitacion, Tipo de Vacuna y Vacuna. Añade, edita o elimina registros de cada categoría con facilidad.
    </p>
</div>

<!-- Sección: Tipo Campaña -->
<div style="width: 80%; margin: 30px auto; background-color: #ecf0f1; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2 style="color: #2980b9;">Tipo Campaña</h2>

    <form method="POST" enctype= "multipart/form-data">
    <div style="margin-bottom: 15px;">

        <input type="text" placeholder="ID Tipo campaña" name="txtID" id="txtID" style="padding: 10px; width: 70%; font-size: 1em; border-radius: 5px; border: 1px solid #ccc;">
        <input type="text" placeholder="Ingresar nuevo tipo de campaña" name="txtTipoCampana" id="txtTipoCampana" style="padding: 10px; width: 70%; font-size: 1em; border-radius: 5px; border: 1px solid #ccc;">
        
        <button name= "accion" value="Agregar" style="background-color: #27ae60; color: white; border: none; padding: 10px 20px; font-size: 1em; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Añadir
        </button>
    </div>

    <div style="margin-bottom: 25px;">
        <button name= "accion" value="Mostrar" style="background-color: #2980b9; color: white; border: none; padding: 10px 25px; font-size: 1em; border-radius: 5px; cursor: pointer;">
            Listar Todos los Registros
        </button>
    </div>

     <div class="col-md-7">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tipo Campaña</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaTipocampana as $TipoCampana) { ?>
                    <tr>
                        <td><?php echo $TipoCampana['id_tipo_campana']; ?></td>
                        <td><?php echo htmlspecialchars($editorial['tipo_campana']); ?></td>
                        <td><?php echo $TipoCampana['estado'] == 1 ? "Sí" : "No"; ?></td>
                        <td> 

                            <?php if ($TipoCampana['estado'] == 1) : ?>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="txtID" value="<?php echo $TipoCampana['id_tipo_campana']; ?>">
                                    <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary btn-sm">
                                </form>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="txtID" value="<?php echo $TipoCampana['id_tipo_campana']; ?>">
                                    <input type="submit" name="accion" value="Eliminar" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta editorial?');">
                                </form>
                            <?php else : ?>
                                <span class="text-muted">Sin acciones</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div style="background-color: white; padding: 15px; border-radius: 5px; max-height: 280px; overflow-y: auto;">
        <div style="padding: 10px 15px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 1.1em; color: #2c3e50;">Ejemplo de Tipo Campaña</span>
            <div>
                <button name= "accion" value="Editar" style="background-color: #f39c12; color: white; border: none; padding: 6px 15px; font-size: 0.9em; border-radius: 5px; cursor: pointer; margin-right: 8px;">
                    Editar
                </button>
                <button  name= "accion" value="Eliminar" style="background-color: #c0392b; color: white; border: none; padding: 6px 15px; font-size: 0.9em; border-radius: 5px; cursor: pointer;">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Sección: Tipo de Reporte -->
<div style="width: 80%; margin: 30px auto; background-color: #ecf0f1; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2 style="color: #2980b9;">Tipo de Reporte</h2>

    <div style="margin-bottom: 15px;">
        <input type="text" placeholder="Ingresar nuevo tipo de reporte" style="padding: 10px; width: 70%; font-size: 1em; border-radius: 5px; border: 1px solid #ccc;">
        <button style="background-color: #27ae60; color: white; border: none; padding: 10px 20px; font-size: 1em; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Añadir
        </button>
    </div>

    <div style="margin-bottom: 25px;">
        <button style="background-color: #2980b9; color: white; border: none; padding: 10px 25px; font-size: 1em; border-radius: 5px; cursor: pointer;">
            Listar Todos los Registros
        </button>
    </div>

    <div style="background-color: white; padding: 15px; border-radius: 5px; max-height: 280px; overflow-y: auto;">
        <div style="padding: 10px 15px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 1.1em; color: #2c3e50;">Ejemplo de Tipo de Reporte</span>
            <div>
                <button style="background-color: #f39c12; color: white; border: none; padding: 6px 15px; font-size: 0.9em; border-radius: 5px; cursor: pointer; margin-right: 8px;">
                    Editar
                </button>
                <button style="background-color: #c0392b; color: white; border: none; padding: 6px 15px; font-size: 0.9em; border-radius: 5px; cursor: pointer;">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Sección: Producto de Desparasitacion -->
<div style="width: 80%; margin: 30px auto; background-color: #ecf0f1; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2 style="color: #2980b9;">Producto de Desparasitacion</h2>

    <div style="margin-bottom: 15px;">
        <input type="text" placeholder="Ingresar nuevo producto de desparasitacion" style="padding: 10px; width: 70%; font-size: 1em; border-radius: 5px; border: 1px solid #ccc;">
        <button style="background-color: #27ae60; color: white; border: none; padding: 10px 20px; font-size: 1em; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Añadir
        </button>
    </div>

    <div style="margin-bottom: 25px;">
        <button style="background-color: #2980b9; color: white; border: none; padding: 10px 25px; font-size: 1em; border-radius: 5px; cursor: pointer;">
            Listar Todos los Registros
        </button>
    </div>

    <div style="background-color: white; padding: 15px; border-radius: 5px; max-height: 280px; overflow-y: auto;">
        <div style="padding: 10px 15px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 1.1em; color: #2c3e50;">Ejemplo de Producto de Desparasitacion</span>
            <div>
                <button style="background-color: #f39c12; color: white; border: none; padding: 6px 15px; font-size: 0.9em; border-radius: 5px; cursor: pointer; margin-right: 8px;">
                    Editar
                </button>
                <button style="background-color: #c0392b; color: white; border: none; padding: 6px 15px; font-size: 0.9em; border-radius: 5px; cursor: pointer;">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Sección: Tipo de Vacuna -->
<div style="width: 80%; margin: 30px auto; background-color: #ecf0f1; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2 style="color: #2980b9;">Tipo de Vacuna</h2>

    <div style="margin-bottom: 15px;">
        <input type="text" placeholder="Ingresar nuevo tipo de vacuna" style="padding: 10px; width: 70%; font-size: 1em; border-radius: 5px; border: 1px solid #ccc;">
        <button style="background-color: #27ae60; color: white; border: none; padding: 10px 20px; font-size: 1em; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Añadir
        </button>
    </div>

    <div style="margin-bottom: 25px;">
        <button style="background-color: #2980b9; color: white; border: none; padding: 10px 25px; font-size: 1em; border-radius: 5px; cursor: pointer;">
            Listar Todos los Registros
        </button>
    </div>

    <div style="background-color: white; padding: 15px; border-radius: 5px; max-height: 280px; overflow-y: auto;">
        <div style="padding: 10px 15px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 1.1em; color: #2c3e50;">Ejemplo de Tipo de Vacuna</span>
            <div>
                <button style="background-color: #f39c12; color: white; border: none; padding: 6px 15px; font-size: 0.9em; border-radius: 5px; cursor: pointer; margin-right: 8px;">
                    Editar
                </button>
                <button style="background-color: #c0392b; color: white; border: none; padding: 6px 15px; font-size: 0.9em; border-radius: 5px; cursor: pointer;">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Sección: Vacuna -->
<div style="width: 80%; margin: 30px auto 60px auto; background-color: #ecf0f1; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2 style="color: #2980b9;">Vacuna</h2>

    <div style="margin-bottom: 15px;">
        <input type="text" placeholder="Ingresar nueva vacuna" style="padding: 10px; width: 70%; font-size: 1em; border-radius: 5px; border: 1px solid #ccc;">
        <button style="background-color: #27ae60; color: white; border: none; padding: 10px 20px; font-size: 1em; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Añadir
        </button>
    </div>

    <div style="margin-bottom: 25px;">
        <button style="background-color: #2980b9; color: white; border: none; padding: 10px 25px; font-size: 1em; border-radius: 5px; cursor: pointer;">
            Listar Todos los Registros
        </button>
    </div>

    <div style="background-color: white; padding: 15px; border-radius: 5px; max-height: 280px; overflow-y: auto;">
        <div style="padding: 10px 15px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 1.1em; color: #2c3e50;">Ejemplo de Vacuna</span>
            <div>
                <button style="background-color: #f39c12; color: white; border: none; padding: 6px 15px; font-size: 0.9em; border-radius: 5px; cursor: pointer; margin-right: 8px;">
                    Editar
                </button>
                <button style="background-color: #c0392b; color: white; border: none; padding: 6px 15px; font-size: 0.9em; border-radius: 5px; cursor: pointer;">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<?php include("../../template/pie.php"); ?>
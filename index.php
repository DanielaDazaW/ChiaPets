<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHIAPET - Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-box {
            width: 380px;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .card-header {
            background-color: #a2e436;
            color: white;
        }

        .btn-ingresar {
            background-color: #a2e436;
            color: white;
            font-weight: bold;
        }

        .btn-ingresar:hover {
            background-color: #a2e436;
            color: white;
        }

        img.logo {
            width: 90px;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="card shadow">
        <div class="card-header text-center">
            <!-- Logo arriba -->
            <img src="img/chiapet3.png" alt="Logo CHIAPET" class="logo">
            <h3>Iniciar Sesión</h3>
        </div>
        <div class="card-body">
            <form action="validar.php" method="POST">
                <div class="form-group mb-3">
                    <label for="usuario">Usuario</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-ingresar btn-block w-100">Ingresar</button>
            </form>
        </div>
        <div class="card-footer text-center">
            <a href="recuperar.php" class="text-success">¿Olvidó su contraseña?</a><br>
            <a href="registro.php"class="text-success"><b>¿No tiene una cuenta? Registrarse</b></a>
        </div>
    </div>
</div>

</body>
</html>

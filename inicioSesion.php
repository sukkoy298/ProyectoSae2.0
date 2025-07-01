<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4" style="max-width:  400px; width: 100%;">
            <h1 class="text-center mb-4"><i class="bi bi-person-circle me-2"></i>Inicio de Sesión</h1>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger text-center">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            <form action="controladores/SesionControler.php" method="POST">
                <div class="mb-3">
                    <label for="Usuario" class="form-label">Correo</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="Usuario" name="Usuario" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="Clave" class="form-label">Clave</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="Clave" name="Clave" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar Sesión
                </button>
                <a class="btn btn-secondary mt-2 w-100" href="index.html">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </form>
            <div class="mt-3 text-center">
                <p>¿No tienes una cuenta? <a href="registros.php"><i class="bi bi-person-plus"></i> Regístrate aquí</a></p>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS (optional, for interactivity) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
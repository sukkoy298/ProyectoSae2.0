
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Panel de control</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg ">
            <div class="container-fluid">   
                <h3>Panel de contol</h3>
                <a href="" id="nav-panelC">Panel de control</a>
                    <a href="ClientesPC.php">Clientes</a>
                    <a href="Desarrolladores.php">Desarrolladores</a>
                    <a href="Requerimientos.php">Requerimientos</a>
                    <a href="reportes.php">Reportes</a>
                
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="controladores/cerrarSesion.php">Salir</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="cardPanel mb-4">
            <div class="card-body">
                <h5 class="card-title">Gestión de Fases</h5>
                <p class="card-text">Agrega nuevas fases para los requerimientos de los sistemas.</p>
                <!-- Botón para abrir el modal -->
                <button type="button" class="btn mb-3" style="background-color: #800080; color: #fff;" data-bs-toggle="modal" data-bs-target="#modalFase">
                    Agregar Fase
                </button>

                <!-- Modal Fase -->
                <div class="modal fade" id="modalFase" tabindex="-1" aria-labelledby="modalFaseLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content" style ="background-color:#2c3e50">
                      <form method="post">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalFaseLabel">Agregar Nueva Fase</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label for="nombre_fase" class="form-label">Nombre de la Fase</label>
                            <input type="text" class="form-control" id="nombre_fase" name="nombre_fase" required>
                          </div>
                          <div class="mb-3">
                            <label for="descripcion_fase" class="form-label">Descripción de la Fase</label>
                            <textarea class="form-control" id="descripcion_fase" name="descripcion_fase" rows="3" required></textarea>
                          </div>    
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn" style= "background-color: #8B0000; color: #fff" data-bs-dismiss="modal">Cancelar</button>
                          <button type="submit" class="btn" style="background-color: #800080; color: #fff;">Guardar Fase</button>
                        </div>
                      </form>
                      <?php
                      require_once 'controladores/conexion.php';
                      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre_fase'], $_POST['descripcion_fase'])) {
                          $nombre = trim($_POST['nombre_fase']);
                          $descripcion = trim($_POST['descripcion_fase']);
                          if ($nombre !== "" && $descripcion !== "") {
                              $stmt = $conn->prepare("INSERT INTO fase (Nombre, Descripcion) VALUES (?, ?)");
                              $stmt->bind_param("ss", $nombre, $descripcion);
                              if ($stmt->execute()) {
                                  echo "<div class='alert alert-success m-3'>Fase agregada correctamente.</div>";
                              } else {
                                  echo "<div class='alert alert-danger m-3'>Error al agregar fase: " . $conn->error . "</div>";
                              }
                              $stmt->close();
                          } else {
                              echo "<div class='alert alert-warning m-3'>El nombre y la descripción de la fase no pueden estar vacíos.</div>";
                          }
                      }
                      ?>
                    </div>
                  </div>
                </div>
            </div>
        </div>

        <!-- Panel para agregar tipo de sistema -->
        <div class="cardPanel mb-4">
            <div class="card-body">
                <h5 class="card-title">Gestión de Tipos de Sistemas</h5>
                <p class="card-text">Agrega nuevos tipos de sistemas para clasificar los proyectos.</p>
                <!-- Botón para abrir el modal -->
                <button type="button" class="btn mb-3" style="background-color: #006400; color: #fff;" data-bs-toggle="modal" data-bs-target="#modalTipoSistema">
                    Agregar Tipo de Sistema
                </button>

                <!-- Modal Tipo de Sistema -->
                <div class="modal fade" id="modalTipoSistema" tabindex="-1" aria-labelledby="modalTipoSistemaLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content" style ="background-color:#2c3e50">
                      <form method="post">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalTipoSistemaLabel">Agregar Nuevo Tipo de Sistema</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label for="nombre_tipo" class="form-label">Nombre del Tipo</label>
                            <input type="text" class="form-control" id="nombre_tipo" name="nombre_tipo" required>
                          </div>
                          <div class="mb-3">
                            <label for="descripcion_tipo" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion_tipo" name="descripcion_tipo" rows="3" required></textarea>
                          </div>
                          <div class="mb-3">
                            <label for="activo_tipo" class="form-label">Activo</label>
                            <select class="form-select" id="activo_tipo" name="activo_tipo" required>
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn" style= "background-color: #8B0000; color: #fff" data-bs-dismiss="modal">Cancelar</button>
                          <button type="submit" class="btn" style="background-color: #006400; color: #fff;">Guardar Tipo</button>
                        </div>
                      </form>
                      <?php
                      require_once 'controladores/conexion.php';
                      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre_tipo'], $_POST['descripcion_tipo'], $_POST['activo_tipo'])) {
                          $nombre_tipo = trim($_POST['nombre_tipo']);
                          $descripcion_tipo = trim($_POST['descripcion_tipo']);
                          $activo_tipo = intval($_POST['activo_tipo']);
                          if ($nombre_tipo !== "" && $descripcion_tipo !== "") {
                              $stmt = $conn->prepare("INSERT INTO tipo_sistema (nombre, descripcion, activo) VALUES (?, ?, ?)");
                              $stmt->bind_param("ssi", $nombre_tipo, $descripcion_tipo, $activo_tipo);
                              if ($stmt->execute()) {
                                  echo "<div class='alert alert-success m-3'>Tipo de sistema agregado correctamente.</div>";
                              } else {
                                  echo "<div class='alert alert-danger m-3'>Error al agregar tipo de sistema: " . $conn->error . "</div>";
                              }
                              $stmt->close();
                          } else {
                              echo "<div class='alert alert-warning m-3'>El nombre y la descripción no pueden estar vacíos.</div>";
                          }
                      }
                      ?>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
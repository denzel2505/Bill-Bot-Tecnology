<?php
// Conexión a la base de datos
require_once '../conexion/conexion-BillBot.php';
session_start();
require '../conexion/conexion-BillBot.php'; // Conexión a la base de datos

/*QUERY PARA FOTO DE PERFIL */
$sql = "SELECT * FROM administrador";
$query2 = mysqli_query($con, $sql);


if (!isset($_SESSION['correo'])) {
    header("Location: ../ingreso.php");
    exit;
}

$correo = $_SESSION['correo'];

// Verificar si la sesión está activa en la base de datos
$query = "SELECT sesion_activa FROM administrador WHERE correo = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($sesion_activa);
$stmt->fetch();
$stmt->close();

// Obtener el rol del usuario actual
$queryRol = "SELECT rol FROM facturadores WHERE correo = ?";
$stmtRol = $con->prepare($queryRol);
$stmtRol->bind_param("s", $correo);
$stmtRol->execute();
$stmtRol->bind_result($rol_usuario);
$stmtRol->fetch();
$stmtRol->close();

// if ($sesion_activa == 0) {
//     session_unset();
//     session_destroy();
//     header("Location: ../ingreso.php");
//     exit;
// }

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Obtener la lista de facturadores (usuarios con rol de facturador)
$sql = "SELECT id, nombre, usuario, correo, session_activa, ultima_actividad FROM facturadores WHERE rol = 'Facturador' ORDER BY nombre ASC";
$resultado = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Facturadores - Bill Bot</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="../img/bot2.ico" type="image/x-icon">
  <!-- Custom styles -->
  <link rel="stylesheet" href="../css/style.min.css">
  <link rel="stylesheet" href="../css/style.css">
      <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Chart library -->
<script src="../plugins/chart.min.js"></script>
<!-- Icons library -->
<script src="../plugins/feather.min.js"></script>
  <script src="../js/script.js"></script>
</head>

<body>
  
  <div class="layer"></div>
<!-- ! Body -->
<a class="skip-link sr-only" href="#skip-target">Skip to content</a>
<div class="page-flex">
  <!-- ! Sidebar -->
  <?php include '../dashboard/sidebar/sidebar.php';?> <!-- Include the top navigation bar -->
  <div class="main-wrapper">
    <!-- ! Main nav -->
    <?php include '../dashboard/navbar/navbar.php';?> <!-- Include the top navigation bar -->
    
            <!-- Contenido principal -->
<div class="content">
    <div class="content-top-wrapper">
        <h2 class="main-title">Lista de Facturadores</h2>
        <button class="add-user-btn" id="btnAgregarFacturador">
            <i data-feather="user-plus"></i>
            <span>Nuevo Facturador</span>
        </button>
    </div>

    <div class="users-table-card">
        <div class="card-header">
            <div class="header-left">
                <span class="card-title">Usuarios Facturadores</span>
                <div class="user-search">
                    <input type="text" id="buscadorFacturas" class="search-input" placeholder="Buscar facturador...">
                    <i data-feather="search" class="search-icon"></i>
                </div>
            </div>
            <div class="header-stats">
                <div class="stat-badge online">
                    <span class="stat-icon"></span>
                    <span class="stat-text">Online: 
                        <?php
                        $online = $con->query("SELECT COUNT(*) as total FROM facturadores WHERE rol = 'Facturador' AND session_activa = 1")->fetch_assoc();
                        echo $online['total'];
                        ?>
                    </span>
                </div>
                <div class="stat-badge total">
                    <span class="stat-icon"></span>
                    <span class="stat-text">Total: 
                        <?php
                        $total = $con->query("SELECT COUNT(*) as total FROM facturadores WHERE rol = 'Facturador'")->fetch_assoc();
                        echo $total['total'];
                        ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table class="users-table" id="tablaFacturas">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Información de conexión</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
    <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr data-id="<?php echo $row['id']; ?>">
            <td>
                <div class="user-status <?php echo ($row['session_activa'] == 1) ? 'status-online' : 'status-offline'; ?>">
                    <span class="status-dot"></span>
                    <span class="status-text"><?php echo ($row['session_activa'] == 1) ? 'Online' : 'Offline'; ?></span>
                </div>
            </td>
            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
            <td><?php echo htmlspecialchars($row['usuario']); ?></td>
            <td><?php echo htmlspecialchars($row['correo']); ?></td>
            <td>
                <div class="connection-info <?php echo ($row['session_activa'] == 1) ? 'active' : 'inactive'; ?>">
                    <i data-feather="<?php echo ($row['session_activa'] == 1) ? 'clock' : 'clock'; ?>"></i>
                    <span><?php echo ($row['session_activa'] == 1) ? 'Conectado desde: ' : 'Última conexión: '; ?></span>
                    <time><?php echo date('d/m/Y H:i', strtotime($row['ultima_actividad'])); ?></time>
                </div>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn view" title="Ver detalles">
                        <i data-feather="eye"></i>
                    </button>
                    <button class="action-btn edit" title="Editar facturador">
                        <i data-feather="edit-2"></i>
                    </button>
                    <button class="action-btn delete" title="Eliminar facturador">
                        <i data-feather="trash-2"></i>
                    </button>
                </div>
            </td>
        </tr>
    <?php endwhile; ?>
                        <?php else: ?>
                            <tr class="empty-state">
                                <td colspan="6">
                                    <div class="empty-container">
                                        <i data-feather="users" class="empty-icon"></i>
                                        <p class="empty-text">No se encontraron facturadores registrados</p>
                                        <button class="add-user-btn" id="btnEmptyAdd">
                                            <i data-feather="user-plus"></i>
                                            <span>Agregar Nuevo Facturador</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar facturador -->
<div class="modal-overlay" id="modalAgregarFacturador">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Agregar Nuevo Facturador</h3>
            <button class="modal-close" id="btnCerrarModal">
                <i data-feather="x"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="formAgregarFacturador">
                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="usuario">Nombre de usuario</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" id="btnCancelar">Cancelar</button>
                    <button type="submit" class="btn-save">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Añadir estos modales después del modal de agregar facturador -->

<!-- Modal para ver detalles del facturador -->
<div class="modal-overlay" id="modalVerFacturador">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Detalles del Facturador</h3>
            <button class="modal-close" data-close-modal>
                <i data-feather="x"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="user-details">
                <div class="detail-group">
                    <span class="detail-label">Nombre completo:</span>
                    <span class="detail-value" id="verNombre"></span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">Nombre de usuario:</span>
                    <span class="detail-value" id="verUsuario"></span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">Correo electrónico:</span>
                    <span class="detail-value" id="verCorreo"></span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">Estado:</span>
                    <span class="detail-value" id="verEstado"></span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">Última actividad:</span>
                    <span class="detail-value" id="verUltimaActividad"></span>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-cancel" data-close-modal>Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar facturador -->
<div class="modal-overlay" id="modalEditarFacturador">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Editar Facturador</h3>
            <button class="modal-close" data-close-modal>
                <i data-feather="x"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="formEditarFacturador">
                <input type="hidden" id="editId" name="id">
                <div class="form-group">
                    <label for="editNombre">Nombre completo</label>
                    <input type="text" id="editNombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="editUsuario">Nombre de usuario</label>
                    <input type="text" id="editUsuario" name="usuario" required>
                </div>
                <div class="form-group">
                    <label for="editCorreo">Correo electrónico</label>
                    <input type="email" id="editCorreo" name="correo" required>
                </div>
                <div class="form-group">
                    <label for="editPassword">Contraseña (dejar en blanco para mantener la actual)</label>
                    <input type="password" id="editPassword" name="password">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" data-close-modal>Cancelar</button>
                    <button type="submit" class="btn-save">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Filtro de búsqueda para la tabla
    document.addEventListener("DOMContentLoaded", function() {
        // Inicializar íconos de Feather
        feather.replace();
        
        const inputBusqueda = document.getElementById("buscadorFacturas");
        const tabla = document.getElementById("tablaFacturas").getElementsByTagName("tbody")[0];

        if (inputBusqueda) {
            inputBusqueda.addEventListener("keyup", function() {
                const filtro = inputBusqueda.value.toLowerCase();

                for (let fila of tabla.rows) {
                    let textoFila = fila.innerText.toLowerCase();
                    fila.style.display = textoFila.includes(filtro) ? "" : "none";
                }
            });
        }

        // Modal para agregar facturador
        const btnAgregar = document.getElementById("btnAgregarFacturador");
        const btnEmptyAdd = document.getElementById("btnEmptyAdd");
        const modal = document.getElementById("modalAgregarFacturador");
        const btnCerrar = document.getElementById("btnCerrarModal");
        const btnCancelar = document.getElementById("btnCancelar");

        function abrirModal() {
            modal.classList.add("active");
            document.body.style.overflow = "hidden";
        }

        function cerrarModal() {
            modal.classList.remove("active");
            document.body.style.overflow = "";
        }

        if (btnAgregar) btnAgregar.addEventListener("click", abrirModal);
        if (btnEmptyAdd) btnEmptyAdd.addEventListener("click", abrirModal);
        if (btnCerrar) btnCerrar.addEventListener("click", cerrarModal);
        if (btnCancelar) btnCancelar.addEventListener("click", cerrarModal);

        // Cerrar modal al hacer clic fuera del contenido
        if (modal) {
            modal.addEventListener("click", function(e) {
                if (e.target === modal) {
                    cerrarModal();
                }
            });
        }

       

       
    });
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Regenerar íconos después de cargar nuevos elementos
    feather.replace();
    
    // Referencias a los modales
    const modalVer = document.getElementById("modalVerFacturador");
    const modalEditar = document.getElementById("modalEditarFacturador");
    
    // Cerrar modales con cualquier botón que tenga data-close-modal
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal-overlay');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = "";
            }
        });
    });
    
    // Cerrar modales al hacer clic fuera del contenido
    [modalVer, modalEditar].forEach(modal => {
        if (modal) {
            modal.addEventListener("click", function(e) {
                if (e.target === modal) {
                    modal.classList.remove("active");
                    document.body.style.overflow = "";
                }
            });
        }
    });
    
    // Funcionalidad para los botones de acción
    setupActionButtons();
    
    // Configurar formulario de edición
    const formEditar = document.getElementById("formEditarFacturador");
    if (formEditar) {
        formEditar.addEventListener("submit", function(e) {
            e.preventDefault();
            actualizarFacturador(this);
        });
    }
    
    // Configurar formulario de agregar facturador
    const formAgregar = document.getElementById("formAgregarFacturador");
    if (formAgregar) {
        formAgregar.addEventListener("submit", function(e) {
            e.preventDefault();
            agregarFacturador(this);
        });
    }
});

// Función para configurar los botones de acción en cada fila
// Función mejorada para configurar los botones de acción en cada fila
function setupActionButtons() {
    // Botones Ver
    document.querySelectorAll(".action-btn.view").forEach(btn => {
        btn.addEventListener("click", function() {
            const fila = this.closest("tr");
            const id = fila.getAttribute("data-id");
            const nombre = fila.cells[1].textContent;
            const usuario = fila.cells[2].textContent;
            const correo = fila.cells[3].textContent;
            const estado = fila.cells[0].querySelector(".status-text").textContent;
            const ultimaActividad = fila.cells[4].querySelector("time").textContent;
            
            console.log("Ver facturador ID:", id);
            
            // Llenar el modal con los datos
            document.getElementById("verNombre").textContent = nombre;
            document.getElementById("verUsuario").textContent = usuario;
            document.getElementById("verCorreo").textContent = correo;
            document.getElementById("verEstado").textContent = estado;
            document.getElementById("verUltimaActividad").textContent = ultimaActividad;
            
            // Mostrar modal
            const modalVer = document.getElementById("modalVerFacturador");
            modalVer.classList.add("active");
            document.body.style.overflow = "hidden";
            
            // Regenerar íconos
            feather.replace();
        });
    });
    
    // Botones Editar
    document.querySelectorAll(".action-btn.edit").forEach(btn => {
        btn.addEventListener("click", function() {
            const fila = this.closest("tr");
            const id = fila.getAttribute("data-id");
            const nombre = fila.cells[1].textContent;
            const usuario = fila.cells[2].textContent;
            const correo = fila.cells[3].textContent;
            
            console.log("Editar facturador - Datos:", { id, nombre, usuario, correo });
            
            if (!id) {
                console.error("No se pudo obtener el ID del facturador");
                mostrarNotificacion("Error: No se pudo identificar el facturador", "error");
                return;
            }
            
            // Llenar el formulario con los datos
            document.getElementById("editId").value = id;
            document.getElementById("editNombre").value = nombre;
            document.getElementById("editUsuario").value = usuario;
            document.getElementById("editCorreo").value = correo;
            document.getElementById("editPassword").value = ""; // Limpiar contraseña
            
            // Mostrar modal
            const modalEditar = document.getElementById("modalEditarFacturador");
            modalEditar.classList.add("active");
            document.body.style.overflow = "hidden";
            
            // Regenerar íconos
            feather.replace();
        });
    });
    
    // Botones Eliminar
    document.querySelectorAll(".action-btn.delete").forEach(btn => {
        btn.addEventListener("click", function() {
            const fila = this.closest("tr");
            const id = fila.getAttribute("data-id");
            const nombre = fila.cells[1].textContent;
            
            console.log("Eliminar facturador ID:", id);
            
            if (!id) {
                console.error("No se pudo obtener el ID del facturador");
                mostrarNotificacion("Error: No se pudo identificar el facturador", "error");
                return;
            }
            
            if (confirm(`¿Estás seguro de que deseas eliminar al facturador "${nombre}"?`)) {
                eliminarFacturador(id, fila);
            }
        });
    });
}
// Función para agregar un nuevo facturador
function agregarFacturador(form) {
    const formData = new FormData(form);
    
    // Cambiar el botón a estado de carga
    const btnSubmit = form.querySelector('button[type="submit"]');
    const btnText = btnSubmit.innerHTML;
    btnSubmit.innerHTML = '<span class="loading-spinner"></span>Guardando...';
    btnSubmit.disabled = true;
    
    fetch('procesar_facturador.php?accion=agregar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cerrar modal
            document.getElementById("modalAgregarFacturador").classList.remove("active");
            document.body.style.overflow = "";
            
            // Mostrar notificación
            mostrarNotificacion('Facturador agregado correctamente', 'success');
            
            // Recargar la página para mostrar el nuevo facturador
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            mostrarNotificacion('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al procesar la solicitud', 'error');
    })
    .finally(() => {
        // Restaurar el botón
        btnSubmit.innerHTML = btnText;
        btnSubmit.disabled = false;
    });
}

// Añade estas modificaciones a tu código JavaScript para mejorar el debugging

// Función para actualizar un facturador existente (versión mejorada)
function actualizarFacturador(form) {
    const formData = new FormData(form);
    const id = formData.get('id');
    
    console.log("ID del facturador a actualizar:", id);
    console.log("Datos del formulario:", {
        nombre: formData.get('nombre'),
        usuario: formData.get('usuario'),
        correo: formData.get('correo'),
        password: formData.get('password') ? "[CONTRASEÑA INGRESADA]" : "[SIN CAMBIOS]"
    });
    
    // Validar que el ID no sea nulo ni vacío
    if (!id) {
        mostrarNotificacion('Error: ID de facturador no encontrado', 'error');
        return;
    }
    
    // Cambiar el botón a estado de carga
    const btnSubmit = form.querySelector('button[type="submit"]');
    const btnText = btnSubmit.innerHTML;
    btnSubmit.innerHTML = '<span class="loading-spinner"></span>Actualizando...';
    btnSubmit.disabled = true;
    
    // URL completa para debugging
    const url = `procesar_facturador.php?accion=actualizar&id=${id}`;
    console.log("URL de la petición:", url);
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            console.error("Error HTTP:", response.status, response.statusText);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log("Respuesta del servidor:", data);
        
        if (data.success) {
            // Cerrar modal
            document.getElementById("modalEditarFacturador").classList.remove("active");
            document.body.style.overflow = "";
            
            // Mostrar notificación
            mostrarNotificacion('Facturador actualizado correctamente', 'success');
            
            // Actualizar los datos en la tabla sin recargar
            const fila = document.querySelector(`tr[data-id="${id}"]`);
            if (fila) {
                fila.cells[1].textContent = formData.get('nombre');
                fila.cells[2].textContent = formData.get('usuario');
                fila.cells[3].textContent = formData.get('correo');
            } else {
                console.warn(`No se encontró la fila con data-id="${id}"`);
                // Si no se encuentra la fila, recargar la página
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        } else {
            mostrarNotificacion('Error: ' + (data.message || 'Error desconocido'), 'error');
        }
    })
    .catch(error => {
        console.error('Error completo:', error);
        mostrarNotificacion('Error al procesar la solicitud. Revisa la consola para más detalles.', 'error');
    })
    .finally(() => {
        // Restaurar el botón
        btnSubmit.innerHTML = btnText;
        btnSubmit.disabled = false;
    });
}

// Función para eliminar un facturador
function eliminarFacturador(id, fila) {
    // Agregar clase de carga a la fila
    fila.classList.add('deleting');
    
    fetch(`procesar_facturador.php?accion=eliminar&id=${id}`, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Animar la eliminación de la fila
            fila.style.opacity = '0';
            fila.style.height = '0';
            
            setTimeout(() => {
                fila.remove();
                
                // Verificar si la tabla está vacía
                const tbody = document.querySelector('#tablaFacturas tbody');
                if (tbody.children.length === 0) {
                    // Crear fila de estado vacío
                    const emptyRow = document.createElement('tr');
                    emptyRow.className = 'empty-state';
                    emptyRow.innerHTML = `
                        <td colspan="6">
                            <div class="empty-container">
                                <i data-feather="users" class="empty-icon"></i>
                                <p class="empty-text">No se encontraron facturadores registrados</p>
                                <button class="add-user-btn" id="btnEmptyAdd">
                                    <i data-feather="user-plus"></i>
                                    <span>Agregar Nuevo Facturador</span>
                                </button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(emptyRow);
                    
                    // Regenerar íconos y configurar botón
                    feather.replace();
                    document.getElementById('btnEmptyAdd').addEventListener('click', function() {
                        document.getElementById('modalAgregarFacturador').classList.add('active');
                        document.body.style.overflow = 'hidden';
                    });
                }
                
                // Actualizar contadores de facturadores
                actualizarContadores();
            }, 300);
            
            // Mostrar notificación
            mostrarNotificacion('Facturador eliminado correctamente', 'success');
        } else {
            fila.classList.remove('deleting');
            mostrarNotificacion('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        fila.classList.remove('deleting');
        mostrarNotificacion('Error al procesar la solicitud', 'error');
    });
}

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo) {
    // Eliminar notificaciones existentes
    const notificacionesExistentes = document.querySelectorAll('.notification');
    notificacionesExistentes.forEach(notif => notif.remove());
    
    // Crear nueva notificación
    const notificacion = document.createElement('div');
    notificacion.className = `notification ${tipo}`;
    notificacion.textContent = mensaje;
    
    // Agregar al DOM
    document.body.appendChild(notificacion);
    
    // Mostrar con animación
    setTimeout(() => {
        notificacion.classList.add('show');
    }, 10);
    
    // Ocultar después de 3 segundos
    setTimeout(() => {
        notificacion.classList.remove('show');
        setTimeout(() => {
            notificacion.remove();
        }, 300);
    }, 3000);
}

function actualizarContadores() {
    fetch('./obtener_contadores.php')
    .then(response => response.json())
    .then(data => {
        if (data.online !== undefined && data.total !== undefined) {
            const onlineCounter = document.querySelector('.stat-badge.online .stat-text');
            const totalCounter = document.querySelector('.stat-badge.total .stat-text');
            
            if (onlineCounter) onlineCounter.textContent = `Online: ${data.online}`;
            if (totalCounter) totalCounter.textContent = `Total: ${data.total}`;
        }
    })
    .catch(error => console.error('Error al actualizar contadores:', error));
}
</script>

<style>
  .user-details {
    background-color: #f9fafc;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.darkmode .user-details {
    background-color: #2a2a3c;
}

.detail-group {
    display: flex;
    margin-bottom: 15px;
}

.detail-group:last-child {
    margin-bottom: 0;
}

.detail-label {
    width: 140px;
    font-weight: 600;
    color: #6e7891;
}

.darkmode .detail-label {
    color: #b0b3c9;
}

.detail-value {
    flex: 1;
    color: #4f5b76;
}

.darkmode .detail-value {
    color: #e0e0e0;
}

/* Animación de carga */
.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
    margin-right: 10px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Mensaje de notificación */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 6px;
    color: white;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1100;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.notification.show {
    opacity: 1;
    transform: translateY(0);
}

.notification.success {
    background-color: #28c76f;
}

.notification.error {
    background-color: #ea5455;
}
/* Estilos para la página de Facturadores */
.content {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.content-top-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.main-title {
    font-size: 24px;
    font-weight: 600;
    margin: 0;
}

.add-user-btn {
    display: flex;
    align-items: center;
    background-color: #3361ff;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.add-user-btn:hover {
    background-color: #2747cc;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(51, 97, 255, 0.3);
}

.add-user-btn svg {
    width: 16px;
    height: 16px;
    margin-right: 8px;
}

/* Tarjeta para la tabla */
.users-table-card {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 30px;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #f1f1f5;
}

.header-left {
    display: flex;
    align-items: center;
}

.card-title {
    font-size: 16px;
    font-weight: 600;
    margin-right: 25px;
}

.user-search {
    position: relative;
}

.search-input {
    background-color: #f5f6fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 8px 16px 8px 38px;
    font-size: 14px;
    width: 240px;
    transition: all 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: #3361ff;
    box-shadow: 0 0 0 3px rgba(51, 97, 255, 0.1);
    background-color: white;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    color: #9497a8;
}

.header-stats {
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-badge {
    display: flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.stat-badge.online {
    background-color: rgba(40, 199, 111, 0.12);
    color: #28c76f;
}

.stat-badge.total {
    background-color: rgba(105, 108, 255, 0.12);
    color: #696cff;
}

.stat-icon {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 8px;
}

.stat-badge.online .stat-icon {
    background-color: #28c76f;
}

.stat-badge.total .stat-icon {
    background-color: #696cff;
}

/* Tabla */
.card-body {
    padding: 0;
}

.table-container {
    overflow-x: auto;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table th {
    background-color: #f9fafc;
    padding: 15px 20px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #6e7891;
    border-bottom: 1px solid #f1f1f5;
}

.users-table td {
    padding: 15px 20px;
    border-bottom: 1px solid #f1f1f5;
    font-size: 14px;
    color: #4f5b76;
}

.users-table tbody tr:hover {
    background-color: #f9fafc;
}

/* Estado del usuario */
.user-status {
    display: flex;
    align-items: center;
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 8px;
}

.status-online .status-dot {
    background-color: #28c76f;
    box-shadow: 0 0 0 3px rgba(40, 199, 111, 0.2);
}

.status-offline .status-dot {
    background-color: #97a3b9;
    box-shadow: 0 0 0 3px rgba(151, 163, 185, 0.2);
}

.status-text {
    font-size: 13px;
    font-weight: 500;
}

.status-online .status-text {
    color: #28c76f;
}

.status-offline .status-text {
    color: #97a3b9;
}

/* Información de conexión */
.connection-info {
    display: flex;
    align-items: center;
    font-size: 13px;
}

.connection-info.active {
    color: #28c76f;
}

.connection-info.inactive {
    color: #97a3b9;
}

.connection-info svg {
    width: 14px;
    height: 14px;
    margin-right: 8px;
}

.connection-info time {
    font-weight: 500;
    margin-left: 4px;
}

/* Botones de acción */
.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: 1px solid #e9ecef;
    background-color: white;
    color: #6e7891;
    cursor: pointer;
    transition: all 0.2s;
}

.action-btn svg {
    width: 14px;
    height: 14px;
}

.action-btn:hover {
    background-color: #f1f1f5;
}

.action-btn.view:hover {
    color: #3361ff;
    border-color: #3361ff;
    background-color: rgba(51, 97, 255, 0.05);
}

.action-btn.edit:hover {
    color: #28c76f;
    border-color: #28c76f;
    background-color: rgba(40, 199, 111, 0.05);
}

.action-btn.delete:hover {
    color: #ea5455;
    border-color: #ea5455;
    background-color: rgba(234, 84, 85, 0.05);
}

/* Estado vacío */
.empty-state {
    text-align: center;
}

.empty-container {
    padding: 40px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.empty-icon {
    width: 48px;
    height: 48px;
    color: #d0d6e4;
    margin-bottom: 16px;
}

.empty-text {
    color: #6e7891;
    font-size: 15px;
    margin-bottom: 20px;
}

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.modal-container {
    background-color: white;
    border-radius: 12px;
    width: 100%;
    max-width: 480px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    transform: translateY(-20px);
    transition: all 0.3s ease;
}

.modal-overlay.active .modal-container {
    transform: translateY(0);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #f1f1f5;
}

.modal-header h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #6e7891;
}

.modal-close:hover {
    color: #ea5455;
}

.modal-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px;
    color: #4f5b76;
}

.form-group input {
    width: 100%;
    padding: 10px 16px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
}

.form-group input:focus {
    outline: none;
    border-color: #3361ff;
    box-shadow: 0 0 0 3px rgba(51, 97, 255, 0.1);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 30px;
}

.btn-cancel {
    padding: 10px 20px;
    border: 1px solid #e9ecef;
    background-color: white;
    color: #6e7891;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-cancel:hover {
    background-color: #f1f1f5;
}

.btn-save {
    padding: 10px 20px;
    border: none;
    background-color: #3361ff;
    color: white;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-save:hover {
    background-color: #2747cc;
}

/* Estilos para modo oscuro */
.darkmode .users-table-card {
    background-color: #1e1e2d;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.darkmode .card-header {
    border-bottom: 1px solid #2a2a3c;
}

.darkmode .card-title,
.darkmode .main-title {
    color: #e0e0e0;
}

.darkmode .search-input {
    background-color: #2a2a3c;
    border-color: #3a3a4c;
    color: #e0e0e0;
}

.darkmode .search-input:focus {
    background-color: #2a2a3c;
    border-color: #3361ff;
}

.darkmode .users-table th {
    background-color: #2a2a3c;
    color: #b0b3c9;
    border-bottom: 1px solid #3a3a4c;
}

.darkmode .users-table td {
    border-bottom: 1px solid #2a2a3c;
    color: #e0e0e0;
}

.darkmode .users-table tbody tr:hover {
    background-color: #2a2a3c;
}

.darkmode .action-btn {
    background-color: #2a2a3c;
    border-color: #3a3a4c;
    color: #b0b3c9;
}

.darkmode .empty-text {
    color: #b0b3c9;
}

.darkmode .modal-container {
    background-color: #1e1e2d;
}

.darkmode .modal-header {
    border-bottom: 1px solid #2a2a3c;
}

.darkmode .modal-header h3 {
    color: #e0e0e0;
}

.darkmode .form-group label {
    color: #b0b3c9;
}

.darkmode .form-group input {
    background-color: #2a2a3c;
    border-color: #3a3a4c;
    color: #e0e0e0;
}

.darkmode .btn-cancel {
    border-color: #3a3a4c;
    background-color: #2a2a3c;
    color: #b0b3c9;
}

.darkmode .empty-icon {
    color: #3a3a4c;
}

/* Responsive */
@media (max-width: 991px) {
    .header-left {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .card-title {
        margin-bottom: 15px;
    }
    
    .card-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-stats {
        margin-top: 15px;
    }
    
    .search-input {
        width: 100%;
    }
    
    .content-top-wrapper {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .add-user-btn {
        margin-top: 15px;
    }
}

@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
        gap: 5px;
    }
    
    .users-table th,
    .users-table td {
        padding: 12px 10px;
    }
}
</style>
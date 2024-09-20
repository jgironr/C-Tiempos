<div class="container py-3">
    <h1 class="text-center mb-5">Gestionar Tiempos Predefinidos</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-3">
                <a href="<?= site_url('timersController/timer') ?>" class="btn btn-secondary">Volver</a>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Duración (minutos)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($intervals)): ?>
                        <?php foreach ($intervals as $interval): ?>
                            <tr>
                                <td><?= htmlspecialchars($interval->name) ?></td>
                                <td><?= htmlspecialchars($interval->duration) ?></td>
                                <td>
                                    <a href="<?= site_url('timersController/edit_interval/' . $interval->id) ?>" class="btn btn-sm btn-warning">Editar</a>

                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('<?= site_url('timersController/delete_interval/' . $interval->id) ?>')">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No hay tiempos predefinidos.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script de confirmación con SweetAlert2 -->
<script>
function confirmDelete(url) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;  
        }
    });
}
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


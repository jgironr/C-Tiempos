<div class="container py-3">
    <a href="<?= site_url('timersController/sound_alerts') ?>" class="btn btn-secondary">Volver</a>
    <h1 class="text-center mb-5"><?= isset($alert->id) ? 'Editar Alerta Sonora' : 'Crear Nueva Alerta Sonora' ?></h1>

    <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form
        action="<?= isset($alert->id) ? site_url('timersController/edit_alert/' . $alert->id) : site_url('timersController/save_alert') ?>"
        method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label for="name">Nombre de la Alerta</label>
            <input type="text" name="name" id="name" class="form-control"
                value="<?= isset($alert->name) ? htmlspecialchars($alert->name) : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="sound_file">Archivo de Sonido</label>
            <input type="file" name="sound_file" id="sound_file" class="form-control" accept="audio/*"
                <?= isset($alert->id) ? '' : 'required' ?>>
        </div>

        <button type="submit" class="btn btn-primary"><?= isset($alert->id) ? 'Actualizar' : 'Crear' ?></button>
    </form>

    <h2 class="mt-5">Alertas Sonoras</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Archivo de Sonido</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($alerts)): ?>
            <?php foreach ($alerts as $alert): ?>
            <tr>
                <td><?= htmlspecialchars($alert->id) ?></td>
                <td><?= htmlspecialchars($alert->name) ?></td>
                <td><?= htmlspecialchars($alert->sound_file) ?></td>
                <td>
                    <a href="<?= site_url('timersController/edit_alert/' . $alert->id) ?>"
                        class="btn btn-warning">Editar</a>
                    <a href="<?= site_url('timersController/delete_alert/' . $alert->id) ?>" class="btn btn-danger"
                        onclick="return confirm('¿Estás seguro de que deseas eliminar esta alerta?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="4" class="text-center">No hay alertas</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
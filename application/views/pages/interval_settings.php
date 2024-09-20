<div class="container py-3">
<a href="<?= site_url('timersController/timer') ?>" class="btn btn-secondary">Volver</a>
    <h1 class="text-center mb-5"><?= isset($interval->id) ? 'Editar' : 'Crear Nuevo Tiempo' ?></h1>
   
    <form action="<?= isset($interval->id) ? site_url('timersController/edit_interval/' . $interval->id) : site_url('timersController/save_interval') ?>" method="post">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= isset($interval->name) ? htmlspecialchars($interval->name) : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="duration">Duración (minutos)</label>
            <input type="number" name="duration" id="duration" class="form-control" value="<?= isset($interval->duration) ? htmlspecialchars($interval->duration) : '' ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary"><?= isset($interval->id) ? 'Actualizar' : 'Crear' ?></button>
    </form>
</div>

<div class="container py-5">
    <h2 class="text-center mb-4">Editar Espacio de Trabajo</h2>
    <form method="POST" action="<?= site_url('timersController/update/' . $workspace->id) ?>">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" name="name"
                value="<?= htmlspecialchars($workspace->name) ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Descripción</label>
            <textarea class="form-control" id="description" name="description" rows="3"
                required><?= htmlspecialchars($workspace->description) ?></textarea>
        </div>
        <div class="form-group">
            <label for="color">Color</label>
            <input type="color" class="form-control" id="color" name="color"
                value="<?= htmlspecialchars($workspace->color) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="<?= site_url('timersController/timer') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
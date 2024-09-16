<div class="container mt-4">
    <h2 class="text-center">Crear Nuevo Cronómetro</h2>
    <form action="<?= site_url('timersController/db_create') ?>" method="post">
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="color">Color:</label>
            <input type="color" class="form-control" id="color" name="color" required>
        </div>
        <button type="submit" class="btn btn-primary">Crear Espacio</button>
    </form>
</div>
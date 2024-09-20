<div class="container py-5">
    <h2 class="text-center mb-4">Editar Espacio de Trabajo</h2>
   
    <form method="POST" action="<?= site_url('timersController/update/' . $workspace->id) ?>" enctype="multipart/form-data">
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
        
        <div class="form-group">
            <label for="rate">Tarifa por hora (en quetzales)</label>
            <input type="number" class="form-control" id="rate" name="rate" step="0.01" min="0" 
                value="<?= isset($workspace->rate) ? htmlspecialchars($workspace->rate) : '0.00' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="image">Imagen de Portada</label>
            <input type="file" class="form-control-file" id="image" name="image">
            
            <?php if (!empty($workspace->image) && file_exists(FCPATH . 'uploads/' . $workspace->image)): ?>
                <div class="mt-3">
                    <p>Imagen Actual:</p>
                    <img src="<?= base_url('uploads/' . htmlspecialchars($workspace->image)) ?>" alt="Imagen Actual" class="img-fluid mb-3" style="max-height: 150px; object-fit: cover; border-radius: 10px;">
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="<?= site_url('timersController/timer') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

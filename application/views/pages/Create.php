<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-dark text-white text-center">
            <h3>Crear Nuevo Cronómetro</h3>
        </div>
        <div class="card-body p-4">
            <form action="<?= site_url('timersController/db_create') ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nombre:</label>
                    <input type="text" class="form-control" id="name" name="name" required
                           placeholder="Ingresa el nombre del cronómetro"
                           data-toggle="tooltip" data-placement="right" title="Nombre único del cronómetro">
                </div>
                <div class="form-group">
                    <label for="description">Descripción:</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required
                              placeholder="Descripción del cronómetro"
                              data-toggle="tooltip" data-placement="right" title="Descripción del propósito del cronómetro"></textarea>
                </div>
                <div class="form-group">
                    <label for="color">Color:</label>
                    <input type="color" class="form-control" id="color" name="color"
                           data-toggle="tooltip" data-placement="right" title="Selecciona un color para identificar este espacio">
                </div>
                <div class="form-group">
                    <label for="rate">Tarifa por Hora (Q):</label>
                    <input type="number" class="form-control" id="rate" name="rate" step="0.01" required
                           placeholder="Tarifa por hora en quetzales"
                           data-toggle="tooltip" data-placement="right" title="Ingresa la tarifa por hora en quetzales para este espacio de trabajo">
                </div>
                <div class="form-group">
                    <label for="image">Imagen de Portada:</label>
                    <input type="file" class="form-control-file" id="image" name="image"
                           data-toggle="tooltip" data-placement="right" title="Cargar una imagen para este espacio">
                </div>
                <button type="submit" class="btn btn-primary btn-block" 
                        data-toggle="tooltip" data-placement="top" title="Guardar y crear el nuevo cronómetro">
                    <i class="fas fa-plus-circle"></i> Crear Espacio
                </button>
            </form>
        </div>
    </div>
</div>

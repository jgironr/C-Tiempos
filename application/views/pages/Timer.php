<div class="container py-2">
    <h2 class="text-center mb-4">Cronómetros</h2>
    
    <div class="d-flex justify-content-between mb-4">
        <button id="backButton" onclick="window.location.href='<?= site_url('timersController') ?>'"
            class="btn btn-secondary">
            Volver
        </button>

        <a href="<?= site_url('TimersController/usage_summary') ?>" class="btn btn-info">Ver Resumen de Uso</a>

        
        <div class="ml-auto">
            <button id="createButton" onclick="window.location.href='<?= site_url('timersController/create') ?>'"
                class="btn btn-primary mr-2"><i class="fas fa-plus mr-2"></i> Cronómetro
            </button>

            <div class="dropdown d-inline">
                <button class="btn btn-info dropdown-toggle" type="button" id="timeManagementDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-clock"></i> Gestión de Tiempos
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="timeManagementDropdown">
                    <a class="dropdown-item" href="<?= site_url('timersController/interval_settings') ?>">Configuración de Tiempo</a>
                    <a class="dropdown-item" href="<?= site_url('timersController/manage_intervals') ?>">Control de Tiempos</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <?php foreach ($workspaces as $workspace): ?>
        <div class="col-md-4 mb-4 d-flex justify-content-center">
            <div class="card text-light border-secondary rounded shadow-sm fixed-card-size"
                style="background-color: <?= htmlspecialchars($workspace->color ?: '#000000') ?>;">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex justify-content-end">
                        <a href="<?= site_url('timersController/edit/' . $workspace->id) ?>" class="text-white mr-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?= site_url('timersController/delete/' . $workspace->id) ?>" class="text-white"
                            onclick="return confirm('¿Estás seguro de que deseas eliminar este cronómetro?');">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center mb-3">
                        <?php if (!empty($workspace->image) && file_exists(FCPATH . 'uploads/' . $workspace->image)): ?>
                            <img src="<?= base_url('uploads/' . htmlspecialchars($workspace->image)) ?>" alt="Imagen de Portada" class="fixed-image-size">
                        <?php else: ?>
                            <img src="<?= base_url('uploads/default.png') ?>" alt="Imagen Predeterminada" class="fixed-image-size">
                        <?php endif; ?>
                    </div>

                    <h5 class="card-title text-white text-center font-weight-bold">
                        <?= htmlspecialchars($workspace->name) ?>
                    </h5>
                    <p class="card-text text-white text-center"><?= htmlspecialchars($workspace->description) ?></p>
                    
                    <p class="card-text text-center">Tarifa: Q<?= htmlspecialchars($workspace->rate) ?> por hora</p>
                    <input type="hidden" id="rate-<?= $workspace->id ?>" value="<?= htmlspecialchars($workspace->rate) ?>">

                    <div class="d-flex justify-content-center">
                        <?php if (in_array($workspace->id, $active_workspaces)): ?>
                        <button class="btn btn-danger toggle-workspace" data-workspace-id="<?= $workspace->id ?>"
                            style="border-radius: 25px; padding: 10px 20px;"
                            data-url="<?= site_url('timersController/toggle_timer') ?>">Dejar de Usar</button>
                        <?php else: ?>
                        <button class="btn btn-success toggle-workspace" data-workspace-id="<?= $workspace->id ?>"
                            style="border-radius: 25px; padding: 10px 20px;"
                            data-url="<?= site_url('timersController/toggle_timer') ?>">Usar</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

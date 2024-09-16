<div class="container py-2">
    <h2 class="text-center mb-4">Cronómetros</h2>
    <div class="d-flex justify-content-between mb-4">
        <button id="backButton" onclick="window.location.href='<?= site_url('timersController') ?>'"
            class="btn btn-secondary ">
            Volver
        </button>
        <button id="backButton" onclick="window.location.href='<?= site_url('timersController/create') ?>'"
            class="btn btn-primary "><i class="fas fa-plus mr-2"></i> Cronómetro
        </button>
    </div>
    <div class="row">
        <?php foreach ($workspaces as $workspace): ?>
        <div class="col-md-4 mb-4">
            <div class="card text-light border-secondary rounded shadow-sm"
                style="background-color:<?= htmlspecialchars($workspace->color) ?>;">
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
                    <h5 class="card-title text-white text-center font-weight-bold">
                        <?= htmlspecialchars($workspace->name) ?></h5>
                    <p class="card-text text-white text-center"><?= htmlspecialchars($workspace->description) ?></p>
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
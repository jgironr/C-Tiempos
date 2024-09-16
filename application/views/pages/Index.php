<div class="container py-3">
    <h1 class="text-center mb-5">Cronómetros Activos</h1>
    <div class="row">
        <?php foreach ($active_workspaces as $workspace): ?>
        <div class="col-sm-4 col-md-4 col-lg-3 mb-3">
            <div class="card border-0 shadow-sm"
                style="border-radius: 10px; background-color: <?= htmlspecialchars($workspace->color) ?>;">
                <div class="card-body text-center p-3">
                    <h2 class="card-title mb-2 text-white"><?= htmlspecialchars($workspace->name) ?></h2>
                    <div class="timer-display bg-dark text-white d-flex justify-content-center align-items-center mx-auto rounded-circle mb-2"
                        style="width: 150px; height: 100px; font-size: 2rem;" id="timer-<?= $workspace->id ?>">
                        00:00:00
                    </div>
                    <div class="btn-group btn-group-toggle mb-2" data-toggle="buttons">
                        <label class="btn btn-outline-light select-type mr-2" data-workspace-id="<?= $workspace->id ?>"
                            data-type="countdown" style="border-radius: 10px; padding: 0.5rem;">
                            Cuenta Atrás <i class="fas fa-hourglass-start"></i>
                        </label>
                        <label class="btn btn-outline-light select-type" data-workspace-id="<?= $workspace->id ?>"
                            data-type="stopwatch" style="border-radius: 10px; padding: 0.5rem;">
                            Cronómetro <i class="fas fa-stopwatch"></i>
                        </label>
                    </div>
                    <div class="form-group mt-2 mb-3">
                        <input type="number" id="limit-<?= $workspace->id ?>"
                            class="form-control form-control-sm text-center input-limit d-none"
                            placeholder="Ingresa limite en minutos" min="0" data-workspace-id="<?= $workspace->id ?>"
                            style="border-radius: 5px;">
                    </div>
                    <div class="d-flex justify-content-around mb-2">
                        <button class="btn btn-primary btn-sm play-pause-timer d-none"
                            data-workspace-id="<?= $workspace->id ?>" style="border-radius: 50px; width: 80px;">
                            <i class="fas fa-play"></i>
                        </button>
                        <button class="btn btn-danger btn-sm reset-timer d-none"
                            data-workspace-id="<?= $workspace->id ?>" style="border-radius: 50px; width: 80px;">
                            <i class="fas fa-redo"></i>
                        </button>
                    </div>
                    <button class="btn btn-outline-danger btn-block toggle-workspace"
                        data-workspace-id="<?= $workspace->id ?>" style="border-radius: 50px;"
                        data-url="<?= site_url('timersController/toggle_timer') ?>">
                        Dejar de Usar <i class="fas fa-times-circle ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
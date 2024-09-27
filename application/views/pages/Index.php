<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>">

<div class="container-fluid p-0">
<?php if ($welcome_message): ?>
    <div class="empty-state text-center">
        <!-- Tarjeta para la temperatura actual -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-10">
                <div class="card text-center bg-light shadow-sm border-0 rounded">
                    <div class="card-body">
                        <h4 class="card-title font-weight-bold text-muted" style="font-size: 1.75rem;">Temperatura actual en Guatemala</h4>
                        <p class="card-text display-3 text-primary" style="font-size: 2.5rem;"><?= htmlspecialchars($current_temperature) ?></p>
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Imagen del reloj -->
        <img src="<?= base_url('uploads/reloj.png') ?>" alt="No cronómetros" class="img-fluid mb-4" style="max-width: 200px;">

        <!-- Mensaje de bienvenida -->
        <h2 class="mt-4 text-secondary font-weight-bold">¡Bienvenido! No hay cronómetros activos</h2>
        <p class="text-muted">¡Parece que no tienes cronómetros activos en este momento! Crea uno nuevo o activa uno para comenzar.</p>

        <!-- Botones de acción -->
        <div class="d-flex justify-content-center mt-3">
            <a href="/C-Tiempos/index.php/timersController/create" class="btn btn-primary btn-lg mx-2">Crear nuevo</a>
            <a href="/C-Tiempos/index.php/timersController/timer" class="btn btn-success btn-lg mx-2">Activar uno</a>
        </div>
    </div>
<?php else: ?>

        <div class="container py-3">
            <h1 class="text-center mb-5">Cronómetros Activos</h1>

            <!-- Tarjeta para la temperatura actual -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-4">
                    <div class="card text-center bg-light shadow">
                        <div class="card-body">
                            <h5 class="card-title">Temperatura actual en Guatemala</h5>
                            <p class="card-text text-primary display-4"><?= htmlspecialchars($current_temperature) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Cronómetros activos -->
                <?php foreach ($active_workspaces as $workspace): ?>
                <div class="mb-4 d-flex justify-content-center mx-2">
                    <div class="card border-0 shadow-sm"
                        style="border-radius: 10px; background-color: <?= htmlspecialchars($workspace->color ?: '#000000') ?>; width: 350px; min-height: 450px; display: flex; flex-direction: column; justify-content: space-between; align-items: center;">
                        <div class="card-body d-flex flex-column justify-content-between align-items-center text-center p-3">
                            <!-- Imagen del workspace -->
                            <div class="d-flex justify-content-center mb-3">
                                <?php if (!empty($workspace->image) && file_exists(FCPATH . 'uploads/' . $workspace->image)): ?>
                                <img src="<?= base_url('uploads/' . htmlspecialchars($workspace->image)) ?>" alt="Imagen de Portada" class="fixed-image-size">
                                <?php else: ?>
                                <img src="<?= base_url('uploads/default.png') ?>" alt="Imagen Predeterminada" class="fixed-image-size">
                                <?php endif; ?>
                            </div>

                            <h2 class="card-title text-white text-center mb-2"><?= htmlspecialchars($workspace->name) ?></h2>
                            <p class="card-text text-white text-center">Tarifa: Q<?= htmlspecialchars($workspace->rate) ?> por hora</p>

                            <input type="hidden" id="rate-<?= $workspace->id ?>" value="<?= htmlspecialchars($workspace->rate) ?>">

                            <div class="timer-display bg-dark text-white d-flex justify-content-center align-items-center mx-auto rounded-circle mb-3"
                                style="width: 150px; height: 100px; font-size: 2rem;" id="timer-<?= $workspace->id ?>">
                                00:00:00
                            </div>

                            <div class="btn-group btn-group-toggle mb-3" data-toggle="buttons">
                                <label class="btn btn-outline-light select-type mr-2" data-workspace-id="<?= $workspace->id ?>"
                                    data-type="countdown" style="border-radius: 10px; padding: 0.5rem;">
                                    Cuenta Atrás <i class="fas fa-hourglass-start"></i>
                                </label>
                                <label class="btn btn-outline-light select-type" data-workspace-id="<?= $workspace->id ?>"
                                    data-type="stopwatch" style="border-radius: 10px; padding: 0.5rem;">
                                    Cronómetro <i class="fas fa-stopwatch"></i>
                                </label>
                            </div>

                            <div class="dropdown mb-3">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="intervalDropdown-<?= $workspace->id ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-clock"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="intervalDropdown-<?= $workspace->id ?>">
                                    <?php if (!empty($intervals)): ?>
                                    <?php foreach ($intervals as $interval): ?>
                                    <a class="dropdown-item preset-interval" href="#" data-duration="<?= $interval->duration ?>">
                                        <?= htmlspecialchars($interval->name) ?> (<?= $interval->duration ?> minutos)
                                    </a>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <p class="dropdown-item">No hay tiempos predefinidos.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group mt-2 mb-3 w-100">
                                <input type="number" id="limit-<?= $workspace->id ?>"
                                    class="form-control form-control-sm text-center input-limit d-none"
                                    placeholder="Ingresa limite en minutos" min="0" data-workspace-id="<?= $workspace->id ?>"
                                    style="border-radius: 5px;">
                            </div>

                            <div class="d-flex justify-content-around mb-2 w-100">
                                <button class="btn btn-primary btn-sm play-pause-timer d-none"
                                    data-workspace-id="<?= $workspace->id ?>" style="border-radius: 50px; width: 80px;">
                                    <i class="fas fa-play"></i>
                                </button>
                                <button class="btn btn-danger btn-sm reset-timer d-none"
                                    data-workspace-id="<?= $workspace->id ?>" style="border-radius: 50px; width: 80px;">
                                    <i class="fas fa-redo"></i>
                                </button>
                            </div>

                            <a class="btn btn-outline-danger btn-block toggle-workspace mt-auto"
                                href="<?= site_url('timersController') ?>" data-workspace-id="<?= $workspace->id ?>"
                                style="border-radius: 50px; border: 2px solid white; color: white;"
                                data-url="<?= site_url('timersController/toggle_timer') ?>">
                                Dejar de Usar <i class="fas fa-times-circle ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

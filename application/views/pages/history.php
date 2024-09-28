<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .card-header {
        background-color: #33747f;
        color: white;
    }
    .btn-primary {
        background-color: #21594c;
        border-color: #21594c;
    }
    .btn-primary:hover {
        background-color: #3f8967;
        border-color: #3f8967;
    }
    .table-hover tbody tr:hover {
        background-color: #a7c3a7;
    }
    .form-control:focus {
        border-color: #a7c3a7;
        box-shadow: 0 0 0 0.2rem #d6e3d6;
    }
</style>

<div class="container py-5">
        <div class="card mb-5">
            <div class="card-header">
                <h1 class="text-center mb-0"><i class="fas fa-history me-2"></i>Historial de Uso</h1>
            </div>
            <div class="card-body">
                <form method="post" action="<?= site_url('timersController/history') ?>" class="mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="filter_type" class="form-label">Tipo de Filtro</label>
                            <select class="form-control form-select" id="filter_type" name="filter_type">
                                <option value="day" <?= set_value('filter_type', $filter_type) == 'day' ? 'selected' : '' ?>>Día</option>
                                <option value="week" <?= set_value('filter_type', $filter_type) == 'week' ? 'selected' : '' ?>>Semana</option>
                                <option value="month" <?= set_value('filter_type', $filter_type) == 'month' ? 'selected' : '' ?>>Mes</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3" id="date-filter" style="<?= set_value('filter_type', $filter_type) == 'month' ? 'display: none;' : '' ?>">
                            <label for="filter_date" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="filter_date" name="filter_date" value="<?= set_value('filter_date', $filter_date) ?>">
                        </div>
                        
                        <div class="col-md-3" id="month-year-filter" style="<?= set_value('filter_type', $filter_type) == 'month' ? '' : 'display: none;' ?>">
                            <label for="filter_month" class="form-label">Mes</label>
                            <select class="form-control form-select" id="filter_month" name="filter_month">
                                <?php
                                $months = [
                                    '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
                                    '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
                                    '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
                                    '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                                ];
                                foreach ($months as $num => $name): ?>
                                    <option value="<?= $num ?>" <?= set_value('filter_month', date('m')) == $num ? 'selected' : '' ?>><?= $name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-2" id="year-filter" style="<?= set_value('filter_type', $filter_type) == 'month' ? '' : 'display: none;' ?>">
                            <label for="filter_year" class="form-label">Año</label>
                            <input type="number" class="form-control" id="filter_year" name="filter_year" value="<?= set_value('filter_year', date('Y')) ?>">
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-2"></i>Filtrar</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="far fa-calendar-alt me-2"></i>Fecha</th>
                                <th><i class="fas fa-laptop me-2"></i>Espacio de Trabajo</th>
                                <th><i class="far fa-clock me-2"></i>Hora Encendido</th>
                                <th><i class="fas fa-power-off me-2"></i>Hora Ultimo Uso</th>
                                <th><i class="fas fa-stopwatch me-2"></i>Horas Trabajadas</th>
                                <th><i class="fas fa-coins me-2"></i>Crédito Generado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($usage_logs)): ?>
                                <?php foreach ($usage_logs as $log): ?>
                                <tr>
                                    <td><?= htmlspecialchars($log->fecha) ?></td>
                                    <td><?= htmlspecialchars($log->workspace_name) ?></td>
                                    <td><?= $log->hora_inicio ? date('H:i:s', strtotime($log->hora_inicio)) : 'N/A' ?></td>
                                    <td><?= $log->hora_fin ? date('H:i:s', strtotime($log->hora_fin)) : 'En uso' ?></td>
                                    <td>
                                        <?php
                                        if ($log->tiempo_total) {
                                            $hours = floor($log->tiempo_total / 3600);
                                            $minutes = floor(($log->tiempo_total % 3600) / 60);
                                            $seconds = $log->tiempo_total % 60;
                                            echo sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                    <td>Q<?= number_format($log->dinero_generado, 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No hay registros de uso.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleFilterTypeChange() {
            var filterType = document.getElementById('filter_type').value;
            var dateFilter = document.getElementById('date-filter');
            var monthYearFilter = document.getElementById('month-year-filter');
            var yearFilter = document.getElementById('year-filter');

            if (filterType === 'month') {
                dateFilter.style.display = 'none';
                monthYearFilter.style.display = 'block';
                yearFilter.style.display = 'block';
            } else {
                dateFilter.style.display = 'block';
                monthYearFilter.style.display = 'none';
                yearFilter.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', handleFilterTypeChange);
        document.getElementById('filter_type').addEventListener('change', handleFilterTypeChange);
    </script>
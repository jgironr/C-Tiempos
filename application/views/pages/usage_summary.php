<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<div class="container">
    <h2 class="text-center mb-4">Resumen de Uso por Espacio de Trabajo</h2>

    <form method="post" action="<?= site_url('timersController/usage_summary') ?>" class="form-inline mb-4">
        <label for="filter_type" class="mr-2">Filtrar por:</label>
        <select id="filter_type" name="filter_type" class="form-control mr-3">
            <option value="day" <?= ($filter_type == 'day') ? 'selected' : '' ?>>Día</option>
            <option value="week" <?= ($filter_type == 'week') ? 'selected' : '' ?>>Semana</option>
            <option value="month" <?= ($filter_type == 'month') ? 'selected' : '' ?>>Mes</option>
        </select>

        <label for="filter_date" class="mr-2">Seleccionar fecha:</label>
        <input type="date" id="filter_date" name="filter_date" class="form-control mr-3" value="<?= $filter_date ?>">

        <button type="submit" class="btn btn-primary">Aplicar Filtro</button>
    </form>

    <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Espacio de Trabajo</th>
            <th>Descripción</th>
            <th>Tiempo Trabajado</th>
            <th>Total Generado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($workspaces_usage as $usage): ?>
            <tr>
                <td><?= htmlspecialchars($usage['workspace']->name) ?></td>
                <td><?= htmlspecialchars($usage['workspace']->description) ?></td>
                <td><?= formatTimeWorked($usage['total_usage']->total_duration) ?></td> <!-- Aquí llamamos a la función -->
                <td>Q<?= number_format($usage['total_usage']->total_cost, 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="text-center mt-4">
        <a href="<?= site_url('timersController/timer') ?>" class="btn btn-secondary">Volver</a>
    </div>

    <?php
function formatTimeWorked($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;

    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
}
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

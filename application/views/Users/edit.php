<?php $this->load->view('Partials/Header'); ?>  <!-- Incluye el encabezado -->

<h1>Editar Usuario</h1>
<form method="POST">
    <input type="text" name="name" value="<?= $user->name ?>" required>
    <input type="email" name="email" value="<?= $user->email ?>" required>
    <select name="role">
        <option value="admin" <?= $user->role == 'admin' ? 'selected' : '' ?>>Administrador</option>
        <option value="operator" <?= $user->role == 'operator' ? 'selected' : '' ?>>Operador</option>
    </select>
    <button type="submit">Actualizar</button>
</form>

<?php $this->load->view('Partials/Footer'); ?>  <!-- Incluye el pie de página con el JS -->

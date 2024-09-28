<?php $this->load->view('Partials/Header'); ?>  <!-- Incluye el encabezado -->

<h1>Crear Usuario</h1>
<form method="POST">
    <input type="text" name="name" placeholder="Nombre" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <select name="role">
        <option value="admin">Administrador</option>
        <option value="operator">Operador</option>
    </select>
    <button type="submit">Crear</button>
</form>

<?php $this->load->view('Partials/Footer'); ?>  <!-- Incluye el pie de página con el JS -->

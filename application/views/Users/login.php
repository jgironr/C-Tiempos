<?php $this->load->view('Partials/Header'); ?>  <!-- Incluye el encabezado -->

<h1>Iniciar Sesión</h1>

<?php if(isset($error)) echo '<p style="color: red;">' . $error . '</p>'; ?>  <!-- Muestra el error si existe -->

<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Iniciar Sesión</button>
</form>

<?php $this->load->view('Partials/Footer'); ?>  <!-- Incluye el pie de página con el JS -->

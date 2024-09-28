<?php $this->load->view('Partials/Header'); ?>  <!-- Incluye el encabezado -->

<h1>Lista de Usuarios</h1>
<a href="<?= site_url('usercontroller/create') ?>">Crear Nuevo Usuario</a>
<table>
    <tr>
        <th>Nombre</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Acciones</th>
    </tr>
    <?php foreach($users as $user): ?>
        <tr>
            <td><?= $user->name ?></td>
            <td><?= $user->email ?></td>
            <td><?= $user->role ?></td>
            <td>
                <a href="<?= site_url('usercontroller/edit/' . $user->id) ?>">Editar</a>
                <a href="<?= site_url('usercontroller/delete/' . $user->id) ?>">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php $this->load->view('Partials/Footer'); ?>  <!-- Incluye el pie de página con el JS -->

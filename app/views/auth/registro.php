<?php
$input = $input ?? [];
$role = $role ?? ($_GET['role'] ?? 'estudiante');
$fixedRole = $fixedRole ?? false;
if (!in_array($role, ['estudiante', 'bibliotecario'], true)) {
    $role = 'estudiante';
}
?>

<section class="auth-shell">
    <div class="row g-4 w-100 align-items-stretch">
        <div class="col-lg-4">
            <div class="auth-panel__side">
                <span class="eyebrow mb-3 bg-white text-dark">Nuevo Usuario</span>
                <h2 class="mb-3">Crea tu acceso</h2>
                <p class="mb-4">Registra estudiantes o bibliotecarios desde un formulario mas claro y ordenado.</p>
                <ul class="mb-0">
                    <li>Datos personales y academicos</li>
                    <li>Alta de roles con campos relevantes</li>
                    <li>Interfaz guiada con validaciones</li>
                </ul>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card auth-panel border-0 shadow-sm">
                <div class="card-body p-lg-4">
                    <h1 class="h2 mb-2">Registro de usuario</h1>
                    <p class="text-secondary mb-4">Completa la informacion para crear una nueva cuenta.</p>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger border-0 shadow-sm"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="post" action="<?= htmlspecialchars(url('registro')) ?>" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                
                                <input id="usernameField" type="text" name="username" value="<?= htmlspecialchars($input['username'] ?? '') ?>" class="form-control" placeholder="Usuario" required minlength="4">
                                
                            </div>
                            <div class="col-md-6">
                               
                                <input id="passwordField" type="password" name="password" class="form-control" placeholder="Contraseña" required minlength="6">
                                
                            </div>
                            <div class="col-md-6">
                                
                                <input id="emailField" type="email" name="email" value="<?= htmlspecialchars($input['email'] ?? '') ?>" class="form-control" placeholder="Email" required>
                                
                            </div>
                            
                            <?php if ($fixedRole): ?>
                                <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
                                <input type="hidden" name="fixed_role" value="1">
                            <?php else: ?>
                                <div class="col-md-6">
                                    
                                    <select id="roleSelect" name="role" class="form-select" required>
                                        <option value="estudiante" <?= $role === 'estudiante' ? 'selected' : '' ?>>Estudiante</option>
                                        <option value="bibliotecario" <?= $role === 'bibliotecario' ? 'selected' : '' ?>>Bibliotecario</option>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-6">
                              
                                <input id="nombreField" type="text" name="nombre" value="<?= htmlspecialchars($input['nombre'] ?? '') ?>" class="form-control" placeholder="Nombre completo" required>
                               
                            </div>
                            <div class="col-md-6">
                                
                                <input id="telefonoField" type="text" name="telefono" value="<?= htmlspecialchars($input['telefono'] ?? '') ?>" class="form-control" placeholder="Telefono (opcional)">
                            </div>
                        </div>

                        <div id="studentFields" class="row g-3 mt-3 <?= $role !== 'estudiante' ? 'd-none' : '' ?>" style="<?= $role !== 'estudiante' ? 'display:none;' : '' ?>" <?= $role !== 'estudiante' ? 'hidden' : '' ?>>
                            <div class="col-md-6">
                               
                                <input id="matriculaField" type="text" name="matricula" value="<?= htmlspecialchars($input['matricula'] ?? '') ?>" class="form-control" placeholder="Matricula">
                                
                            </div>
                            <div class="col-md-6">
                               
                                <input id="carreraField" type="text" name="carrera" value="<?= htmlspecialchars($input['carrera'] ?? '') ?>" class="form-control" placeholder="Carrera">
                                
                            </div>
                        </div>

                        <div id="bibliotecarioFields" class="row g-3 mt-3 <?= $role !== 'bibliotecario' ? 'd-none' : '' ?>" style="<?= $role !== 'bibliotecario' ? 'display:none;' : '' ?>" <?= $role !== 'bibliotecario' ? 'hidden' : '' ?>>
                            <div class="col-md-6">
                               
                                <input id="numeroEmpleadoField" type="text" name="numero_empleado" value="<?= htmlspecialchars($input['numero_empleado'] ?? '') ?>" class="form-control" placeholder="Numero de empleado">
                                
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <button type="submit" id="registerButton" class="btn <?= $role === 'estudiante' ? 'btn-success' : 'btn-warning' ?> btn-lg">
                                <?= $role === 'estudiante' ? 'Registrarme como estudiante' : 'Registrarme como bibliotecario' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
(function () {
    const roleSelect = document.getElementById('roleSelect');
    const roleInput = document.querySelector('input[name="role"]');
    const studentFields = document.getElementById('studentFields');
    const librarianFields = document.getElementById('bibliotecarioFields');
    const matricula = document.getElementById('matriculaField');
    const carrera = document.getElementById('carreraField');
    const numeroEmpleado = document.getElementById('numeroEmpleadoField');

    const registerButton = document.getElementById('registerButton');

    function getRoleValue() {
        if (roleSelect) {
            return roleSelect.value;
        }
        return roleInput ? roleInput.value : 'estudiante';
    }

    function updateButton(role) {
        if (!registerButton) {
            return;
        }

        if (role === 'estudiante') {
            registerButton.classList.remove('btn-warning');
            registerButton.classList.add('btn-success');
            registerButton.textContent = 'Registrarme como estudiante';
            return;
        }

        registerButton.classList.remove('btn-success');
        registerButton.classList.add('btn-warning');
        registerButton.textContent = 'Registrar bibliotecario';
    }

    function toggleFields() {
        const role = getRoleValue();
        const isStudent = role === 'estudiante';

        studentFields.classList.toggle('d-none', !isStudent);
        librarianFields.classList.toggle('d-none', isStudent);

        matricula.required = isStudent;
        carrera.required = isStudent;
        numeroEmpleado.required = !isStudent;
        updateButton(role);
    }

    if (roleSelect) {
        roleSelect.addEventListener('change', toggleFields);
    }
    toggleFields();
})();
</script>

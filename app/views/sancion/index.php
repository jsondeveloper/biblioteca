<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Sanciones</span>
        <h1 class="section-title"><?= htmlspecialchars($title ?? 'Listado de sanciones') ?></h1>
        <p class="section-subtitle">Revisa todas las sanciones registradas y su estado actual.</p>
    </div>
</section>

<section class="table-card p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h2 class="h5 mb-2">Resumen de sanciones</h2>
            <p class="text-secondary mb-0">Consulta las razones, fechas y el estado activo de cada sancion.</p>
        </div>
        <a href="<?= htmlspecialchars(url('sanciones/crear')) ?>" class="btn btn-danger">Nueva sancion</a>
    </div>

    <?php if (empty($sanciones)): ?>
        <div class="empty-state">
            <h2 class="h4 mb-2">No hay sanciones registradas</h2>
            <p class="text-secondary mb-0">Registra una sancion para comenzar a ver el historial.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Razon</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Activa</th>
                        <th class="text-end">Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sanciones as $sancion): ?>
                        <tr>
                            <td><?= htmlspecialchars($sancion['estudiante']) ?></td>
                            <td><?= htmlspecialchars($sancion['razon']) ?></td>
                            <td><?= htmlspecialchars($sancion['fecha_inicio']) ?></td>
                            <td><?= htmlspecialchars($sancion['fecha_fin']) ?></td>
                            <td>
                                <span class="badge <?= $sancion['activa'] ? 'bg-danger' : 'bg-secondary' ?>">
                                    <?= $sancion['activa'] ? 'Activa' : 'Inactiva' ?>
                                </span>
                            </td>
                            <td class="text-end book-actions">
                                <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary action-icon-btn" title="Editar sancion" data-bs-toggle="modal" data-bs-target="#editarSancionModal<?= $sancion['id'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                        <span class="visually-hidden">Editar</span>
                                    </button>
                                    <form method="post" action="<?= htmlspecialchars(url('sanciones/eliminar/' . $sancion['id'])) ?>" class="d-inline-block">
                                        <button type="submit" class="btn btn-sm btn-outline-danger action-icon-btn" title="Eliminar sancion" onclick="return confirm('Se eliminara esta sancion. Deseas continuar?')">
                                            <i class="bi bi-trash3"></i>
                                            <span class="visually-hidden">Eliminar</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>
</section>

<?php if (!empty($sanciones)): ?>
    <?php foreach ($sanciones as $sancion): ?>
        <div class="modal fade" id="editarSancionModal<?= $sancion['id'] ?>" tabindex="-1" aria-labelledby="editarSancionModalLabel<?= $sancion['id'] ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarSancionModalLabel<?= $sancion['id'] ?>">Editar sancion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <form method="post" action="<?= htmlspecialchars(url('sanciones/actualizar/' . $sancion['id'])) ?>" class="needs-validation" novalidate>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="estudiante<?= $sancion['id'] ?>" class="form-label">Estudiante</label>
                                    <select id="estudiante<?= $sancion['id'] ?>" name="estudiante_id" class="form-select" required>
                                        <option value="">Selecciona un estudiante</option>
                                        <?php foreach (($estudiantes ?? []) as $estudiante): ?>
                                            <option value="<?= $estudiante['id'] ?>" <?= (int) $sancion['estudiante_id'] === (int) $estudiante['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($estudiante['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Selecciona un estudiante.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="razon<?= $sancion['id'] ?>" class="form-label">Razon</label>
                                    <input type="text" id="razon<?= $sancion['id'] ?>" name="razon" class="form-control" value="<?= htmlspecialchars($sancion['razon']) ?>" required>
                                    <div class="invalid-feedback">Ingresa la razon de la sancion.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fechaInicio<?= $sancion['id'] ?>" class="form-label">Fecha de inicio</label>
                                    <input type="date" id="fechaInicio<?= $sancion['id'] ?>" name="fecha_inicio" class="form-control" value="<?= htmlspecialchars($sancion['fecha_inicio']) ?>" required>
                                    <div class="invalid-feedback">Selecciona la fecha de inicio.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fechaFin<?= $sancion['id'] ?>" class="form-label">Fecha de fin</label>
                                    <input type="date" id="fechaFin<?= $sancion['id'] ?>" name="fecha_fin" class="form-control" value="<?= htmlspecialchars($sancion['fecha_fin']) ?>" required>
                                    <div class="invalid-feedback">Selecciona la fecha de fin.</div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="activa<?= $sancion['id'] ?>" name="activa" <?= $sancion['activa'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="activa<?= $sancion['id'] ?>">Sancion activa</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

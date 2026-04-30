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
            <p class="text-secondary mb-0">Consulta las razones, fechas y el estado activo de cada sanción.</p>
        </div>
        <a href="<?= htmlspecialchars(url('sanciones/crear')) ?>" class="btn btn-danger">Nueva sanción</a>
    </div>

    <?php if (empty($sanciones)): ?>
        <div class="empty-state">
            <h2 class="h4 mb-2">No hay sanciones registradas</h2>
            <p class="text-secondary mb-0">Registra una sanción para comenzar a ver el historial.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Razón</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Activa</th>
                        <th>Acción</th>
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
                            <td>
                                <form method="post" action="<?= htmlspecialchars(url('sanciones/' . ($sancion['activa'] ? 'desactivar' : 'activar') . '/' . $sancion['id'])) ?>" class="d-inline-block">
                                    <button type="submit" class="btn btn-sm <?= $sancion['activa'] ? 'btn-outline-secondary' : 'btn-outline-success' ?>">
                                        <?= $sancion['activa'] ? 'Desactivar' : 'Activar' ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

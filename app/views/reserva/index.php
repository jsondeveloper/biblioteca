<section class="mb-4">
    <div class="hero-card">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <span class="eyebrow mb-3">Reservas</span>
                <h1 class="section-title"><?= htmlspecialchars($title ?? 'Reservas') ?></h1>
                <p class="section-subtitle">Monitorea reservas activas, vencimientos y acciones disponibles por usuario.</p>
            </div>
            <?php if (Auth::hasRole('estudiante')): ?>
                <a class="btn btn-success" href="<?= htmlspecialchars(url('reservas/crear')) ?>">Nueva reserva</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="table-card p-4">
    <?php if (empty($reservas)): ?>
        <div class="empty-state">
            <h2 class="h4 mb-2">Sin reservas registradas</h2>
            <p class="text-secondary mb-0">Las reservas activas apareceran aqui junto con su estado.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Libro</th>
                        <th>Estudiante</th>
                        <th>Reservado</th>
                        <th>Expira</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($reserva['libro']) ?></td>
                            <td><?= htmlspecialchars($reserva['estudiante']) ?></td>
                            <td><?= htmlspecialchars($reserva['fecha_reserva']) ?></td>
                            <td><?= htmlspecialchars($reserva['fecha_expiracion']) ?></td>
                            <td><span class="badge <?= htmlspecialchars(status_badge_class($reserva['estado'])) ?>"><?= htmlspecialchars($reserva['estado']) ?></span></td>
                            <td class="text-end">
                                <?php if (($isBibliotecario ?? false) && $reserva['estado'] === 'Activa'): ?>
                                    <form method="post" action="<?= htmlspecialchars(url('reservas/aprobar/' . $reserva['id'])) ?>" class="d-inline-flex align-items-center gap-2 flex-wrap justify-content-end">
                                        <input type="date" name="fecha_devolucion" class="form-control form-control-sm" required style="min-width: 170px;">
                                        <button type="submit" class="btn btn-sm btn-outline-success">Aprobar y prestar</button>
                                    </form>
                                    <form method="post" action="<?= htmlspecialchars(url('reservas/cancelar/' . $reserva['id'])) ?>" class="d-inline-block ms-2">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                                    </form>
                                <?php elseif ($reserva['estado'] === 'Activa'): ?>
                                    <form method="post" action="<?= htmlspecialchars(url('reservas/cancelar/' . $reserva['id'])) ?>" class="d-inline-block">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-secondary small">Sin acciones</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

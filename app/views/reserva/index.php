<section class="mb-4">
    <div class="hero-card">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <span class="eyebrow mb-3">Reservas</span>
                <h1 class="section-title"><?= htmlspecialchars($title ?? 'Reservas y historial') ?></h1>
                <p class="section-subtitle">Consulta reservas activas y el historial completo de movimientos.</p>
            </div>
            <?php if (Auth::hasRole('estudiante')): ?>
                <a class="btn btn-success" href="<?= htmlspecialchars(url('reservas/crear')) ?>">Nueva reserva</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Reservas Activas -->
<section class="mb-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Reservas activas</h2>
        <span class="badge badge-soft-warning fs-6"><?= count($reservasActivas ?? []) ?> activas</span>
    </div>

    <div class="table-card p-4">
        <?php if (empty($reservasActivas ?? [])): ?>
            <div class="empty-state">
                <h3 class="h5 mb-2">Sin reservas activas</h3>
                <p class="text-secondary mb-0">No hay reservas activas en este momento.</p>
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
                            <th>Tiempo restante</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservasActivas as $reserva): ?>
                            <?php $expirationDateTime = date('Y-m-d H:i:s', strtotime($reserva['created_at'] . ' +1 day')); ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($reserva['libro']) ?></td>
                                <td><?= htmlspecialchars($reserva['estudiante']) ?></td>
                                <td><?= htmlspecialchars($reserva['created_at']) ?></td>
                                <td><?= htmlspecialchars($expirationDateTime) ?></td>
                                <td>
                                    <span id="countdown-<?= $reserva['id'] ?>" data-expiry="<?= htmlspecialchars($expirationDateTime) ?>" class="badge badge-soft-warning fw-semibold">
                                        Calculando...
                                    </span>
                                </td>
                                <td><span class="badge <?= htmlspecialchars(status_badge_class($reserva['estado'])) ?>"><?= htmlspecialchars($reserva['estado']) ?></span></td>
                                <td class="text-end">
                                    <?php if ($reserva['estado'] === 'Activa'): ?>
                                        <?php if ($isBibliotecario ?? false): ?>
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reservaModal<?= $reserva['id'] ?>">Gestionar</button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reservaModal<?= $reserva['id'] ?>">Cancelar</button>
                                        <?php endif; ?>
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
    </div>
</section>

<?php if (!empty($reservasActivas ?? [])): ?>
    <?php foreach ($reservasActivas as $reserva): ?>
    <!-- Modal para gestionar reserva -->
    <div class="modal fade" id="reservaModal<?= $reserva['id'] ?>" tabindex="-1" aria-labelledby="reservaModalLabel<?= $reserva['id'] ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="reservaModalLabel<?= $reserva['id'] ?>">Gestionar Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- Información de la Reserva -->
                    <div class="mb-4">
                        <h6 class="text-uppercase text-secondary small fw-bold mb-3">Detalles de la Reserva</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <small class="text-secondary d-block mb-1">Libro</small>
                                        <strong><?= htmlspecialchars($reserva['libro']) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <small class="text-secondary d-block mb-1">Estudiante</small>
                                        <strong><?= htmlspecialchars($reserva['estudiante']) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <small class="text-secondary d-block mb-1">Fecha de Reserva</small>
                                        <strong><?= htmlspecialchars($reserva['fecha_reserva']) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <small class="text-secondary d-block mb-1">Expira</small>
                                        <strong><?= htmlspecialchars($reserva['fecha_expiracion']) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($isBibliotecario ?? false): ?>
                    <hr>

                    <!-- Formulario para Aprobar -->
                    <div class="mb-4">
                        <h6 class="text-uppercase text-secondary small fw-bold mb-3">Aprobar Reserva y Crear Préstamo</h6>
                        <form method="post" action="<?= htmlspecialchars(url('reservas/aprobar/' . $reserva['id'])) ?>" id="aprobarForm<?= $reserva['id'] ?>">
                            <div class="mb-3">
                                <label for="fechaDevolucion<?= $reserva['id'] ?>" class="form-label">Fecha de Devolución <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="fechaDevolucion<?= $reserva['id'] ?>" name="fecha_devolucion" required min="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="mb-3">
                                <label for="comentarios<?= $reserva['id'] ?>" class="form-label">Comentarios</label>
                                <textarea class="form-control" id="comentarios<?= $reserva['id'] ?>" name="comentarios" rows="3" placeholder="Agregar observaciones sobre el préstamo..."></textarea>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer border-top">
                    <?php if ($isBibliotecario ?? false): ?>
                    <form method="post" action="<?= htmlspecialchars(url('reservas/cancelar/' . $reserva['id'])) ?>" class="me-auto">
                        <button type="submit" class="btn btn-outline-danger">Cancelar Reserva</button>
                    </form>
                    <button type="submit" form="aprobarForm<?= $reserva['id'] ?>" class="btn btn-success">Aprobar y Prestar</button>
                    <?php else: ?>
                    <form method="post" action="<?= htmlspecialchars(url('reservas/cancelar/' . $reserva['id'])) ?>">
                        <button type="submit" class="btn btn-outline-danger">Cancelar Reserva</button>
                    </form>
                    <?php endif; ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<section>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Historial completo</h2>
        <span class="badge badge-soft-secondary fs-6"><?= count($historial ?? []) ?> total</span>
    </div>

    <div class="table-card p-4">
        <?php if (empty($historial ?? [])): ?>
            <div class="empty-state">
                <h3 class="h5 mb-2">Sin historial</h3>
                <p class="text-secondary mb-0">No hay movimientos registrados en el historial.</p>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial as $reserva): ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($reserva['libro']) ?></td>
                                <td><?= htmlspecialchars($reserva['estudiante']) ?></td>
                                <td><?= htmlspecialchars($reserva['fecha_reserva']) ?></td>
                                <td><?= htmlspecialchars($reserva['fecha_expiracion']) ?></td>
                                <td><span class="badge <?= htmlspecialchars(status_badge_class($reserva['estado'])) ?>"><?= htmlspecialchars($reserva['estado']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para formatear tiempo en HH:MM:SS
    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    // Función para actualizar cuenta regresiva
    function updateCountdowns() {
        const countdownElements = document.querySelectorAll('[id^="countdown-"]');

        countdownElements.forEach(element => {
            const reservaId = element.id.replace('countdown-', '');
            const row = element.closest('tr');

            // Obtener fecha de expiración registrada en el atributo data-expiry
            const expiryText = element.dataset.expiry;
            if (!expiryText) return;

            const expiryDate = new Date(expiryText.replace(' ', 'T'));

            if (isNaN(expiryDate.getTime())) return;

            const now = new Date();
            const timeDiff = expiryDate - now;
            const secondsLeft = Math.floor(timeDiff / 1000);

            if (secondsLeft <= 0) {
                element.textContent = 'EXPIRADO';
                element.className = 'badge badge-soft-danger fw-semibold';
                return;
            }

            const timeString = formatTime(secondsLeft);
            element.textContent = timeString;

            // Cambiar color según tiempo restante
            if (secondsLeft <= 3600) { // Menos de 1 hora
                element.className = 'badge badge-soft-danger fw-semibold';
            } else if (secondsLeft <= 7200) { // Menos de 2 horas
                element.className = 'badge badge-soft-warning fw-semibold';
            } else {
                element.className = 'badge badge-soft-success fw-semibold';
            }
        });
    }

    // Actualizar cada segundo
    updateCountdowns();
    setInterval(updateCountdowns, 1000);
});
</script>

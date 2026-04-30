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
    </div>
</section>

<!-- Historial Completo -->
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

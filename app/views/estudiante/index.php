<section class="mb-4">
    <div class="hero-card">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <span class="eyebrow mb-3">Estudiantes</span>
                <h1 class="section-title"><?= htmlspecialchars($title ?? 'Listado de estudiantes') ?></h1>
                <p class="section-subtitle">Consulta cada estudiante junto con sus reservas, prestamos y sanciones activos.</p>
            </div>
        </div>
    </div>
</section>

<section class="table-card p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="h5 mb-2">Actividad activa</h2>
            <p class="text-secondary mb-0">Los prestamos retrasados se muestran porque siguen pendientes de entrega.</p>
        </div>
        <span class="badge badge-soft-primary fs-6"><?= count($estudiantes ?? []) ?> estudiantes</span>
    </div>
    <div class="mb-4">
        <input
            type="search"
            id="studentSearch"
            class="form-control"
            placeholder="Buscar estudiante por nombre, correo, usuario o telefono"
            autocomplete="off"
        >
    </div>

    <?php if (empty($estudiantes ?? [])): ?>
        <div class="empty-state">
            <h3 class="h5 mb-2">No hay estudiantes registrados</h3>
            <p class="text-secondary mb-0">Registra estudiantes para consultar su actividad academica.</p>
        </div>
    <?php else: ?>
        <div id="studentSearchEmpty" class="empty-state d-none mb-3">
            <h3 class="h5 mb-2">Sin resultados</h3>
            <p class="text-secondary mb-0">No hay estudiantes que coincidan con la busqueda.</p>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Reservas activas</th>
                        <th>Prestamos activos</th>
                        <th>Sanciones activas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estudiantes as $estudiante): ?>
                        <?php
                            $estudianteActividad = $actividad[(int) $estudiante['id']] ?? ['reservas' => [], 'prestamos' => [], 'sanciones' => []];
                            $reservasActivas = $estudianteActividad['reservas'];
                            $prestamosActivos = $estudianteActividad['prestamos'];
                            $sancionesActivas = $estudianteActividad['sanciones'];
                            $studentSearchText = implode(' ', [
                                $estudiante['nombre'] ?? '',
                                $estudiante['email'] ?? '',
                                $estudiante['username'] ?? '',
                                $estudiante['telefono'] ?? '',
                            ]);
                        ?>
                        <tr data-student-search="<?= htmlspecialchars(strtolower($studentSearchText)) ?>">
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($estudiante['nombre']) ?></div>
                                <div class="text-secondary small"><?= htmlspecialchars($estudiante['email']) ?></div>
                                <?php if (!empty($estudiante['telefono'])): ?>
                                    <div class="text-secondary small"><?= htmlspecialchars($estudiante['telefono']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= htmlspecialchars(status_badge_class('Activa')) ?> mb-2"><?= count($reservasActivas) ?></span>
                                <?php if (empty($reservasActivas)): ?>
                                    <div class="text-secondary small">Sin reservas activas</div>
                                <?php else: ?>
                                    <div class="vstack gap-2">
                                        <?php foreach ($reservasActivas as $reserva): ?>
                                            <?php $expirationDateTime = date('Y-m-d H:i:s', strtotime($reserva['created_at'] . ' +1 day')); ?>
                                            <div>
                                                <div class="fw-semibold small"><?= htmlspecialchars($reserva['libro']) ?></div>
                                                <div class="text-secondary small">
                                                    Restante:
                                                    <span class="reservation-countdown" data-expiry="<?= htmlspecialchars($expirationDateTime) ?>">
                                                        Calculando...
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= htmlspecialchars(status_badge_class('Activo')) ?> mb-2"><?= count($prestamosActivos) ?></span>
                                <?php if (empty($prestamosActivos)): ?>
                                    <div class="text-secondary small">Sin prestamos activos</div>
                                <?php else: ?>
                                    <div class="vstack gap-2">
                                        <?php foreach ($prestamosActivos as $prestamo): ?>
                                            <div>
                                                <div class="fw-semibold small"><?= htmlspecialchars($prestamo['libro']) ?></div>
                                                <div class="text-secondary small">
                                                    Devolucion: <?= htmlspecialchars($prestamo['fecha_devolucion']) ?>
                                                    <span class="badge <?= htmlspecialchars(status_badge_class($prestamo['estado'])) ?> ms-1"><?= htmlspecialchars($prestamo['estado']) ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= empty($sancionesActivas) ? 'badge-soft-secondary' : 'badge-soft-danger' ?> mb-2"><?= count($sancionesActivas) ?></span>
                                <?php if (empty($sancionesActivas)): ?>
                                    <div class="text-secondary small">Sin sanciones activas</div>
                                <?php else: ?>
                                    <div class="vstack gap-2">
                                        <?php foreach ($sancionesActivas as $sancion): ?>
                                            <div>
                                                <div class="fw-semibold small text-danger"><?= htmlspecialchars($sancion['razon']) ?></div>
                                                <div class="text-secondary small">Hasta: <?= htmlspecialchars($sancion['fecha_fin']) ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const studentSearch = document.getElementById('studentSearch');
    const studentRows = document.querySelectorAll('tr[data-student-search]');
    const studentSearchEmpty = document.getElementById('studentSearchEmpty');

    if (studentSearch) {
        studentSearch.addEventListener('input', function () {
            const term = studentSearch.value.trim().toLowerCase();
            let visibleRows = 0;

            studentRows.forEach(function (row) {
                const matches = row.dataset.studentSearch.includes(term);
                row.classList.toggle('d-none', !matches);

                if (matches) {
                    visibleRows++;
                }
            });

            if (studentSearchEmpty) {
                studentSearchEmpty.classList.toggle('d-none', visibleRows > 0);
            }
        });
    }

    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    function updateReservationCountdowns() {
        document.querySelectorAll('.reservation-countdown').forEach(function (element) {
            const expiryText = element.dataset.expiry;
            if (!expiryText) {
                return;
            }

            const expiryDate = new Date(expiryText.replace(' ', 'T'));
            if (Number.isNaN(expiryDate.getTime())) {
                element.textContent = 'No disponible';
                return;
            }

            const secondsLeft = Math.floor((expiryDate - new Date()) / 1000);

            if (secondsLeft <= 0) {
                element.textContent = 'EXPIRADO';
                element.className = 'reservation-countdown text-danger fw-semibold';
                return;
            }

            element.textContent = formatTime(secondsLeft);
            element.className = 'reservation-countdown text-success fw-semibold';
        });
    }

    updateReservationCountdowns();
    setInterval(updateReservationCountdowns, 1000);
});
</script>

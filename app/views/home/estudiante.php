<section class="mb-4">
    <div class="hero-card">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <span class="eyebrow mb-3">Panel Estudiantil</span>
                <h1 class="section-title">Hola, <?= htmlspecialchars($user['username']) ?></h1>
                <p class="section-subtitle">Consulta tus movimientos activos, revisa fechas de devolucion y encuentra nuevas lecturas desde una sola vista.</p>
            </div>
            <a href="<?= htmlspecialchars(url('libros')) ?>" class="btn btn-primary">Explorar catalogo</a>
        </div>
    </div>
</section>

<section class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="table-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-0">Mis prestamos activos</h2>
                <span class="badge badge-soft-primary"><?= count($mis_prestamos) ?> abiertos</span>
            </div>
            <?php if (empty($mis_prestamos)): ?>
                <div class="empty-state">
                    <p class="mb-3">No tienes prestamos activos por ahora.</p>
                    <a href="<?= htmlspecialchars(url('libros')) ?>" class="btn btn-primary">Buscar libros</a>
                </div>
            <?php else: ?>
                <div class="vstack gap-3">
                    <?php foreach ($mis_prestamos as $prestamo): ?>
                        <div class="quick-link">
                            <div>
                                <strong><?= htmlspecialchars($prestamo['titulo']) ?></strong>
                                <span>Fecha de devolucion: <?= htmlspecialchars($prestamo['fecha_devolucion']) ?></span>
                            </div>
                            <span class="badge <?= htmlspecialchars(status_badge_class('Activo')) ?>">Activo</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="table-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-0">Mis reservas activas</h2>
                <span class="badge badge-soft-success"><?= count($mis_reservas) ?> activas</span>
            </div>
            <?php if (empty($mis_reservas)): ?>
                <div class="empty-state">
                    <p class="mb-3">No tienes reservas activas en este momento.</p>
                    <a href="<?= htmlspecialchars(url('reservas/crear')) ?>" class="btn btn-success">Crear reserva</a>
                </div>
            <?php else: ?>
                <div class="vstack gap-3">
                    <?php foreach ($mis_reservas as $reserva): ?>
                        <div class="quick-link">
                            <div>
                                <strong><?= htmlspecialchars($reserva['titulo']) ?></strong>
                                <span>Reservado desde: <?= htmlspecialchars($reserva['fecha_reserva']) ?></span>
                            </div>
                            <span class="badge <?= htmlspecialchars(status_badge_class('Activa')) ?>">Activa</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="table-card p-4">
    <h2 class="h4 mb-3">Acciones disponibles</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <a href="<?= htmlspecialchars(url('libros/buscar')) ?>" class="quick-link h-100">
                <div>
                    <strong>Buscar libros</strong>
                    <span>Encuentra titulos por autor, nombre o ISBN.</span>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?= htmlspecialchars(url('reservas')) ?>" class="quick-link h-100">
                <div>
                    <strong>Ver reservas</strong>
                    <span>Consulta el estado de todas tus reservas.</span>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?= htmlspecialchars(url('prestamos')) ?>" class="quick-link h-100">
                <div>
                    <strong>Ver historial</strong>
                    <span>Revisa tus prestamos y fechas importantes.</span>
                </div>
            </a>
        </div>
    </div>
</section>

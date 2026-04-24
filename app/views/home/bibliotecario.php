<section class="mb-4">
    <div class="hero-card">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <span class="eyebrow mb-3">Panel Bibliotecario</span>
                <h1 class="section-title">Bienvenido, <?= htmlspecialchars($user['username']) ?></h1>
                <p class="section-subtitle">Supervisa el catalogo, controla prestamos activos y ejecuta acciones clave desde un solo lugar.</p>
            </div>
            <a href="<?= htmlspecialchars(url('libros/crear')) ?>" class="btn btn-primary">Agregar libro</a>
        </div>
    </div>
</section>

<section class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="metric-card metric-card--primary">
            <span class="metric-card__label">Catalogo</span>
            <div class="metric-card__value"><?= htmlspecialchars((string) $libros_count) ?></div>
            <p class="text-secondary mb-3">Total de libros registrados en la biblioteca.</p>
            <a href="<?= htmlspecialchars(url('libros')) ?>" class="btn btn-outline-primary">Ver catalogo</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card metric-card--warning">
            <span class="metric-card__label">Prestamos activos</span>
            <div class="metric-card__value"><?= htmlspecialchars((string) $prestamos_activos) ?></div>
            <p class="text-secondary mb-3">Movimientos pendientes de devolucion.</p>
            <a href="<?= htmlspecialchars(url('prestamos')) ?>" class="btn btn-outline-dark">Gestionar prestamos</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card metric-card--info">
            <span class="metric-card__label">Estudiantes</span>
            <div class="metric-card__value"><?= htmlspecialchars((string) $estudiantes_count) ?></div>
            <p class="text-secondary mb-3">Usuarios con acceso al modulo academico.</p>
            <a href="<?= htmlspecialchars(url('registro')) ?>" class="btn btn-outline-secondary">Registrar nuevo</a>
        </div>
    </div>
</section>

<section class="row g-4">
    <div class="col-lg-7">
        <div class="table-card p-4 h-100">
            <h2 class="h4 mb-3">Acciones rapidas</h2>
            <div class="vstack gap-3">
                <a href="<?= htmlspecialchars(url('libros/crear')) ?>" class="quick-link">
                    <div>
                        <strong>Agregar nuevo libro</strong>
                        <span>Registra ejemplares y define su estado inicial.</span>
                    </div>
                    <span class="badge badge-soft-success">Nuevo</span>
                </a>
                <a href="<?= htmlspecialchars(url('prestamos/crear')) ?>" class="quick-link">
                    <div>
                        <strong>Registrar prestamo</strong>
                        <span>Asocia un libro con un estudiante y una fecha de devolucion.</span>
                    </div>
                    <span class="badge badge-soft-primary">Operacion</span>
                </a>
                <a href="<?= htmlspecialchars(url('prestamos')) ?>" class="quick-link">
                    <div>
                        <strong>Procesar devoluciones</strong>
                        <span>Revisa el estado de cada prestamo y confirma entregas.</span>
                    </div>
                    <span class="badge badge-soft-warning">Seguimiento</span>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="table-card p-4 h-100">
            <h2 class="h4 mb-3">Resumen del rol</h2>
            <div class="vstack gap-3">
                <div class="quick-link">
                    <div>
                        <strong>Catalogo visible</strong>
                        <span>Actualiza disponibilidad, mantenimiento y ediciones.</span>
                    </div>
                </div>
                <div class="quick-link">
                    <div>
                        <strong>Control de flujo</strong>
                        <span>Prestamos y devoluciones con indicadores por color.</span>
                    </div>
                </div>
                <div class="quick-link">
                    <div>
                        <strong>Operaciones seguras</strong>
                        <span>Formularios con validaciones y mensajes claros.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

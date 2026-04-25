<section class="mb-4">
    <div class="hero-card">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                
                <h1 class="section-title">Bienvenido, <?= htmlspecialchars(Auth::getUserName() ?? Auth::getUser()['username']) ?></h1>
                <p class="section-subtitle">Supervisa el catalogo, controla prestamos activos y ejecuta acciones clave desde un solo lugar.</p>
            </div>
           
        </div>
    </div>
</section>

<section class="row g-4 mb-4" style="display: flex; justify-content: space-between; align-items: center;margin: 1rem 0;">
    <div class="col-md-4">
        <div class="metric-card metric-card--primary">
            <span class="metric-card__label">Libros</span>
            <div class="metric-card__value"><?= htmlspecialchars((string) $libros_count) ?></div>
            <p class="text-secondary mb-3">Total de libros registrados en la biblioteca.</p>
            <a href="<?= htmlspecialchars(url('libros')) ?>" class="btn btn-outline-primary">Ver libros</a>
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
            <a href="<?= htmlspecialchars(url('registro/bibliotecario')) ?>" class="btn btn-outline-secondary">Registrar nuevo</a>
        </div>
    </div>
</section>

<section class="row g-4">
    <div class="col-lg-7">
        <div class=" p-4 h-100">
            
            <div class="vstack gap-3">
                <a href="<?= htmlspecialchars(url('libros/crear')) ?>" class="quick-link metric-card metric-card--info">
                    <div>
                        <strong>Agregar nuevo libro</strong>
                        <span>Registra ejemplares y define su estado inicial.</span>
                    </div>
                    <span class="badge badge-soft-primary">Libros</span>
                </a>
                <a href="<?= htmlspecialchars(url('prestamos/crear')) ?>" class="quick-link metric-card metric-card--warning">
                    <div>
                        <strong>Registrar prestamo</strong>
                        <span>Asocia un libro con un estudiante y una fecha de devolucion.</span>
                    </div>
                    <span class="badge badge-soft-primary">Prestamos</span>
                </a>
                <a href="<?= htmlspecialchars(url('prestamos')) ?>" class="quick-link metric-card metric-card--primary">
                    <div>
                        <strong>Procesar devoluciones</strong>
                        <span>Revisa el estado de cada prestamo y confirma entregas.</span>
                    </div>
                    <span class="badge badge-soft-primary">Devoluciones</span>
                </a>
            </div>
        </div>
    </div>
    
</section>

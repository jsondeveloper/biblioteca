<section class="mb-4">
    <div class="hero-card">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <span class="eyebrow mb-3">Panel Bibliotecario</span>
                <h1 class="section-title">Bienvenido, <?= htmlspecialchars(Auth::getUserName() ?? Auth::getUser()['username']) ?></h1>
                <p class="section-subtitle">Supervisa el catalogo, controla prestamos activos y ejecuta acciones clave desde un solo lugar.</p>
            </div>
            <a href="<?= htmlspecialchars(url('libros/crear')) ?>" class="btn btn-primary">Agregar libro</a>
        </div>
    </div>
</section>

<section class="row g-4 mb-4">
<div class="col-md-4">
        <div class="metric-card metric-card--success">
            <span class="metric-card__label">Reservas activas</span>
            <div class="metric-card__value"><?= htmlspecialchars((string) $reservas_activas) ?></div>
            <p class="text-secondary mb-3">Solicitudes vigentes pendientes de aprobacion.</p>
            <a href="<?= htmlspecialchars(url('reservas')) ?>" class="btn btn-outline-success">Gestionar reservas</a>
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
        <div class="metric-card metric-card--danger">
            <span class="metric-card__label">Sanciones activas</span>
            <div class="metric-card__value"><?= htmlspecialchars((string) $sanciones_activas) ?></div>
            <p class="text-secondary mb-3">Restricciones vigentes asignadas a estudiantes.</p>
            <a href="<?= htmlspecialchars(url('sanciones')) ?>" class="btn btn-outline-danger">Ver sanciones</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card metric-card--secondary">
            <span class="metric-card__label">Categorias</span>
            <div class="metric-card__value"><?= htmlspecialchars((string) $categorias_count) ?></div>
            <p class="text-secondary mb-3">Clasificaciones disponibles para organizar libros.</p>
            <a href="<?= htmlspecialchars(url('categorias')) ?>" class="btn btn-outline-secondary">Ver categorias</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card metric-card--primary">
            <span class="metric-card__label">Catalogo</span>
            <div class="metric-card__value"><?= htmlspecialchars((string) $libros_count) ?></div>
            <p class="text-secondary mb-3">Total de libros registrados en la biblioteca.</p>
            <a href="<?= htmlspecialchars(url('libros')) ?>" class="btn btn-outline-primary">Ver catalogo</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card metric-card--info">
            <span class="metric-card__label">Estudiantes</span>
            <div class="metric-card__value"><?= htmlspecialchars((string) $estudiantes_count) ?></div>
            <p class="text-secondary mb-3">Usuarios con acceso al modulo academico.</p>
            <a href="<?= htmlspecialchars(url('estudiantes')) ?>" class="btn btn-outline-secondary">Ver estudiantes</a>
        </div>
    </div>
    
    
    
</section>


<section class="table-card p-4">
    <h2 class="h4 mb-3">Acciones disponibles</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <a href="<?= htmlspecialchars(url('libros/crear')) ?>" class="quick-link">
                    <div>
                        <strong>Agregar nuevo libro</strong>
                        <span>Registra ejemplares y define su estado inicial.</span>
                    </div>
                    
                </a>
        </div>
        
        <div class="col-md-4">
            <a href="<?= htmlspecialchars(url('prestamos')) ?>" class="quick-link">
                    <div>
                        <strong>Procesar devoluciones</strong>
                        <span>Revisa el estado de cada prestamo y confirma entregas.</span>
                    </div>
                    
                </a>
        </div>
        <div class="col-md-4">
            <a href="<?= htmlspecialchars(url('sanciones')) ?>" class="quick-link">
                    <div>
                        <strong>Ver sanciones</strong>
                        <span>Consulta y administra sanciones vigentes y registradas.</span>
                    </div>
                    
                </a>
        </div>
    </div>
</section>

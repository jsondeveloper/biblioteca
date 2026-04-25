<section class="mb-5">
    <div class="hero-card">
        <span class="eyebrow mb-3">Centro Academico Digital</span>
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h1 class="display-5 mb-3">Gestiona la biblioteca con una experiencia clara, rapida y agradable.</h1>
                <p class="lead text-secondary mb-4">
                    Consulta catalogo, administra prestamos, registra reservas y sigue el estado de cada libro desde un panel moderno y organizado.
                </p>
                
            </div>
            <div class="col-lg-5">
                <div class="panel-card p-4">
                    
                    <div class="vstack gap-3">
                        <div class="quick-link">
                            <div>
                                <strong>Catalogo organizado</strong>
                                <span>Busqueda por titulo, autor e ISBN.</span>
                            </div>
                            <span class="badge badge-soft-primary">Libros</span>
                        </div>
                        <div class="quick-link">
                            <div>
                                <strong>Prestamos con control visual</strong>
                                <span>Estados resaltados y acciones claras.</span>
                            </div>
                            <span class="badge badge-soft-success">Prestamos</span>
                        </div>
                        <div class="quick-link">
                            <div>
                                <strong>Reservas simples</strong>
                                <span>Seguimiento de expiracion y disponibilidad.</span>
                            </div>
                            <span class="badge badge-soft-warning">Reservas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="row g-4" style="display: flex; margin-bottom: 2rem;">
    <div class="col-lg-6">
        <div class="metric-card metric-card--primary">
            <span class="metric-card__label">Estudiantes</span>
            <h2 class="h3 mt-2">Consulta y reserva libros sin friccion</h2>
            <p class="text-secondary mb-4">Visualiza prestamos activos, historial reciente y reservas en una sola vista.</p>
            <a href="<?= htmlspecialchars(url('registro')) ?>" class="btn btn-primary">Registrarme como estudiante</a>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="metric-card metric-card--warning">
            <span class="metric-card__label">Bibliotecarios</span>
            <h2 class="h3 mt-2">Administra el flujo completo de la biblioteca</h2>
            <p class="text-secondary mb-4">Crea libros, registra prestamos y controla devoluciones con formularios claros.</p>
            <a href="<?= htmlspecialchars(url('registro')) ?>" class="btn btn-warning">Registrarme como bibliotecario</a>
        </div>
    </div>
</section>

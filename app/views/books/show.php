<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Detalle del libro</span>
        <h1 class="section-title"><?= htmlspecialchars($book['titulo']) ?></h1>
        <p class="section-subtitle">Consulta la ficha general del ejemplar seleccionado.</p>
    </div>
</section>

<section class="row g-4">
    <div class="col-lg-8">
        <div class="table-card p-4 h-100">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Autor</label>
                    <div class="form-control bg-light-subtle"><?= htmlspecialchars($book['autor']) ?></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">ISBN</label>
                    <div class="form-control bg-light-subtle"><?= htmlspecialchars($book['isbn']) ?></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Año de publicacion</label>
                    <div class="form-control bg-light-subtle"><?= htmlspecialchars((string) ($book['anio_publicacion'] ?? 'No disponible')) ?></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estado</label>
                    <div><span class="badge <?= htmlspecialchars(status_badge_class($book['estado'] ?? 'Disponible')) ?>"><?= htmlspecialchars($book['estado'] ?? 'Disponible') ?></span></div>
                </div>
                <div class="col-12">
                    <label class="form-label">Descripcion</label>
                    <div class="form-control bg-light-subtle"><?= htmlspecialchars($book['descripcion'] ?? 'Sin descripcion disponible.') ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel-card p-4 h-100">
            <h2 class="h5 mb-3">Acciones</h2>
            <div class="d-grid gap-3">
                <a class="btn btn-primary" href="<?= htmlspecialchars(url('books')) ?>">Volver al listado</a>
                <a class="btn btn-outline-secondary" href="<?= htmlspecialchars(url('libros')) ?>">Ir al catalogo principal</a>
            </div>
        </div>
    </div>
</section>

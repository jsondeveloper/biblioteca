<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Categorias</span>
        <h1 class="section-title"><?= htmlspecialchars($title ?? 'Categoria') ?></h1>
        <p class="section-subtitle">Define el nombre y una descripcion breve para agrupar libros del catalogo.</p>
    </div>
</section>

<section class="form-card p-4 p-lg-5">
    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <strong>No pudimos guardar la categoria.</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars(isset($categoria['id']) ? url('categorias/actualizar/' . $categoria['id']) : url('categorias/crear')) ?>" class="needs-validation" novalidate>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($categoria['nombre'] ?? '') ?>" class="form-control form-control-lg" required>
                <div class="invalid-feedback">Ingresa el nombre de la categoria.</div>
            </div>
            <div class="col-12">
                <label class="form-label">Descripcion</label>
                <textarea class="form-control" name="descripcion" rows="5" placeholder="Describe brevemente el uso de esta categoria."><?= htmlspecialchars($categoria['descripcion'] ?? '') ?></textarea>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-3 mt-4">
            <button type="submit" class="btn btn-primary btn-lg"><?= isset($categoria['id']) ? 'Actualizar categoria' : 'Guardar categoria' ?></button>
            <a href="<?= htmlspecialchars(url('categorias')) ?>" class="btn btn-outline-secondary btn-lg">Volver al listado</a>
        </div>
    </form>
</section>

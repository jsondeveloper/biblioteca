<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Catalogo</span>
        <h1 class="section-title"><?= htmlspecialchars($title ?? 'Libro') ?></h1>
        <p class="section-subtitle">Completa la informacion del ejemplar con datos claros y consistentes.</p>
    </div>
</section>

<section class="form-card p-4 p-lg-5">
    <form method="post" action="<?= htmlspecialchars(isset($libro) ? url('libros/actualizar/' . $libro['id']) : url('libros/crear')) ?>" class="needs-validation" novalidate>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Titulo</label>
                <input type="text" name="titulo" value="<?= htmlspecialchars($libro['titulo'] ?? '') ?>" class="form-control form-control-lg" required>
                <div class="invalid-feedback">Ingresa el titulo del libro.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Autor</label>
                <input type="text" name="autor" value="<?= htmlspecialchars($libro['autor'] ?? '') ?>" class="form-control form-control-lg" required>
                <div class="invalid-feedback">Ingresa el autor del libro.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">ISBN</label>
                <input type="text" name="isbn" value="<?= htmlspecialchars($libro['isbn'] ?? '') ?>" class="form-control" required minlength="10">
                <div class="form-hint">Usa el identificador oficial del libro.</div>
                <div class="invalid-feedback">Ingresa un ISBN valido.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Categoria</label>
                <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                    <a href="<?= htmlspecialchars(url('categorias')) ?>" class="btn btn-sm btn-outline-secondary action-icon-btn">
                        <i class="bi bi-tags"></i>
                        <span>Gestionar categorias</span>
                    </a>
                </div>
                <select name="categoria_id" class="form-select" required>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>" <?= isset($libro) && $libro['categoria_id'] == $categoria['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Selecciona una categoria.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select" required>
                    <?php foreach (['Disponible', 'Reservado', 'Prestado', 'Mantenimiento'] as $estado): ?>
                        <option value="<?= $estado ?>" <?= isset($libro) && $libro['estado'] === $estado ? 'selected' : '' ?>><?= $estado ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Año de publicacion</label>
                <input type="number" name="anio_publicacion" value="<?= htmlspecialchars($libro['anio_publicacion'] ?? '') ?>" class="form-control" min="1500" max="2099">
            </div>
            <div class="col-12">
                <label class="form-label">Descripcion</label>
                <textarea class="form-control" name="descripcion" rows="5" placeholder="Resume el contenido o detalles importantes del libro."><?= htmlspecialchars($libro['descripcion'] ?? '') ?></textarea>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-3 mt-4">
            <button type="submit" class="btn btn-primary btn-lg"><?= isset($libro) ? 'Actualizar libro' : 'Guardar libro' ?></button>
            <a href="<?= htmlspecialchars(url('libros')) ?>" class="btn btn-outline-secondary btn-lg">Volver al catalogo</a>
        </div>
    </form>
</section>

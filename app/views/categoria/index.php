<section class="mb-4">
    <div class="hero-card">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <span class="eyebrow mb-3">Categorias</span>
                <h1 class="section-title"><?= htmlspecialchars($title ?? 'Categorias') ?></h1>
                <p class="section-subtitle">Organiza el catalogo administrando las categorias disponibles para los libros.</p>
            </div>
            <a class="btn btn-success" href="<?= htmlspecialchars(url('categorias/crear')) ?>">
                <i class="bi bi-folder-plus me-2"></i>Nueva categoria
            </a>
        </div>
    </div>
</section>

<section class="table-card p-4">
    <?php if (empty($categorias)): ?>
        <div class="empty-state">
            <h2 class="h4 mb-2">Todavia no hay categorias</h2>
            <p class="text-secondary mb-0">Crea la primera categoria para comenzar a organizar el catalogo.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($categoria['nombre']) ?></td>
                            <td class="text-secondary"><?= htmlspecialchars($categoria['descripcion'] ?? 'Sin descripcion') ?></td>
                            <td class="text-end">
                                <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                    
                                <a class="btn btn-sm btn-outline-primary action-icon-btn" href="<?= htmlspecialchars(url('categorias/actualizar/' . $categoria['id'])) ?>" title="Editar categoria">
                                                <i class="bi bi-pencil-square"></i>
                                                <span class="visually-hidden">Editar</span>
                                            </a>
                                            <form method="post" action="<?= htmlspecialchars(url('categorias/eliminar/' . $categoria['id'])) ?>" class="d-inline-block">
                                                <button type="submit" class="btn btn-sm btn-outline-danger action-icon-btn" title="Eliminar categoria" onclick="return confirm('Se eliminara esta categoria. Deseas continuar?')">
                                                    <i class="bi bi-trash3"></i>
                                                    <span class="visually-hidden">Eliminar</span>
                                                </button>
                                            </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

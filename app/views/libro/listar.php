<?php Auth::start(); $isBibliotecario = Auth::hasRole('bibliotecario'); ?>
<section class="mb-4">
    <div class="hero-card">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <span class="eyebrow mb-3">Catalogo</span>
                <h1 class="section-title"><?= htmlspecialchars($title ?? 'Libros') ?></h1>
                <p class="section-subtitle">
                    <?= !empty($term) ? 'Resultados filtrados para "' . htmlspecialchars($term) . '".' : 'Explora el inventario disponible de la biblioteca.' ?>
                </p>
            </div>
            <?php if ($isBibliotecario): ?>
                <a class="btn btn-success" href="<?= htmlspecialchars(url('libros/crear')) ?>">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo libro
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="table-card p-4 mb-4">
    <form method="get" action="<?= htmlspecialchars(url('libros/buscar')) ?>" class="row g-3 align-items-end">
        <div class="col-lg-9">
            <label class="form-label">Buscar por titulo, autor o ISBN</label>
            <input type="text" class="form-control form-control-lg" name="q" value="<?= htmlspecialchars($term ?? '') ?>" placeholder="Ej. Cien años de soledad o 978-1234567890">
        </div>
        <div class="col-lg-3 d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-search me-2"></i>Buscar libro
            </button>
        </div>
    </form>
</section>

<section class="table-card p-4">
    <?php if (empty($libros)): ?>
        <div class="empty-state">
            <h2 class="h4 mb-2">No se encontraron resultados</h2>
            <p class="text-secondary mb-0">Prueba con otro termino o registra un nuevo libro si tienes permisos.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Estado</th>
                        <?php if ($isBibliotecario): ?>
                            <th class="text-end">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($libros as $libro): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($libro['titulo']) ?></div>
                                <div class="small text-secondary">Publicado: <?= htmlspecialchars((string) ($libro['anio_publicacion'] ?? 'N/D')) ?></div>
                            </td>
                            <td><?= htmlspecialchars($libro['autor']) ?></td>
                            <td><code><?= htmlspecialchars($libro['isbn']) ?></code></td>
                            <td><span class="badge <?= htmlspecialchars(status_badge_class($libro['estado'])) ?>"><?= htmlspecialchars($libro['estado']) ?></span></td>
                            <?php if ($isBibliotecario): ?>
                                <td class="text-end">
                                    <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                        <?php if ($libro['estado'] === 'Disponible'): ?>
                                            <a class="btn btn-sm btn-outline-success action-icon-btn" href="<?= htmlspecialchars(url('prestamos/crear?libro_id=' . $libro['id'])) ?>" title="Registrar prestamo">
                                                <i class="bi bi-journal-plus"></i>
                                                <span>Prestar</span>
                                            </a>
                                        <?php endif; ?>
                                        <a class="btn btn-sm btn-outline-primary action-icon-btn" href="<?= htmlspecialchars(url('libros/actualizar/' . $libro['id'])) ?>" title="Editar libro">
                                            <i class="bi bi-pencil-square"></i>
                                            <span>Editar</span>
                                        </a>
                                        <form method="post" action="<?= htmlspecialchars(url('libros/eliminar/' . $libro['id'])) ?>" class="d-inline-block">
                                            <button type="submit" class="btn btn-sm btn-outline-danger action-icon-btn" title="Eliminar libro" onclick="return confirm('Se eliminara este libro. Deseas continuar?')">
                                                <i class="bi bi-trash3"></i>
                                                <span>Eliminar</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

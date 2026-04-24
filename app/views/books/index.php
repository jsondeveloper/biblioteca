<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Coleccion</span>
        <h1 class="section-title">Libros disponibles</h1>
        <p class="section-subtitle">Vista resumida del catalogo con acceso rapido al detalle de cada libro.</p>
    </div>
</section>

<section class="table-card p-4">
    <?php if (empty($books)): ?>
        <div class="empty-state">
            <p class="mb-0">No hay libros registrados.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Autor</th>
                        <th>Ano</th>
                        <th>ISBN</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($book['titulo']) ?></td>
                            <td><?= htmlspecialchars($book['autor']) ?></td>
                            <td><?= htmlspecialchars((string) ($book['anio_publicacion'] ?? '')) ?></td>
                            <td><code><?= htmlspecialchars($book['isbn']) ?></code></td>
                            <td><a class="btn btn-sm btn-outline-primary" href="<?= htmlspecialchars(url('books/' . $book['id'])) ?>">Ver detalle</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

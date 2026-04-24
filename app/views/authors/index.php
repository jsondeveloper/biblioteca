<section class="mb-4">
    <div class="hero-card">
        <span class="eyebrow mb-3">Autores</span>
        <h1 class="section-title">Autores registrados</h1>
        <p class="section-subtitle">Explora las personas que forman parte del catalogo disponible.</p>
    </div>
</section>

<section class="table-card p-4">
    <?php if (empty($authors)): ?>
        <div class="empty-state">
            <p class="mb-0">No hay autores registrados.</p>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($authors as $author): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="panel-card p-4 h-100">
                        <span class="badge badge-soft-primary mb-3">Autor</span>
                        <h2 class="h5 mb-2"><?= htmlspecialchars($author['name']) ?></h2>
                        <p class="text-secondary mb-0">
                            <?= !empty($author['biography']) ? nl2br(htmlspecialchars($author['biography'])) : 'Autor presente en el catalogo bibliografico.' ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

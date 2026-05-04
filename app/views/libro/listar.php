<?php Auth::start(); $isBibliotecario = Auth::hasRole('bibliotecario'); $isEstudiante = Auth::hasRole('estudiante'); ?>
<section class="mb-4">
    <div class="hero-card">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <span class="eyebrow mb-3">Catalogo</span>
                <h1 class="section-title"><?= htmlspecialchars($title ?? 'Libros') ?></h1>
                <p class="section-subtitle">Explora el inventario disponible de la biblioteca.</p>
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
    <div class="book-search">
        <label class="form-label" for="bookSearch">Buscar por titulo, autor, ISBN, estado o anio</label>
        <input
            type="search"
            id="bookSearch"
            class="form-control form-control-lg"
            placeholder="Ej. Cien anios de soledad o 978-1234567890"
            autocomplete="off"
        >
    </div>
</section>

<section class="table-card p-4">
    <?php if (empty($libros)): ?>
        <div class="empty-state">
            <h2 class="h4 mb-2">No se encontraron resultados</h2>
            <p class="text-secondary mb-0">Prueba con otro termino o registra un nuevo libro si tienes permisos.</p>
        </div>
    <?php else: ?>
        <div id="bookSearchEmpty" class="empty-state d-none mb-3">
            <h2 class="h4 mb-2">Sin resultados</h2>
            <p class="text-secondary mb-0">No hay libros que coincidan con la busqueda.</p>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Estado</th>
                        <?php if ($isBibliotecario || $isEstudiante): ?>
                            <th class="text-end">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($libros as $libro): ?>
                        <?php
                            $bookSearchText = implode(' ', [
                                $libro['titulo'] ?? '',
                                $libro['autor'] ?? '',
                                $libro['isbn'] ?? '',
                                $libro['estado'] ?? '',
                                (string) ($libro['anio_publicacion'] ?? ''),
                            ]);
                        ?>
                        <tr data-book-search="<?= htmlspecialchars(strtolower($bookSearchText)) ?>">
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($libro['titulo']) ?></div>
                                <div class="small text-secondary">Publicado: <?= htmlspecialchars((string) ($libro['anio_publicacion'] ?? 'N/D')) ?></div>
                            </td>
                            <td><?= htmlspecialchars($libro['autor']) ?></td>
                            <td><code><?= htmlspecialchars($libro['isbn']) ?></code></td>
                            <td><span class="badge <?= htmlspecialchars(status_badge_class($libro['estado'])) ?>"><?= htmlspecialchars($libro['estado']) ?></span></td>
                            <?php if ($isBibliotecario || $isEstudiante): ?>
                                <td class="text-end">
                                    <div class="book-actions d-inline-flex flex-wrap justify-content-end gap-1">
                                        <?php if ($isEstudiante && $libro['estado'] === 'Disponible'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-success action-icon-btn" onclick="openReservaModal(<?= $libro['id'] ?>, '<?= htmlspecialchars(addslashes($libro['titulo'])) ?>')" title="Reservar libro">
                                                <i class="bi bi-bookmark-plus"></i>
                                                <span class="visually-hidden">Reservar</span>
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($isBibliotecario): ?>
                                            <?php if ($libro['estado'] === 'Disponible'): ?>
                                                <a class="btn btn-sm btn-outline-success action-icon-btn" href="<?= htmlspecialchars(url('prestamos/crear?libro_id=' . $libro['id'])) ?>" title="Registrar prestamo">
                                                    <i class="bi bi-journal-plus"></i>
                                                    <span class="visually-hidden">Prestar</span>
                                                </a>
                                            <?php endif; ?>
                                            <a class="btn btn-sm btn-outline-secondary action-icon-btn" href="<?= htmlspecialchars(url('libros/' . $libro['id'] . '/historial')) ?>" title="Ver hoja de vida">
                                                <i class="bi bi-journal-text"></i>
                                                <span class="visually-hidden">Hoja de vida</span>
                                            </a>
                                            <a class="btn btn-sm btn-outline-primary action-icon-btn" href="<?= htmlspecialchars(url('libros/actualizar/' . $libro['id'])) ?>" title="Editar libro">
                                                <i class="bi bi-pencil-square"></i>
                                                <span class="visually-hidden">Editar</span>
                                            </a>
                                            <form method="post" action="<?= htmlspecialchars(url('libros/eliminar/' . $libro['id'])) ?>" class="d-inline-block">
                                                <button type="submit" class="btn btn-sm btn-outline-danger action-icon-btn" title="Eliminar libro" onclick="return confirm('Se eliminara este libro. Deseas continuar?')">
                                                    <i class="bi bi-trash3"></i>
                                                    <span class="visually-hidden">Eliminar</span>
                                                </button>
                                            </form>
                                        <?php endif; ?>
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

<!-- Modal de Confirmación de Reserva -->
<div class="modal fade" id="reservaModal" tabindex="-1" aria-labelledby="reservaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservaModalLabel">Confirmar reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form method="post" action="<?= htmlspecialchars(url('reservas/crear')) ?>">
                <div class="modal-body">
                    <input type="hidden" name="libro_id" id="modalLibroId">
                    <div class="text-center mb-4">
                        <i class="bi bi-bookmark-plus text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="fw-bold mb-3">¿Confirmar reserva de:</h6>
                    <p class="h5 text-primary mb-4" id="modalLibroTitulo"></p>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Información importante:</strong>
                        <ul class="mb-0 mt-2">
                            <li>La reserva tendrá una duración de <strong>24 horas</strong> desde el momento de confirmación</li>
                            <li>Si no es aprobada por un bibliotecario dentro de este tiempo, se cancelará automáticamente</li>
                            <li>Puedes cancelar la reserva en cualquier momento desde tu panel de reservas</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Confirmar reserva</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openReservaModal(libroId, libroTitulo) {
    // Poblar los datos del modal
    document.getElementById('modalLibroId').value = libroId;
    document.getElementById('modalLibroTitulo').textContent = libroTitulo;

    // Mostrar el modal usando método nativo (sin depender de bootstrap global)
    const modal = document.getElementById('reservaModal');
    modal.style.display = 'block';
    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-open');

    // Crear backdrop
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    backdrop.id = 'modalBackdrop';
    document.body.appendChild(backdrop);
}

// Función para cerrar el modal
function closeReservaModal() {
    const modal = document.getElementById('reservaModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('modal-open');

    // Remover backdrop
    const backdrop = document.getElementById('modalBackdrop');
    if (backdrop) {
        backdrop.remove();
    }
}

// Agregar event listeners cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const bookSearch = document.getElementById('bookSearch');
    const bookRows = document.querySelectorAll('tr[data-book-search]');
    const bookSearchEmpty = document.getElementById('bookSearchEmpty');

    if (bookSearch) {
        bookSearch.addEventListener('input', function () {
            const term = bookSearch.value.trim().toLowerCase();
            let visibleRows = 0;

            bookRows.forEach(function (row) {
                const matches = row.dataset.bookSearch.includes(term);
                row.classList.toggle('d-none', !matches);

                if (matches) {
                    visibleRows++;
                }
            });

            if (bookSearchEmpty) {
                bookSearchEmpty.classList.toggle('d-none', visibleRows > 0);
            }
        });
    }

    // Cerrar modal al hacer click en el botón de cerrar
    const closeButtons = document.querySelectorAll('#reservaModal .btn-close, #reservaModal .btn-outline-secondary');
    closeButtons.forEach(button => {
        button.addEventListener('click', closeReservaModal);
    });

    // Cerrar modal al hacer click fuera del modal
    document.getElementById('reservaModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeReservaModal();
        }
    });
});
</script>

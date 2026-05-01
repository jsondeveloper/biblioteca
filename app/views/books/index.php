<?php Auth::start(); $isEstudiante = Auth::hasRole('estudiante'); ?>
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
                            <td>
                                <div class="d-inline-flex flex-wrap gap-2">
                                    <a class="btn btn-sm btn-outline-primary" href="<?= htmlspecialchars(url('books/' . $book['id'])) ?>">Ver detalle</a>
                                    <?php if ($isEstudiante && $book['estado'] === 'Disponible'): ?>
                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="openReservaModal(<?= $book['id'] ?>, '<?= htmlspecialchars(addslashes($book['titulo'])) ?>')">Reservar</button>
                                    <?php endif; ?>
                                </div>
                            </td>
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

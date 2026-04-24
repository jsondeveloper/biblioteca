<section class="py-4">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h3 class="card-title">Catalogo</h3>
                    <p class="card-text">Consulta los libros disponibles y el estado general de la biblioteca.</p>
                    <a href="<?= htmlspecialchars(url('libros')) ?>" class="btn btn-primary">Ver libros</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h3 class="card-title">Accesos rapidos</h3>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= htmlspecialchars(url('prestamos/crear')) ?>" class="btn btn-outline-primary">Registrar prestamo</a>
                        <a href="<?= htmlspecialchars(url('prestamos')) ?>" class="btn btn-outline-secondary">Ver historial</a>
                        <a href="<?= htmlspecialchars(url('reservas/crear')) ?>" class="btn btn-outline-success">Crear reserva</a>
                        <a href="<?= htmlspecialchars(url('reservas')) ?>" class="btn btn-outline-dark">Ver reservas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

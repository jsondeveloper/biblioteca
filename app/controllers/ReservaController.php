<?php
declare(strict_types=1);

class ReservaController extends BaseController
{
    public function reservar(): void
    {
        Auth::requireAuth(['estudiante']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libroId = (int) ($_POST['libro_id'] ?? 0);
            $estudianteId = (int) ($this->query(
                'SELECT id FROM estudiantes WHERE usuario_id = ? LIMIT 1',
                [Auth::getUserId()]
            )->fetch()['id'] ?? 0);
            // Fecha de expiración automática: 24 horas después
            $fechaExpiracion = date('Y-m-d H:i:s', strtotime('+1 day'));

            Reserva::reserveBook($libroId, $estudianteId, $fechaExpiracion);
            Libro::reserve($libroId);

            redirect_to('reservas?success=Reserva registrada correctamente. Expira en 24 horas.');
        }

        $libros = self::query('SELECT id, titulo, estado FROM libros WHERE estado = :estado ORDER BY titulo', ['estado' => 'Disponible'])->fetchAll();
        $estudianteActual = self::query(
            'SELECT id, nombre FROM estudiantes WHERE usuario_id = ? LIMIT 1',
            [Auth::getUserId()]
        )->fetch();

        $this->render('reserva/form', [
            'title' => 'Reservar libro',
            'libros' => $libros,
            'estudianteActual' => $estudianteActual,
        ]);
    }

    public function aprobar(string $id): void
    {
        Auth::requireAuth(['bibliotecario']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('reservas');
        }

        $usuarioId = Auth::getUserId() ?? 0;
        $bibliotecarioId = (int) ($this->query(
            'SELECT id FROM bibliotecarios WHERE usuario_id = ? LIMIT 1',
            [$usuarioId]
        )->fetch()['id'] ?? 0);
        $fechaDevolucion = trim($_POST['fecha_devolucion'] ?? '');

        $resultado = Prestamo::lendFromReservation((int) $id, $bibliotecarioId, $fechaDevolucion, $usuarioId);

        if ($resultado['success']) {
            redirect_to('reservas?success=Reserva aprobada y convertida en prestamo.');
        }

        redirect_to('reservas?error=' . urlencode(implode(', ', $resultado['errors'])));
    }

    public function cancelar(string $id): void
    {
        Auth::requireAuth(['estudiante', 'bibliotecario']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reserva = Reserva::find((int) $id);
            if ($reserva !== null) {
                Reserva::cancel((int) $id);
                Libro::release((int) $reserva['libro_id']);
            }
        }

        redirect_to('reservas?success=Reserva cancelada correctamente.');
    }

    public function index(): void
    {
        Auth::requireAuth(['estudiante', 'bibliotecario']);

        // Cancelar reservas expiradas automáticamente
        Reserva::cancelExpiredReservations();

        $isBibliotecario = Auth::hasRole('bibliotecario');
        if ($isBibliotecario) {
            $reservasActivas = self::query(
                'SELECT r.*, l.titulo AS libro, e.nombre AS estudiante
                 FROM reservas r
                 JOIN libros l ON r.libro_id = l.id
                 JOIN estudiantes e ON r.estudiante_id = e.id
                 WHERE r.estado = :estado
                 ORDER BY r.fecha_reserva DESC',
                ['estado' => 'Activa']
            )->fetchAll();
            $historial = Reserva::fullHistory();
        } else {
            $estudianteId = (int) ($this->query(
                'SELECT id FROM estudiantes WHERE usuario_id = ? LIMIT 1',
                [Auth::getUserId()]
            )->fetch()['id'] ?? 0);

            $reservasActivas = self::query(
                'SELECT r.*, l.titulo AS libro, e.nombre AS estudiante
                 FROM reservas r
                 JOIN libros l ON r.libro_id = l.id
                 JOIN estudiantes e ON r.estudiante_id = e.id
                 WHERE r.estudiante_id = :estudiante_id AND r.estado = :estado
                 ORDER BY r.fecha_reserva DESC',
                ['estudiante_id' => $estudianteId, 'estado' => 'Activa']
            )->fetchAll();
            $historial = Reserva::historyByStudent($estudianteId);
        }

        $this->render('reserva/index', [
            'title' => 'Reservas y historial',
            'reservasActivas' => $reservasActivas,
            'historial' => $historial,
            'isBibliotecario' => $isBibliotecario,
        ]);
    }
}

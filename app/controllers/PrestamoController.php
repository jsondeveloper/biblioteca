<?php
declare(strict_types=1);

class PrestamoController extends BaseController
{
    public function registrar(): void
    {
        Auth::requireAuth(['bibliotecario']);

        $mensaje = null;
        $tipo = null;
        $errores = [];
        $selectedLibroId = (int) ($_GET['libro_id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libroId = (int) ($_POST['libro_id'] ?? 0);
            $estudianteId = (int) ($_POST['estudiante_id'] ?? 0);
            $fechaDevolucion = trim($_POST['fecha_devolucion'] ?? '');
            $observaciones = trim($_POST['observaciones'] ?? '');
            $usuarioId = Auth::getUserId() ?? 0;
            $bibliotecarioId = (int) ($this->query(
                'SELECT id FROM bibliotecarios WHERE usuario_id = ? LIMIT 1',
                [$usuarioId]
            )->fetch()['id'] ?? 0);

            $resultado = Prestamo::lendBook($libroId, $estudianteId, $bibliotecarioId, $fechaDevolucion, $usuarioId, $observaciones);

            if ($resultado['success']) {
                $mensaje = 'Prestamo registrado exitosamente (ID: ' . $resultado['id'] . ').';
                $tipo = 'success';
                redirect_to('prestamos?success=1');
            }

            $errores = $resultado['errors'];
            $tipo = 'error';
        }

        $libros = self::query('SELECT id, titulo, estado FROM libros WHERE estado = :estado ORDER BY titulo', ['estado' => 'Disponible'])->fetchAll();
        $estudiantes = self::query('SELECT id, nombre FROM estudiantes ORDER BY nombre')->fetchAll();
        $bibliotecarioActual = self::query(
            'SELECT id, nombre FROM bibliotecarios WHERE usuario_id = ? LIMIT 1',
            [Auth::getUserId()]
        )->fetch();

        $this->render('prestamo/registrar', [
            'title' => 'Registrar prestamo',
            'libros' => $libros,
            'estudiantes' => $estudiantes,
            'bibliotecarioActual' => $bibliotecarioActual,
            'selectedLibroId' => $selectedLibroId,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
            'errores' => $errores,
            'observaciones' => $_POST['observaciones'] ?? '',
        ]);
    }

    public function devolver(string $id): void
    {
        Auth::requireAuth(['bibliotecario']);

        $prestamoId = (int) $id;
        $mensaje = null;
        $tipo = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = Auth::getUserId() ?? 0;
            $comentario = trim($_POST['comentario'] ?? '');
            $sancionData = null;

            if (!empty($_POST['sancion_razon']) || !empty($_POST['sancion_fecha_inicio']) || !empty($_POST['sancion_fecha_fin'])) {
                $sancionData = [
                    'razon' => trim($_POST['sancion_razon'] ?? ''),
                    'fecha_inicio' => trim($_POST['sancion_fecha_inicio'] ?? ''),
                    'fecha_fin' => trim($_POST['sancion_fecha_fin'] ?? ''),
                    'activa' => isset($_POST['sancion_activa']),
                ];
            }

            $resultado = Prestamo::returnBook($prestamoId, $usuarioId, $comentario !== '' ? $comentario : null, $sancionData);

            if ($resultado['success']) {
                redirect_to('prestamos?success=1');
            }

            $mensaje = implode(', ', $resultado['errors']);
            redirect_to('prestamos?error=' . urlencode($mensaje));
        }

        $prestamo = $this->query(
            'SELECT p.*, l.titulo AS libro, e.nombre AS estudiante FROM prestamos p
             JOIN libros l ON p.libro_id = l.id
             JOIN estudiantes e ON p.estudiante_id = e.id
             WHERE p.id = :id AND p.estado = :estado',
            ['id' => $prestamoId, 'estado' => 'Activo']
        )->fetch();

        if (!$prestamo) {
            redirect_to('prestamos?error=' . urlencode('Prestamo no encontrado o no activo.'));
        }

        $this->render('prestamo/devolver', [
            'title' => 'Registrar devolucion',
            'prestamo' => $prestamo,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
        ]);
    }

    public function historial(): void
    {
        Auth::requireAuth(['bibliotecario', 'estudiante']);

        // Cancelar reservas expiradas automáticamente
        Reserva::cancelExpiredReservations();

        $mensaje = null;
        $tipo = null;

        if (isset($_GET['success'])) {
            $mensaje = 'Operacion completada exitosamente.';
            $tipo = 'success';
        } elseif (isset($_GET['error'])) {
            $mensaje = (string) $_GET['error'];
            $tipo = 'error';
        }

        $isBibliotecario = Auth::hasRole('bibliotecario');

        if ($isBibliotecario) {
            $prestamosActivos = Prestamo::activeLoans();
            $historial = Prestamo::fullHistory();
        } else {
            $estudianteId = $this->query(
                'SELECT id FROM estudiantes WHERE usuario_id = ?',
                [Auth::getUserId()]
            )->fetch()['id'] ?? null;

            if ($estudianteId) {
                $prestamosActivos = Prestamo::activeLoansByStudent((int) $estudianteId);
                $historial = Prestamo::historyByStudent((int) $estudianteId);
            } else {
                $prestamosActivos = [];
                $historial = [];
            }
        }

        $this->render('prestamo/historial', [
            'title' => 'Prestamos y historial',
            'prestamosActivos' => $prestamosActivos,
            'historial' => $historial,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
            'isBibliotecario' => $isBibliotecario,
        ]);
    }
}

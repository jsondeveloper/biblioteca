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
            $usuarioId = Auth::getUserId() ?? 0;
            $bibliotecarioId = (int) ($this->query(
                'SELECT id FROM bibliotecarios WHERE usuario_id = ? LIMIT 1',
                [$usuarioId]
            )->fetch()['id'] ?? 0);

            $resultado = Prestamo::lendBook($libroId, $estudianteId, $bibliotecarioId, $fechaDevolucion, $usuarioId);

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
        ]);
    }

    public function devolver(string $id): void
    {
        Auth::requireAuth(['bibliotecario']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = Auth::getUserId() ?? 0;
            $resultado = Prestamo::returnBook((int) $id, $usuarioId);

            if ($resultado['success']) {
                redirect_to('prestamos?success=1');
            }

            $mensaje = implode(', ', $resultado['errors']);
            redirect_to('prestamos?error=' . urlencode($mensaje));
        }

        redirect_to('prestamos');
    }

    public function historial(): void
    {
        Auth::requireAuth(['bibliotecario', 'estudiante']);

        $mensaje = null;
        $tipo = null;

        if (isset($_GET['success'])) {
            $mensaje = 'Operacion completada exitosamente.';
            $tipo = 'success';
        } elseif (isset($_GET['error'])) {
            $mensaje = (string) $_GET['error'];
            $tipo = 'error';
        }

        if (Auth::hasRole('bibliotecario')) {
            $historial = Prestamo::fullHistory();
        } else {
            $estudianteId = $this->query(
                'SELECT id FROM estudiantes WHERE usuario_id = ?',
                [Auth::getUserId()]
            )->fetch()['id'] ?? null;

            if ($estudianteId) {
                $historial = Prestamo::historyByStudent((int) $estudianteId);
            } else {
                $historial = [];
            }
        }

        $this->render('prestamo/historial', [
            'title' => 'Historial de prestamos',
            'historial' => $historial,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
        ]);
    }
}

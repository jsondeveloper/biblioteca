<?php
declare(strict_types=1);

class SancionController extends BaseController
{
    public function crear(): void
    {
        Auth::requireAuth(['bibliotecario']);

        $mensaje = null;
        $tipo = null;
        $errores = [];
        $selectedEstudianteId = (int) ($_GET['estudiante_id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estudianteId = (int) ($_POST['estudiante_id'] ?? 0);
            $razon = trim($_POST['razon'] ?? '');
            $fechaInicio = trim($_POST['fecha_inicio'] ?? '');
            $fechaFin = trim($_POST['fecha_fin'] ?? '');
            $activa = isset($_POST['activa']);

            if ($estudianteId <= 0) {
                $errores[] = 'Selecciona un estudiante para sancionar.';
            }

            if ($razon === '') {
                $errores[] = 'La razón de la sanción es obligatoria.';
            }

            if (!self::isValidDate($fechaInicio)) {
                $errores[] = 'La fecha de inicio no es válida.';
            }

            if (!self::isValidDate($fechaFin)) {
                $errores[] = 'La fecha de fin no es válida.';
            }

            if (empty($errores) && strtotime($fechaInicio) > strtotime($fechaFin)) {
                $errores[] = 'La fecha de fin debe ser igual o posterior a la fecha de inicio.';
            }

            if (empty($errores)) {
                Sancion::create([
                    'estudiante_id' => $estudianteId,
                    'razon' => $razon,
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'activa' => $activa,
                ]);

                $mensaje = 'Sanción registrada correctamente.';
                $tipo = 'success';
                $selectedEstudianteId = $estudianteId;
            } else {
                $tipo = 'error';
            }
        }

        $estudiantes = self::query('SELECT id, nombre FROM estudiantes ORDER BY nombre')->fetchAll();

        $this->render('sancion/crear', [
            'title' => 'Crear sanción',
            'estudiantes' => $estudiantes,
            'selectedEstudianteId' => $selectedEstudianteId,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
            'errores' => $errores,
        ]);
    }

    public function index(): void
    {
        Auth::requireAuth(['bibliotecario']);

        Sancion::deactivateExpired();

        $sanciones = self::query(
            'SELECT s.*, e.nombre AS estudiante
             FROM sanciones s
             JOIN estudiantes e ON s.estudiante_id = e.id
             ORDER BY s.activa DESC, s.fecha_inicio DESC'
        )->fetchAll();
        $estudiantes = self::query('SELECT id, nombre FROM estudiantes ORDER BY nombre')->fetchAll();

        $this->render('sancion/index', [
            'title' => 'Listado de sanciones',
            'sanciones' => $sanciones,
            'estudiantes' => $estudiantes,
        ]);
    }

    public function actualizar(string $id): void
    {
        Auth::requireAuth(['bibliotecario']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('sanciones');
        }

        $sancionId = (int) $id;
        $estudianteId = (int) ($_POST['estudiante_id'] ?? 0);
        $razon = trim($_POST['razon'] ?? '');
        $fechaInicio = trim($_POST['fecha_inicio'] ?? '');
        $fechaFin = trim($_POST['fecha_fin'] ?? '');
        $activa = isset($_POST['activa']);
        $errores = [];

        if (Sancion::find($sancionId) === null) {
            redirect_to('sanciones?error=' . urlencode('La sanción no existe.'));
        }

        if ($estudianteId <= 0) {
            $errores[] = 'Selecciona un estudiante para sancionar.';
        }

        if ($razon === '') {
            $errores[] = 'La razÃ³n de la sanción es obligatoria.';
        }

        if (!self::isValidDate($fechaInicio)) {
            $errores[] = 'La fecha de inicio no es válida.';
        }

        if (!self::isValidDate($fechaFin)) {
            $errores[] = 'La fecha de fin no es válida.';
        }

        if (empty($errores) && strtotime($fechaInicio) > strtotime($fechaFin)) {
            $errores[] = 'La fecha de fin debe ser igual o posterior a la fecha de inicio.';
        }

        if (!empty($errores)) {
            redirect_to('sanciones?error=' . urlencode(implode(', ', $errores)));
        }

        Sancion::update($sancionId, [
            'estudiante_id' => $estudianteId,
            'razon' => $razon,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'activa' => $activa,
        ]);

        redirect_to('sanciones?success=La sanción se actualizó correctamente.');
    }

    public function eliminar(string $id): void
    {
        Auth::requireAuth(['bibliotecario']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('sanciones');
        }

        $sancionId = (int) $id;
        if (Sancion::find($sancionId) === null) {
            redirect_to('sanciones?error=' . urlencode('La sanción no existe.'));
        }

        Sancion::delete($sancionId);
        redirect_to('sanciones?success=La sanción se eliminó correctamente.');
    }

    public function activar(string $id): void
    {
        Auth::requireAuth(['bibliotecario']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('sanciones');
        }

        Sancion::setActive((int) $id, true);
        redirect_to('sanciones?success=La sanción se activó correctamente.');
    }

    public function desactivar(string $id): void
    {
        Auth::requireAuth(['bibliotecario']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('sanciones');
        }

        Sancion::setActive((int) $id, false);
        redirect_to('sanciones?success=La sanción se desactivó correctamente.');
    }

    private static function isValidDate(string $date): bool
    {
        if ($date === '') {
            return false;
        }

        $pattern = '/^\d{4}-\d{2}-\d{2}$/';
        if (!preg_match($pattern, $date)) {
            return false;
        }

        $parts = explode('-', $date);
        return checkdate((int) $parts[1], (int) $parts[2], (int) $parts[0]);
    }
}

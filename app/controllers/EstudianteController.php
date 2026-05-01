<?php
declare(strict_types=1);

class EstudianteController extends BaseController
{
    public function index(): void
    {
        Auth::requireAuth(['bibliotecario']);
        Sancion::deactivateExpired();

        $estudiantes = self::query(
            'SELECT e.id, e.nombre, e.telefono, u.email, u.username
             FROM estudiantes e
             JOIN usuarios u ON e.usuario_id = u.id
             ORDER BY e.nombre'
        )->fetchAll();

        $reservas = self::query(
            'SELECT r.estudiante_id, r.fecha_reserva, r.fecha_expiracion, r.created_at, l.titulo AS libro
             FROM reservas r
             JOIN libros l ON r.libro_id = l.id
             WHERE r.estado = :estado
             ORDER BY r.fecha_expiracion ASC',
            ['estado' => 'Activa']
        )->fetchAll();

        $prestamos = self::query(
            'SELECT p.estudiante_id, p.fecha_prestamo, p.fecha_devolucion, p.estado, l.titulo AS libro
             FROM prestamos p
             JOIN libros l ON p.libro_id = l.id
             WHERE p.estado IN (:activo, :retrasado)
             ORDER BY p.fecha_devolucion ASC',
            ['activo' => 'Activo', 'retrasado' => 'Retrasado']
        )->fetchAll();

        $sanciones = self::query(
            'SELECT estudiante_id, razon, fecha_inicio, fecha_fin
             FROM sanciones
             WHERE activa = true
             ORDER BY fecha_fin ASC'
        )->fetchAll();

        $actividad = [];
        foreach ($estudiantes as $estudiante) {
            $actividad[(int) $estudiante['id']] = [
                'reservas' => [],
                'prestamos' => [],
                'sanciones' => [],
            ];
        }

        foreach ($reservas as $reserva) {
            $actividad[(int) $reserva['estudiante_id']]['reservas'][] = $reserva;
        }

        foreach ($prestamos as $prestamo) {
            $actividad[(int) $prestamo['estudiante_id']]['prestamos'][] = $prestamo;
        }

        foreach ($sanciones as $sancion) {
            $actividad[(int) $sancion['estudiante_id']]['sanciones'][] = $sancion;
        }

        $this->render('estudiante/index', [
            'title' => 'Listado de estudiantes',
            'estudiantes' => $estudiantes,
            'actividad' => $actividad,
        ]);
    }
}

<?php
declare(strict_types=1);

class HomeController extends BaseController
{
    public function index(): void
    {
        if (Auth::isAuthenticated()) {
            $role = Auth::getRole();
            $userId = Auth::getUserId();
            
            $data = [
                'title' => 'Panel de inicio',
                'user' => Auth::getUser(),
                'role' => $role,
            ];

            if ($role === 'bibliotecario') {
                Prestamo::markOverdueLoans($userId);
                Reserva::cancelExpiredReservations();
                Sancion::deactivateExpired();

                $data['libros_count'] = count(Libro::all());
                $prestamosResult = $this->query(
                    'SELECT COUNT(*) as count FROM prestamos WHERE estado IN ("Activo", "Retrasado") AND fecha_entrega IS NULL'
                );
                $data['prestamos_activos'] = $prestamosResult->fetch()['count'];
                $data['estudiantes_count'] = count(Estudiante::all());
                $reservasResult = $this->query(
                    'SELECT COUNT(*) as count FROM reservas WHERE estado = ?',
                    ['Activa']
                );
                $data['reservas_activas'] = $reservasResult->fetch()['count'];
                $sancionesResult = $this->query(
                    'SELECT COUNT(*) as count FROM sanciones WHERE activa = true'
                );
                $data['sanciones_activas'] = $sancionesResult->fetch()['count'];
                $data['categorias_count'] = count(Categoria::all());
                $this->render('home/bibliotecario', $data);
            } else {
                Sancion::deactivateExpired();
                Prestamo::markOverdueLoans($userId);

                $data['mis_prestamos'] = $this->query(
                    'SELECT p.*, l.titulo, b.nombre as bibliotecario FROM prestamos p 
                     JOIN libros l ON p.libro_id = l.id 
                     JOIN bibliotecarios b ON p.bibliotecario_id = b.id 
                     WHERE p.estudiante_id = (SELECT id FROM estudiantes WHERE usuario_id = ?) AND p.estado IN ("Activo", "Retrasado") AND p.fecha_entrega IS NULL',
                    [$userId]
                )->fetchAll();
                
                $data['mis_reservas'] = $this->query(
                    'SELECT r.*, l.titulo FROM reservas r 
                     JOIN libros l ON r.libro_id = l.id 
                     WHERE r.estudiante_id = (SELECT id FROM estudiantes WHERE usuario_id = ?) AND r.estado = ?',
                    [$userId, 'Activa']
                )->fetchAll();

                $data['mis_sanciones'] = $this->query(
                    'SELECT * FROM sanciones 
                     WHERE estudiante_id = (SELECT id FROM estudiantes WHERE usuario_id = ?) AND activa = true
                     ORDER BY fecha_fin DESC',
                    [$userId]
                )->fetchAll();
                
                $this->render('home/estudiante', $data);
            }
            return;
        }

        $this->render('home/index', [
            'title' => 'Biblioteca Universitaria',
        ]);
    }
}

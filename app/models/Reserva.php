<?php
declare(strict_types=1);

class Reserva extends Model
{
    protected static function table(): string
    {
        return 'reservas';
    }

    protected static function fillable(): array
    {
        return ['libro_id', 'estudiante_id', 'fecha_reserva', 'fecha_expiracion', 'estado'];
    }

    public static function reserveBook(int $libroId, int $estudianteId, string $fechaExpiracion): int
    {
        $data = [
            'libro_id' => $libroId,
            'estudiante_id' => $estudianteId,
            'fecha_reserva' => date('Y-m-d H:i:s'),
            'fecha_expiracion' => $fechaExpiracion,
            'estado' => 'Activa',
        ];

        return self::create($data);
    }

    public static function cancel(int $id): bool
    {
        return self::update($id, ['estado' => 'Cancelada']);
    }

    public static function markAsFulfilled(int $id): bool
    {
        return self::update($id, ['estado' => 'Cumplida']);
    }

    public static function activeByBook(int $libroId): array
    {
        return self::query('SELECT * FROM reservas WHERE libro_id = :libro_id AND estado = :estado ORDER BY fecha_reserva DESC', [
            'libro_id' => $libroId,
            'estado' => 'Activa',
        ])->fetchAll();
    }

    public static function fullHistory(): array
    {
        $sql = 'SELECT r.*, l.titulo AS libro, e.nombre AS estudiante
                FROM reservas r
                JOIN libros l ON r.libro_id = l.id
                JOIN estudiantes e ON r.estudiante_id = e.id
                ORDER BY r.fecha_reserva DESC, r.id DESC';

        return self::query($sql)->fetchAll();
    }

    public static function historyByStudent(int $estudianteId): array
    {
        $sql = 'SELECT r.*, l.titulo AS libro, e.nombre AS estudiante
                FROM reservas r
                JOIN libros l ON r.libro_id = l.id
                JOIN estudiantes e ON r.estudiante_id = e.id
                WHERE r.estudiante_id = :estudiante_id
                ORDER BY r.fecha_reserva DESC, r.id DESC';

        return self::query($sql, ['estudiante_id' => $estudianteId])->fetchAll();
    }

    public static function cancelExpiredReservations(): int
    {
        $now = date('Y-m-d H:i:s');
        $expiredReservations = self::query(
            'SELECT id, libro_id FROM reservas WHERE estado = :estado AND DATE_ADD(created_at, INTERVAL 1 DAY) < :now',
            ['estado' => 'Activa', 'now' => $now]
        )->fetchAll();

        $cancelledCount = 0;
        foreach ($expiredReservations as $reserva) {
            self::cancel($reserva['id']);
            Libro::release($reserva['libro_id']);
            $cancelledCount++;
        }

        return $cancelledCount;
    }
}

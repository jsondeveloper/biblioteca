<?php
declare(strict_types=1);

class Prestamo extends Model
{
    protected static function table(): string
    {
        return 'prestamos';
    }

    protected static function fillable(): array
    {
        return ['libro_id', 'estudiante_id', 'bibliotecario_id', 'fecha_prestamo', 'fecha_devolucion', 'fecha_entrega', 'estado', 'observaciones'];
    }

    public static function lendBook(int $libroId, int $estudianteId, int $bibliotecarioId, string $fechaDevolucion, int $cambiadoPorUsuarioId): array
    {
        $errors = [];

        if ($libroId <= 0) {
            $errors[] = 'El ID del libro es inválido.';
        }

        if ($estudianteId <= 0) {
            $errors[] = 'El ID del estudiante es inválido.';
        }

        if ($bibliotecarioId <= 0) {
            $errors[] = 'El ID del bibliotecario es inválido.';
        }

        if (!self::isValidDate($fechaDevolucion)) {
            $errors[] = 'La fecha de devolución no es válida.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors, 'id' => null];
        }

        $book = Libro::find($libroId);
        if ($book === null) {
            return ['success' => false, 'errors' => ['El libro no existe.'], 'id' => null];
        }

        if ($book['estado'] !== 'Disponible') {
            return ['success' => false, 'errors' => ['El libro no está disponible. Estado actual: ' . htmlspecialchars($book['estado'])], 'id' => null];
        }

        $estudiante = Estudiante::find($estudianteId);
        if ($estudiante === null) {
            return ['success' => false, 'errors' => ['El estudiante no existe.'], 'id' => null];
        }

        if (Sancion::hasActiveSanction($estudianteId)) {
            $sanciones = Sancion::getActivasByStudent($estudianteId);
            $motivos = array_map(static fn ($s) => $s['razon'], $sanciones);
            return ['success' => false, 'errors' => ['El estudiante tiene sanciones activas: ' . implode(', ', $motivos)], 'id' => null];
        }

        $bibliotecario = Bibliotecario::find($bibliotecarioId);
        if ($bibliotecario === null) {
            return ['success' => false, 'errors' => ['El bibliotecario no existe.'], 'id' => null];
        }

        $connection = self::db();
        $connection->beginTransaction();

        try {
            $prestamoId = self::create([
                'libro_id' => $libroId,
                'estudiante_id' => $estudianteId,
                'bibliotecario_id' => $bibliotecarioId,
                'fecha_prestamo' => date('Y-m-d'),
                'fecha_devolucion' => $fechaDevolucion,
                'fecha_entrega' => null,
                'estado' => 'Activo',
                'observaciones' => null,
            ]);

            Libro::lend($libroId);
            self::recordHistory($prestamoId, 'Activo', 'Activo', $cambiadoPorUsuarioId, 'Préstamo registrado exitosamente.');

            $connection->commit();
            return ['success' => true, 'errors' => [], 'id' => $prestamoId];
        } catch (Throwable $exception) {
            $connection->rollBack();
            return ['success' => false, 'errors' => ['Error al registrar el préstamo: ' . $exception->getMessage()], 'id' => null];
        }
    }

    private static function isValidDate(string $date): bool
    {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';
        if (!preg_match($pattern, $date)) {
            return false;
        }

        $parts = explode('-', $date);
        return checkdate((int) $parts[1], (int) $parts[2], (int) $parts[0]);
    }

    public static function returnBook(int $prestamoId, int $cambiadoPorUsuarioId): array
    {
        if ($prestamoId <= 0) {
            return ['success' => false, 'errors' => ['El ID del préstamo es inválido.']];
        }

        $prestamo = self::find($prestamoId);
        if ($prestamo === null) {
            return ['success' => false, 'errors' => ['El préstamo no existe.']];
        }

        if ($prestamo['estado'] !== 'Activo') {
            return ['success' => false, 'errors' => ['El préstamo no está activo. Estado actual: ' . htmlspecialchars($prestamo['estado'])]];
        }

        $connection = self::db();
        $connection->beginTransaction();

        try {
            $fechaEntrega = date('Y-m-d');
            $estado = strtotime($fechaEntrega) > strtotime($prestamo['fecha_devolucion']) ? 'Retrasado' : 'Devuelto';

            $actualizado = self::update($prestamoId, [
                'estado' => $estado,
                'fecha_entrega' => $fechaEntrega,
            ]);

            if (!$actualizado) {
                throw new RuntimeException('No se pudo actualizar el préstamo.');
            }

            Libro::release((int) $prestamo['libro_id']);
            $mensajeHistorial = $estado === 'Retrasado' ? 'Devolución procesada con atraso.' : 'Devolución procesada a tiempo.';
            self::recordHistory($prestamoId, 'Activo', $estado, $cambiadoPorUsuarioId, $mensajeHistorial);

            $connection->commit();
            $mensaje = $estado === 'Retrasado' ? 'Devolución registrada (RETRASADA).' : 'Devolución registrada exitosamente.';
            return ['success' => true, 'errors' => [], 'message' => $mensaje];
        } catch (Throwable $exception) {
            $connection->rollBack();
            return ['success' => false, 'errors' => ['Error al procesar la devolución: ' . $exception->getMessage()]];
        }
    }

    public static function activeLoans(): array
    {
        $sql = 'SELECT p.*, l.titulo AS libro, e.nombre AS estudiante, b.nombre AS bibliotecario
                FROM prestamos p
                JOIN libros l ON p.libro_id = l.id
                JOIN estudiantes e ON p.estudiante_id = e.id
                JOIN bibliotecarios b ON p.bibliotecario_id = b.id
                WHERE p.estado = :estado
                ORDER BY p.fecha_prestamo DESC';

        return self::query($sql, ['estado' => 'Activo'])->fetchAll();
    }

    public static function activeLoansByStudent(int $estudianteId): array
    {
        $sql = 'SELECT p.*, l.titulo AS libro, e.nombre AS estudiante, b.nombre AS bibliotecario
                FROM prestamos p
                JOIN libros l ON p.libro_id = l.id
                JOIN estudiantes e ON p.estudiante_id = e.id
                JOIN bibliotecarios b ON p.bibliotecario_id = b.id
                WHERE p.estado = :estado AND p.estudiante_id = :estudiante_id
                ORDER BY p.fecha_prestamo DESC';

        return self::query($sql, ['estado' => 'Activo', 'estudiante_id' => $estudianteId])->fetchAll();
    }

    public static function fullHistory(): array
    {
        $sql = 'SELECT p.*, l.titulo AS libro, e.nombre AS estudiante, b.nombre AS bibliotecario
                FROM prestamos p
                JOIN libros l ON p.libro_id = l.id
                JOIN estudiantes e ON p.estudiante_id = e.id
                JOIN bibliotecarios b ON p.bibliotecario_id = b.id
                ORDER BY p.fecha_prestamo DESC, p.id DESC';

        return self::query($sql)->fetchAll();
    }

    public static function historyByStudent(int $estudianteId): array
    {
        $sql = 'SELECT p.*, l.titulo AS libro, e.nombre AS estudiante, b.nombre AS bibliotecario
                FROM prestamos p
                JOIN libros l ON p.libro_id = l.id
                JOIN estudiantes e ON p.estudiante_id = e.id
                JOIN bibliotecarios b ON p.bibliotecario_id = b.id
                WHERE p.estudiante_id = :estudiante_id
                ORDER BY p.fecha_prestamo DESC, p.id DESC';

        return self::query($sql, ['estudiante_id' => $estudianteId])->fetchAll();
    }

    public static function lendFromReservation(int $reservaId, int $bibliotecarioId, string $fechaDevolucion, int $cambiadoPorUsuarioId): array
    {
        if ($reservaId <= 0) {
            return ['success' => false, 'errors' => ['La reserva es invalida.'], 'id' => null];
        }

        if ($bibliotecarioId <= 0) {
            return ['success' => false, 'errors' => ['El bibliotecario es invalido.'], 'id' => null];
        }

        if (!self::isValidDate($fechaDevolucion)) {
            return ['success' => false, 'errors' => ['La fecha de devolucion no es valida.'], 'id' => null];
        }

        $reserva = self::query(
            'SELECT r.*, l.titulo AS libro_titulo
             FROM reservas r
             JOIN libros l ON r.libro_id = l.id
             WHERE r.id = :id
             LIMIT 1',
            ['id' => $reservaId]
        )->fetch();

        if (!$reserva) {
            return ['success' => false, 'errors' => ['La reserva no existe.'], 'id' => null];
        }

        if ($reserva['estado'] !== 'Activa') {
            return ['success' => false, 'errors' => ['La reserva ya no esta activa.'], 'id' => null];
        }

        $book = Libro::find((int) $reserva['libro_id']);
        if ($book === null) {
            return ['success' => false, 'errors' => ['El libro asociado no existe.'], 'id' => null];
        }

        if (!in_array($book['estado'], ['Reservado', 'Disponible'], true)) {
            return ['success' => false, 'errors' => ['El libro no puede prestarse. Estado actual: ' . htmlspecialchars($book['estado'])], 'id' => null];
        }

        $estudiante = Estudiante::find((int) $reserva['estudiante_id']);
        if ($estudiante === null) {
            return ['success' => false, 'errors' => ['El estudiante asociado no existe.'], 'id' => null];
        }

        if (Sancion::hasActiveSanction((int) $reserva['estudiante_id'])) {
            $sanciones = Sancion::getActivasByStudent((int) $reserva['estudiante_id']);
            $motivos = array_map(static fn ($s) => $s['razon'], $sanciones);
            return ['success' => false, 'errors' => ['El estudiante tiene sanciones activas: ' . implode(', ', $motivos)], 'id' => null];
        }

        $bibliotecario = Bibliotecario::find($bibliotecarioId);
        if ($bibliotecario === null) {
            return ['success' => false, 'errors' => ['El bibliotecario no existe.'], 'id' => null];
        }

        $prestamoActivo = self::query(
            'SELECT id FROM prestamos WHERE libro_id = :libro_id AND estado = :estado LIMIT 1',
            ['libro_id' => $reserva['libro_id'], 'estado' => 'Activo']
        )->fetch();

        if ($prestamoActivo) {
            return ['success' => false, 'errors' => ['El libro ya tiene un prestamo activo.'], 'id' => null];
        }

        $connection = self::db();
        $connection->beginTransaction();

        try {
            $prestamoId = self::create([
                'libro_id' => (int) $reserva['libro_id'],
                'estudiante_id' => (int) $reserva['estudiante_id'],
                'bibliotecario_id' => $bibliotecarioId,
                'fecha_prestamo' => date('Y-m-d'),
                'fecha_devolucion' => $fechaDevolucion,
                'fecha_entrega' => null,
                'estado' => 'Activo',
                'observaciones' => 'Prestamo generado desde reserva aprobada.',
            ]);

            Libro::lend((int) $reserva['libro_id']);
            Reserva::markAsFulfilled($reservaId);
            self::recordHistory($prestamoId, 'Activo', 'Activo', $cambiadoPorUsuarioId, 'Prestamo creado a partir de reserva aprobada.');

            $connection->commit();
            return ['success' => true, 'errors' => [], 'id' => $prestamoId];
        } catch (Throwable $exception) {
            $connection->rollBack();
            return ['success' => false, 'errors' => ['Error al aprobar la reserva: ' . $exception->getMessage()], 'id' => null];
        }
    }

    public static function historyByBook(int $libroId): array
    {
        $sql = 'SELECT p.*, e.nombre AS estudiante, b.nombre AS bibliotecario
                FROM prestamos p
                JOIN estudiantes e ON p.estudiante_id = e.id
                JOIN bibliotecarios b ON p.bibliotecario_id = b.id
                WHERE p.libro_id = :libro_id
                ORDER BY p.fecha_prestamo DESC';

        return self::query($sql, ['libro_id' => $libroId])->fetchAll();
    }

    protected static function recordHistory(int $prestamoId, string $estadoAnterior, string $estadoNuevo, int $cambiadoPor, ?string $comentario = null): void
    {
        $sql = 'INSERT INTO prestamos_historial (prestamo_id, estado_anterior, estado_nuevo, cambiado_por, comentario) VALUES (:prestamo_id, :estado_anterior, :estado_nuevo, :cambiado_por, :comentario)';
        self::query($sql, [
            'prestamo_id' => $prestamoId,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
            'cambiado_por' => $cambiadoPor,
            'comentario' => $comentario,
        ]);
    }
}

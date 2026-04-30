<?php
declare(strict_types=1);

class Sancion extends Model
{
    protected static function table(): string
    {
        return 'sanciones';
    }

    protected static function fillable(): array
    {
        return ['estudiante_id', 'razon', 'fecha_inicio', 'fecha_fin', 'activa'];
    }

    public static function getActivasByStudent(int $estudianteId): array
    {
        self::deactivateExpired();

        $sql = 'SELECT * FROM sanciones WHERE estudiante_id = :estudiante_id AND activa = true ORDER BY fecha_fin DESC';
        
        return self::query($sql, ['estudiante_id' => $estudianteId])->fetchAll();
    }

    public static function hasActiveSanction(int $estudianteId): bool
    {
        self::deactivateExpired();

        $sanciones = self::getActivasByStudent($estudianteId);
        return !empty($sanciones);
    }

    public static function deactivateExpired(): int
    {
        $sql = 'UPDATE sanciones SET activa = false WHERE activa = true AND fecha_fin < CURDATE()';
        $stmt = self::query($sql);
        return $stmt->rowCount();
    }

    public static function setActive(int $id, bool $activa): bool
    {
        return self::update($id, ['activa' => $activa]);
    }
}

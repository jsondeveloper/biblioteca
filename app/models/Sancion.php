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
        $sql = 'SELECT * FROM sanciones WHERE estudiante_id = :estudiante_id AND activa = true AND fecha_fin >= CURDATE() ORDER BY fecha_fin DESC';
        
        return self::query($sql, ['estudiante_id' => $estudianteId])->fetchAll();
    }

    public static function hasActiveSanction(int $estudianteId): bool
    {
        $sanciones = self::getActivasByStudent($estudianteId);
        return !empty($sanciones);
    }
}

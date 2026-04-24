<?php
declare(strict_types=1);

abstract class BaseModel
{
    protected static function db(): PDO
    {
        return Database::connect();
    }

    abstract protected static function table(): string;

    protected static function primaryKey(): string
    {
        return 'id';
    }

    protected static function fillable(): array
    {
        return [];
    }

    public static function all(): array
    {
        $sql = sprintf('SELECT * FROM %s ORDER BY %s DESC', static::table(), static::primaryKey());
        return self::query($sql)->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :id LIMIT 1', static::table(), static::primaryKey());
        $stmt = self::query($sql, ['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $data = self::filterData($data);
        if (empty($data)) {
            throw new InvalidArgumentException('No hay datos válidos para crear.');
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(static fn (string $field) => ':' . $field, array_keys($data)));
        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', static::table(), $columns, $placeholders);

        self::query($sql, $data);

        return (int) self::db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $data = self::filterData($data);
        if (empty($data)) {
            return false;
        }

        $assignments = implode(', ', array_map(static fn (string $field) => sprintf('%s = :%s', $field, $field), array_keys($data)));
        $data['id'] = $id;
        $sql = sprintf('UPDATE %s SET %s WHERE %s = :id', static::table(), $assignments, static::primaryKey());

        $stmt = self::query($sql, $data);
        return $stmt->rowCount() > 0;
    }

    public static function delete(int $id): bool
    {
        $sql = sprintf('DELETE FROM %s WHERE %s = :id', static::table(), static::primaryKey());

        $stmt = self::query($sql, ['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    protected static function filterData(array $data): array
    {
        $allowed = static::fillable();
        if (empty($allowed)) {
            return $data;
        }

        return array_intersect_key($data, array_flip($allowed));
    }

    protected static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }
}

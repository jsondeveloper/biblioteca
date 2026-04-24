<?php
declare(strict_types=1);

class Categoria extends Model
{
    protected static function table(): string
    {
        return 'categorias';
    }

    protected static function fillable(): array
    {
        return ['nombre', 'descripcion'];
    }
}

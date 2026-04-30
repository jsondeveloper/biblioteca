<?php
declare(strict_types=1);

class LibroController extends BaseController
{
    public function listar(): void
    {
        $libros = Libro::all();
        $categorias = self::getCategorias();

        $this->render('libro/listar', [
            'title' => 'Listado de libros',
            'libros' => $libros,
            'categorias' => $categorias,
        ]);
    }

    public function buscar(): void
    {
        $term = trim($_GET['q'] ?? '');
        $libros = $term !== '' ? Libro::search($term) : Libro::all();
        $categorias = self::getCategorias();

        $this->render('libro/listar', [
            'title' => 'Resultados de búsqueda',
            'libros' => $libros,
            'term' => $term,
            'categorias' => $categorias,
        ]);
    }

    public function historial(string $id): void
    {
        $libro = Libro::find((int) $id);
        if ($libro === null) {
            header('HTTP/1.0 404 Not Found');
            echo '<h1>Libro no encontrado</h1>';
            return;
        }

        $prestamos = Prestamo::historyByBook((int) $id);

        $this->render('libro/historial', [
            'title' => 'Hoja de vida del libro',
            'libro' => $libro,
            'prestamos' => $prestamos,
        ]);
    }

    public function crear(): void
    {
        Auth::requireAuth(['bibliotecario']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Libro::create([
                'titulo' => trim($_POST['titulo'] ?? ''),
                'isbn' => trim($_POST['isbn'] ?? ''),
                'autor' => trim($_POST['autor'] ?? ''),
                'categoria_id' => (int) ($_POST['categoria_id'] ?? 0),
                'estado' => trim($_POST['estado'] ?? 'Disponible'),
                'anio_publicacion' => trim($_POST['anio_publicacion'] ?? null),
                'descripcion' => trim($_POST['descripcion'] ?? null),
            ]);

            redirect_to('libros?success=Libro registrado correctamente.');
        }

        $this->render('libro/form', [
            'title' => 'Crear libro',
            'categorias' => self::getCategorias(),
        ]);
    }

    public function actualizar(string $id): void
    {
        Auth::requireAuth(['bibliotecario']);

        $libro = Libro::find((int) $id);
        if ($libro === null) {
            redirect_to('libros?success=Libro actualizado correctamente.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Libro::update((int) $id, [
                'titulo' => trim($_POST['titulo'] ?? ''),
                'isbn' => trim($_POST['isbn'] ?? ''),
                'autor' => trim($_POST['autor'] ?? ''),
                'categoria_id' => (int) ($_POST['categoria_id'] ?? 0),
                'estado' => trim($_POST['estado'] ?? 'Disponible'),
                'anio_publicacion' => trim($_POST['anio_publicacion'] ?? null),
                'descripcion' => trim($_POST['descripcion'] ?? null),
            ]);

            redirect_to('libros');
        }

        $this->render('libro/form', [
            'title' => 'Editar libro',
            'libro' => $libro,
            'categorias' => self::getCategorias(),
        ]);
    }

    public function eliminar(string $id): void
    {
        Auth::requireAuth(['bibliotecario']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Libro::delete((int) $id);
        }

        redirect_to('libros?success=Libro eliminado correctamente.');
    }

    private static function getCategorias(): array
    {
        return self::query('SELECT id, nombre FROM categorias ORDER BY nombre')->fetchAll();
    }
}

<?php
declare(strict_types=1);

class CategoriaController extends BaseController
{
    public function index(): void
    {
        Auth::requireAuth(['bibliotecario']);

        $categorias = Categoria::all();

        $this->render('categoria/index', [
            'title' => 'Categorias',
            'categorias' => $categorias,
        ]);
    }

    public function crear(): void
    {
        Auth::requireAuth(['bibliotecario']);

        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');

            if ($nombre === '') {
                $errores[] = 'El nombre de la categoria es obligatorio.';
            }

            if (empty($errores)) {
                try {
                    Categoria::create([
                        'nombre' => $nombre,
                        'descripcion' => $descripcion !== '' ? $descripcion : null,
                    ]);

                    redirect_to('categorias?success=Categoria creada correctamente.');
                } catch (Throwable $exception) {
                    $errores[] = 'No se pudo crear la categoria. Verifica que el nombre no este repetido.';
                }
            }
        }

        $this->render('categoria/form', [
            'title' => 'Nueva categoria',
            'errores' => $errores,
            'categoria' => [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
            ],
        ]);
    }

    public function actualizar(string $id): void
    {
        Auth::requireAuth(['bibliotecario']);

        $categoria = Categoria::find((int) $id);
        if ($categoria === null) {
            redirect_to('categorias?error=La categoria no existe.');
        }

        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');

            if ($nombre === '') {
                $errores[] = 'El nombre de la categoria es obligatorio.';
            }

            if (empty($errores)) {
                try {
                    Categoria::update((int) $id, [
                        'nombre' => $nombre,
                        'descripcion' => $descripcion !== '' ? $descripcion : null,
                    ]);

                    redirect_to('categorias?success=Categoria actualizada correctamente.');
                } catch (Throwable $exception) {
                    $errores[] = 'No se pudo actualizar la categoria. Verifica que el nombre no este repetido.';
                }
            }

            $categoria = [
                'id' => (int) $id,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
            ];
        }

        $this->render('categoria/form', [
            'title' => 'Editar categoria',
            'errores' => $errores,
            'categoria' => $categoria,
        ]);
    }

    public function eliminar(string $id): void
    {
        Auth::requireAuth(['bibliotecario']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('categorias');
        }

        try {
            Categoria::delete((int) $id);
            redirect_to('categorias?success=Categoria eliminada correctamente.');
        } catch (Throwable $exception) {
            redirect_to('categorias?error=' . urlencode('No se puede eliminar la categoria porque esta asociada a uno o mas libros.'));
        }
    }
}

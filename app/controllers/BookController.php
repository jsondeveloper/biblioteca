<?php
declare(strict_types=1);

class BookController extends BaseController
{
    public function index(): void
    {
        $books = self::query(
            'SELECT l.*, c.nombre AS categoria
             FROM libros l
             JOIN categorias c ON l.categoria_id = c.id
             ORDER BY l.titulo'
        )->fetchAll();

        $this->render('books/index', [
            'title' => 'Libros disponibles',
            'books' => $books,
        ]);
    }

    public function show(string $id): void
    {
        $book = Book::find((int) $id);

        if ($book === null) {
            header('HTTP/1.0 404 Not Found');
            echo '<h1>Libro no encontrado</h1>';
            return;
        }

        $this->render('books/show', [
            'title' => 'Detalle del libro',
            'book' => $book,
        ]);
    }
}

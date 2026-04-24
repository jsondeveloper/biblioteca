<?php
declare(strict_types=1);

class AuthorController extends BaseController
{
    public function index(): void
    {
        $authors = Author::all();
        $this->render('authors/index', [
            'title' => 'Autores registrados',
            'authors' => $authors,
        ]);
    }
}

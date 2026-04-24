<?php
declare(strict_types=1);

class PanelController extends BaseController
{
    public function index(): void
    {
        $this->render('home/panel', [
            'title' => 'Panel de Bibliotecario',
        ]);
    }
}

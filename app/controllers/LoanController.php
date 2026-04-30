<?php
declare(strict_types=1);

class LoanController extends BaseController
{
    public function index(): void
    {
        Auth::requireAuth(['estudiante', 'bibliotecario']);

        $isBibliotecario = Auth::hasRole('bibliotecario');
        if ($isBibliotecario) {
            $loans = Prestamo::activeLoans();
        } else {
            $estudianteId = (int) ($this->query(
                'SELECT id FROM estudiantes WHERE usuario_id = ? LIMIT 1',
                [Auth::getUserId()]
            )->fetch()['id'] ?? 0);

            $loans = Prestamo::activeLoansByStudent($estudianteId);
        }

        $this->render('loans/index', [
            'title' => 'Prestamos activos',
            'loans' => $loans,
            'isBibliotecario' => $isBibliotecario,
        ]);
    }
}

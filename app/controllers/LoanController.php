<?php
declare(strict_types=1);

class LoanController extends BaseController
{
    public function index(): void
    {
        $loans = Loan::activeLoans();

        $this->render('loans/index', [
            'title' => 'Prestamos activos',
            'loans' => $loans,
        ]);
    }
}

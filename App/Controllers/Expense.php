<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Date;
use \App\Models\ExpenseDB;

class Expense extends Authenticated
{

    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }

    public function newAction()
    {
        View::renderTemplate('Expense/new.html', [
            'user' => $this->user,
            'userExpenses' => ExpenseDB::getUserExpenseCategories(),
            'userPaymentMethods' => ExpenseDB::getUserPaymentMethods(),
            'currentDate' => Date::getTheCurrentDate()
        ]);
    }

    public function createAction()
    {
        $expense = new ExpenseDB($_POST);

        if ($expense->save()) {
            $this->redirect('/expense/success');
        } else {
            View::renderTemplate('Expense/new.html', [
                'user' => $this->user,
                'userErrors' => $expense,
                'userExpenses' => ExpenseDB::getUserExpenseCategories(),
                'userPaymentMethods' => ExpenseDB::getUserPaymentMethods(),
                'currentDate' => Date::getTheCurrentDate()
            ]);
        }
    }

    public function successAction()
    {
        View::renderTemplate('Expense/success.html');
    }
}

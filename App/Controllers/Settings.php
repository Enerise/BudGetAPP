<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\IncomeDB;
use \App\Models\ExpenseDB;

class Settings extends Authenticated
{
    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }

    public function showAction()
    {
        View::renderTemplate('Settings/show.html', [
            'user' => $this->user
        ]);
    }

    public function editDataAction()
    {
        View::renderTemplate('Settings/edit_data.html', [
            'user' => $this->user
        ]);
    }

    public function updateDataAction()
    {
        if ($this->user->updateProfile($_POST)) {

            Flash::addMessage('Changes saved');

            $this->redirect('/settings/success');
        } else {

            View::renderTemplate('Settings/edit_data.html', [
                'user' => $this->user
            ]);
        }
    }

    public function manageIncomesCategoriesAction()
    {
        View::renderTemplate('Settings/manage_Incomes_Category.html', [
            'user' => $this->user,
            'userIncomes' => IncomeDB::getUserIncomeCategories(),
        ]);
    }

    public function addIncomesCategoryAction()
    {
        $income = new IncomeDB($_POST);

        if ($income->addIncomeCategory()) {
            $this->redirect('/settings/success');
        } else {
            View::renderTemplate('Settings/manage_Incomes_Category.html', [
                'user' => $this->user,
                'userErrors' => $income,
                'userIncomes' => IncomeDB::getUserIncomeCategories(),
            ]);
        }
    }

    public function changeIncomesCategoryAction()
    {
        $income = new IncomeDB($_POST);

        if ($income->changeIncomesCategory()) {
            $this->redirect('/settings/success');
        } else {
            View::renderTemplate('Settings/manage_Incomes_Category.html', [
                'user' => $this->user,
                'userErrors' => $income,
                'userIncomes' => IncomeDB::getUserIncomeCategories(),
            ]);
        }
    }

    public function deleteIncomesCategoryAction()
    {
        $income = new IncomeDB($_POST);

        if ($income->deleteIncomesCategory()) {
            $this->redirect('/settings/success');
        } else {
            View::renderTemplate('Settings/show.html', [
                'user' => $this->user
            ]);
        }
    }

    public function manageExpensesCategoriesAction()
    {
        View::renderTemplate('Settings/manage_Expenses_Category.html', [
            'user' => $this->user,
            'userExpenses' => ExpenseDB::getUserExpenseCategories(),
        ]);
    }

    public function addExpensesCategoryAction()
    {
        $expense = new ExpenseDB($_POST);

        if ($expense->addExpensesCategory()) {
            $this->redirect('/settings/success');
        } else {
            View::renderTemplate('Settings/manage_Expenses_Category.html', [
                'user' => $this->user,
                'userErrors' => $expense,
                'userExpenses' => ExpenseDB::getUserExpenseCategories(),
            ]);
        }
    }

    public function changeExpensesCategoryAction()
    {
        $expense = new ExpenseDB($_POST);

        if ($expense->changeExpensesCategory()) {
            $this->redirect('/settings/success');
        } else {
            View::renderTemplate('Settings/manage_Expenses_Category.html', [
                'user' => $this->user,
                'userErrors' => $expense,
                'userExpenses' => ExpenseDB::getUserExpenseCategories(),
            ]);
        }
    }

    public function deleteExpensesCategoryAction()
    {
        $expense = new ExpenseDB($_POST);

        if ($expense->deleteExpensesCategory()) {
            $this->redirect('/settings/success');
        } else {
            View::renderTemplate('Settings/show.html', [
                'user' => $this->user
            ]);
        }
    }

    public function managePaymentMethodsAction()
    {
        View::renderTemplate('Settings/manage_Payment_Methods.html', [
            'user' => $this->user,
            'userPaymentMethods' => ExpenseDB::getUserPaymentMethods()
        ]);
    }

    public function addPaymentMethodsAction()
    {
        $expense = new ExpenseDB($_POST);

        if ($expense->addPaymentMethods()) {
            $this->redirect('/settings/success');
        } else {
            View::renderTemplate('Settings/manage_Payment_Methods.html', [
                'user' => $this->user,
                'userErrors' => $expense,
                'userPaymentMethods' => ExpenseDB::getUserPaymentMethods(),
            ]);
        }
    }

    public function changePaymentMethodsAction()
    {
        $expense = new ExpenseDB($_POST);

        if ($expense->changePaymentMethods()) {
            $this->redirect('/settings/success');
        } else {
            View::renderTemplate('Settings/manage_Payment_Methods.html', [
                'user' => $this->user,
                'userErrors' => $expense,
                'userPaymentMethods' => ExpenseDB::getUserPaymentMethods(),
            ]);
        }
    }

    public function deletePaymentMethodsAction()
    {
        $expense = new ExpenseDB($_POST);

        if ($expense->deletePaymentMethods()) {
            $this->redirect('/settings/success');
        } else {
            View::renderTemplate('Settings/manage_Payment_Methods.html', [
                'user' => $this->user
            ]);
        }
    }

    public function successAction()
    {
        View::renderTemplate('Settings/success.html', [
            'user' => $this->user
        ]);
    }
}

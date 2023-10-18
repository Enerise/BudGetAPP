<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\IncomeDB;

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

    public function manageIncomesCategoryAction()
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
            View::renderTemplate('Settings/show.html', [
                'user' => $this->user
            ]);
        }
    }

    public function changeIncomesCategoryAction()
    {
        $income = new IncomeDB($_POST);

        if ($income->changeIncomesCategory()) {
            $this->redirect('/settings/success');
        } else {
            View::renderTemplate('Settings/show.html', [
                'user' => $this->user
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

    public function successAction()
    {
        View::renderTemplate('Settings/success.html', [
            'user' => $this->user
        ]);
    }
}

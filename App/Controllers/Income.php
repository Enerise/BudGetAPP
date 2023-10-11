<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Date;
use \App\Models\IncomeDB;

class Income extends Authenticated
{
    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }

    public function newAction()
    {
        View::renderTemplate('Income/new.html', [
            'user' => $this->user,
            'userIncomes' => IncomeDB::getUserIncomeCategories(),
            'currentDate' => Date::getTheCurrentDate()
        ]);
    }

    public function createAction()
    {
        $income = new IncomeDB($_POST);

        if ($income->save()) {
            $this->redirect('/income/success');
        } else {
            View::renderTemplate('Income/new.html', [
                'user' => $user
            ]);
        }
    }

    public function successAction()
    {
        View::renderTemplate('Income/success.html');
    }
}

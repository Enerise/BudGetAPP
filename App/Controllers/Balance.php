<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Date;
use \App\Models\BalanceDB;

class Balance extends Authenticated
{

    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }

    public function indexAction()
    {
        //$currentMonth = Date::getTheFirstDayOfPreviousMonth();
        //var_dump($currentMonth);
        //exit();

        //$balance = new BalanceDB();

        // $show = BalanceDB::getBalanceWithSumOfAllCategoriesIncomes();
        // var_dump($show);
        // exit();

        //$show = BalanceDB::calculateBalance();
        //var_dump($show);
        //exit();

        View::renderTemplate('Balance/index.html', [
            'user' => $this->user,
            'sumAmountForCategoriesOfIncomes' => BalanceDB::getBalanceWithSumOfAllCategoriesIncomes(),
            'userParticularIncomes' => BalanceDB::getParticularIncomes(),
            'sumAmountForCategoriesOfExpenses' => BalanceDB::getBalanceWithSumOfAllCategoriesExpenses(),
            'userParticularExpenses' => BalanceDB::getParticularExpenses(),
            'balance' => BalanceDB::calculateBalance(),
            //'currentDate' => Date::getTheCurrentDate()
        ]);
    }
}

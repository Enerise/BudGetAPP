<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Date;
use \App\Models\BalanceDB;
use \App\Models\ExpenseDB;
use \App\Models\IncomeDB;

class Balance extends Authenticated
{

    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }

    public function indexAction()
    {


        View::renderTemplate('Balance/index.html', [
            'user' => $this->user,
            'userExpenses' => ExpenseDB::getUserExpenseCategories(),
            'userPaymentMethods' => ExpenseDB::getUserPaymentMethods(),
            'currentDate' => Date::getTheCurrentDate(),
            'userIncomes' => IncomeDB::getUserIncomeCategories()
        ]);
    }


    public function getParticularIncomesAction()
    {
        $selectedPeriod = $this->route_params['period'];

        $dateBegin = $this->route_params['datestart'];
        $dateEnd = $this->route_params['dateend'];

        switch ($selectedPeriod) {
            case "currentMonth":

                $dateBegin = Date::getTheFirstDayOfCurrentMonth();
                $dateEnd = Date::getTheLastDayOfCurrentMonth();

                echo json_encode(BalanceDB::getParticularIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "lastMonth":

                $dateBegin = Date::getTheDateWithFirstDayOfPreviousMonth();
                $dateEnd = Date::getTheDateWithLastDayOfPreviousMonth();


                echo json_encode(BalanceDB::getParticularIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "currentYear":

                $dateBegin = Date::getTheFirstDayOfCurrentYear();
                $dateEnd = Date::getTheLastDayOfCurrentYear();

                echo json_encode(BalanceDB::getParticularIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "custom":

                echo json_encode(BalanceDB::getParticularIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            default:
                return console . log(buttonInnerHTML);
                break;
        }
    }

    public function getParticularExpensesAction()
    {
        $selectedPeriod = $this->route_params['period'];

        $dateBegin = $this->route_params['datestart'];
        $dateEnd = $this->route_params['dateend'];

        switch ($selectedPeriod) {
            case "currentMonth":

                $dateBegin = Date::getTheFirstDayOfCurrentMonth();
                $dateEnd = Date::getTheLastDayOfCurrentMonth();

                echo json_encode(BalanceDB::getParticularExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "lastMonth":

                $dateBegin = Date::getTheDateWithFirstDayOfPreviousMonth();
                $dateEnd = Date::getTheDateWithLastDayOfPreviousMonth();

                echo json_encode(BalanceDB::getParticularExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "currentYear":

                $dateBegin = Date::getTheFirstDayOfCurrentYear();
                $dateEnd = Date::getTheLastDayOfCurrentYear();

                echo json_encode(BalanceDB::getParticularExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "custom":

                echo json_encode(BalanceDB::getParticularExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;

            default:
                return console . log(buttonInnerHTML);
                break;
        }
    }


    public function getSumAmountForCategoriesOfIncomes()
    {
        $selectedPeriod = $this->route_params['period'];

        $dateBegin = $this->route_params['datestart'];
        $dateEnd = $this->route_params['dateend'];

        switch ($selectedPeriod) {
            case "currentMonth":

                $dateBegin = Date::getTheFirstDayOfCurrentMonth();
                $dateEnd = Date::getTheLastDayOfCurrentMonth();

                echo json_encode(BalanceDB::getBalanceWithSumOfAllCategoriesIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "lastMonth":

                $dateBegin = Date::getTheDateWithFirstDayOfPreviousMonth();
                $dateEnd = Date::getTheDateWithLastDayOfPreviousMonth();

                echo json_encode(BalanceDB::getBalanceWithSumOfAllCategoriesIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "currentYear":

                $dateBegin = Date::getTheFirstDayOfCurrentYear();
                $dateEnd = Date::getTheLastDayOfCurrentYear();

                echo json_encode(BalanceDB::getBalanceWithSumOfAllCategoriesIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
                break;
            case "custom":

                echo json_encode(BalanceDB::getBalanceWithSumOfAllCategoriesIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            default:
                return console . log(buttonInnerHTML);
                break;
        }
    }

    public function getSumAmountForCategoriesOfExpenses()
    {

        $selectedPeriod = $this->route_params['period'];

        $dateBegin = $this->route_params['datestart'];
        $dateEnd = $this->route_params['dateend'];

        switch ($selectedPeriod) {
            case "currentMonth":

                $dateBegin = Date::getTheFirstDayOfCurrentMonth();
                $dateEnd = Date::getTheLastDayOfCurrentMonth();

                echo json_encode(BalanceDB::getBalanceWithSumOfAllCategoriesExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "lastMonth":

                $dateBegin = Date::getTheDateWithFirstDayOfPreviousMonth();
                $dateEnd = Date::getTheDateWithLastDayOfPreviousMonth();

                echo json_encode(BalanceDB::getBalanceWithSumOfAllCategoriesExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "currentYear":

                $dateBegin = Date::getTheFirstDayOfCurrentYear();
                $dateEnd = Date::getTheLastDayOfCurrentYear();

                echo json_encode(BalanceDB::getBalanceWithSumOfAllCategoriesExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "custom":

                echo json_encode(BalanceDB::getBalanceWithSumOfAllCategoriesExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            default:
                return console . log(buttonInnerHTML);
                break;
        }
    }

    public function getSumOfBalance()
    {

        $selectedPeriod = $this->route_params['period'];
        $dateBegin = $this->route_params['datestart'];
        $dateEnd = $this->route_params['dateend'];

        switch ($selectedPeriod) {
            case "currentMonth":

                $dateBegin = Date::getTheFirstDayOfCurrentMonth();
                $dateEnd = Date::getTheLastDayOfCurrentMonth();

                echo json_encode(BalanceDB::calculateBalance($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "lastMonth":

                $dateBegin = Date::getTheDateWithFirstDayOfPreviousMonth();
                $dateEnd = Date::getTheDateWithLastDayOfPreviousMonth();

                echo json_encode(BalanceDB::calculateBalance($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "currentYear":

                $dateBegin = Date::getTheFirstDayOfCurrentYear();
                $dateEnd = Date::getTheLastDayOfCurrentYear();

                echo json_encode(BalanceDB::calculateBalance($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "custom":

                echo json_encode(BalanceDB::calculateBalance($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            default:
                return console . log(buttonInnerHTML);
                break;
        }
    }

    public function updateParticularIncome()
    {
        $selectedPeriod = $this->route_params['period'];
        $amount = $this->route_params['amount'];
        $date = $this->route_params['date'];
        $category = $this->route_params['category'];
        $comment = $this->route_params['comment'];
        $incomeID = $this->route_params['incomeid'];
        $dateBegin = $this->route_params['datestart'];
        $dateEnd = $this->route_params['dateend'];

        if ($comment == "empty") {
            $comment = "";
        }

        BalanceDB::updateParticularIncomes($amount, $date, $category, $incomeID, $comment);

        switch ($selectedPeriod) {
            case "currentMonth":

                $dateBegin = Date::getTheFirstDayOfCurrentMonth();
                $dateEnd = Date::getTheLastDayOfCurrentMonth();

                echo json_encode(BalanceDB::getParticularIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "lastMonth":

                $dateBegin = Date::getTheDateWithFirstDayOfPreviousMonth();
                $dateEnd = Date::getTheDateWithLastDayOfPreviousMonth();

                echo json_encode(BalanceDB::getParticularIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "currentYear":

                $dateBegin = Date::getTheFirstDayOfCurrentYear();
                $dateEnd = Date::getTheLastDayOfCurrentYear();

                echo json_encode(BalanceDB::getParticularIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "custom":

                echo json_encode(BalanceDB::getParticularIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;

            default:
                return console . log(buttonInnerHTML);
                break;
        }
    }

    public function deleteParticularIncome()
    {
        $selectedPeriod = $this->route_params['period'];
        $incomeID = $this->route_params['incomeid'];
        $dateBegin = $this->route_params['datestart'];
        $dateEnd = $this->route_params['dateend'];

        BalanceDB::deleteParticularIncomes($incomeID);

        switch ($selectedPeriod) {
            case "currentMonth":

                $dateBegin = Date::getTheFirstDayOfCurrentMonth();
                $dateEnd = Date::getTheLastDayOfCurrentMonth();

                echo json_encode(BalanceDB::getParticularIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "lastMonth":

                $dateBegin = Date::getTheDateWithFirstDayOfPreviousMonth();
                $dateEnd = Date::getTheDateWithLastDayOfPreviousMonth();

                echo json_encode(BalanceDB::getParticularIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "currentYear":

                $dateBegin = Date::getTheFirstDayOfCurrentYear();
                $dateEnd = Date::getTheLastDayOfCurrentYear();

                echo json_encode(BalanceDB::getParticularIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "custom":

                echo json_encode(BalanceDB::getParticularIncomes($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;

            default:
                return console . log(buttonInnerHTML);
                break;
        }
    }

    public function updateParticularExpense()
    {
        $selectedPeriod = $this->route_params['period'];
        $amount = $this->route_params['amount'];
        $date = $this->route_params['date'];
        $payment = $this->route_params['payment'];
        $category = $this->route_params['category'];
        $comment = $this->route_params['comment'];
        $expenseID = $this->route_params['expenseid'];
        $dateBegin = $this->route_params['datestart'];
        $dateEnd = $this->route_params['dateend'];

        if ($comment == "empty") {
            $comment = "";
        }

        BalanceDB::updateParticularExpenses($amount, $date, $payment, $category, $expenseID, $comment);

        switch ($selectedPeriod) {
            case "currentMonth":

                $dateBegin = Date::getTheFirstDayOfCurrentMonth();
                $dateEnd = Date::getTheLastDayOfCurrentMonth();

                echo json_encode(BalanceDB::getParticularExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "lastMonth":

                $dateBegin = Date::getTheDateWithFirstDayOfPreviousMonth();
                $dateEnd = Date::getTheDateWithLastDayOfPreviousMonth();

                echo json_encode(BalanceDB::getParticularExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "currentYear":

                $dateBegin = Date::getTheFirstDayOfCurrentYear();
                $dateEnd = Date::getTheLastDayOfCurrentYear();

                echo json_encode(BalanceDB::getParticularExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "custom":

                echo json_encode(BalanceDB::getParticularExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;

            default:
                return console . log(buttonInnerHTML);
                break;
        }
    }

    public function deleteParticularExpense()
    {
        $selectedPeriod = $this->route_params['period'];
        $expenseID = $this->route_params['expenseid'];
        $dateBegin = $this->route_params['datestart'];
        $dateEnd = $this->route_params['dateend'];

        BalanceDB::deleteParticularExpenses($expenseID);

        switch ($selectedPeriod) {
            case "currentMonth":

                $dateBegin = Date::getTheFirstDayOfCurrentMonth();
                $dateEnd = Date::getTheLastDayOfCurrentMonth();

                echo json_encode(BalanceDB::getParticularExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "lastMonth":

                $dateBegin = Date::getTheDateWithFirstDayOfPreviousMonth();
                $dateEnd = Date::getTheDateWithLastDayOfPreviousMonth();

                echo json_encode(BalanceDB::getParticularExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "currentYear":

                $dateBegin = Date::getTheFirstDayOfCurrentYear();
                $dateEnd = Date::getTheLastDayOfCurrentYear();

                echo json_encode(BalanceDB::getParticularExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;
            case "custom":

                echo json_encode(BalanceDB::getParticularExpenses($dateBegin, $dateEnd), JSON_UNESCAPED_UNICODE);

                break;

            default:
                return console . log(buttonInnerHTML);
                break;
        }
    }
}

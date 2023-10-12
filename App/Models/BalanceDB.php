<?php

namespace App\Models;

use PDO;
use \App\Date;

class BalanceDB extends \Core\Model
{
    public static function getBalanceWithSumOfAllCategoriesIncomes()
    {
        $date_begin = Date::getTheFirstDayOfCurrentMonth();
        $date_end = Date::getTheLastDayOfCurrentMonth();

        $sql = "SELECT incomes_category_assigned_to_users.name AS category, 
        SUM(incomes.amount) AS amount 
        FROM 
        incomes_category_assigned_to_users 
        INNER JOIN incomes ON 
        incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id 
        WHERE incomes.user_id = :user_id AND 
        incomes.date_of_income BETWEEN :date_begin AND :date_end
        GROUP BY incomes.income_category_assigned_to_user_id 
        ORDER BY amount DESC";

        $db = static::getDB();
        $expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenseCategories->bindValue(':date_begin', $date_begin, PDO::PARAM_STR);
        $expenseCategories->bindValue(':date_end', $date_end, PDO::PARAM_STR);
        $expenseCategories->execute();

        return $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getParticularIncomes()
    {
        $date_begin = Date::getTheFirstDayOfCurrentMonth();
        $date_end = Date::getTheLastDayOfCurrentMonth();

        $sql = "SELECT incomes_category_assigned_to_users.name AS category, 
        incomes.amount AS amount,
        incomes.date_of_income AS date,
        incomes.income_comment AS comment 
        FROM 
        incomes_category_assigned_to_users 
        INNER JOIN incomes ON 
        incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id 
        WHERE incomes.user_id = :user_id AND 
        incomes.date_of_income BETWEEN :date_begin AND :date_end
        ORDER BY amount DESC";

        $db = static::getDB();
        $expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenseCategories->bindValue(':date_begin', $date_begin, PDO::PARAM_STR);
        $expenseCategories->bindValue(':date_end', $date_end, PDO::PARAM_STR);
        $expenseCategories->execute();

        return $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSumOFIncomes()
    {
        $show = static::getParticularIncomes();
        $sumOfAmounts = 0;

        foreach ($show as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $insideKey => $insideValue) {
                    if ($insideKey == 'amount') {
                        $sumOfAmounts += $insideValue;
                    }
                }
            }
        }

        return $sumOfAmounts;
    }

    public static function getBalanceWithSumOfAllCategoriesExpenses()
    {
        $date_begin = Date::getTheFirstDayOfCurrentMonth();
        $date_end = Date::getTheLastDayOfCurrentMonth();

        $sql = "SELECT expenses_category_assigned_to_users.name AS category, 
        SUM(expenses.amount) AS amount 
        FROM 
        expenses_category_assigned_to_users 
        INNER JOIN expenses ON 
        expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id 
        WHERE expenses.user_id = :user_id AND 
        expenses.date_of_expense BETWEEN :date_begin AND :date_end
        GROUP BY expenses.expense_category_assigned_to_user_id 
        ORDER BY amount DESC";

        $db = static::getDB();
        $expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenseCategories->bindValue(':date_begin', $date_begin, PDO::PARAM_STR);
        $expenseCategories->bindValue(':date_end', $date_end, PDO::PARAM_STR);
        $expenseCategories->execute();

        return $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
    }
}

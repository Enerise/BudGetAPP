<?php

namespace App\Models;

use PDO;
use \App\Date;

class BalanceDB extends \Core\Model
{
    public static function getBalanceWithSumOfAllCategoriesIncomes($dateBegin, $dateEnd)
    {

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
        $expenseCategories->bindValue(':date_begin', $dateBegin, PDO::PARAM_STR);
        $expenseCategories->bindValue(':date_end', $dateEnd, PDO::PARAM_STR);
        $expenseCategories->execute();

        return $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getParticularIncomes($dateBegin, $dateEnd)
    {

        $sql = "SELECT incomes_category_assigned_to_users.name AS category, 
        incomes.amount AS amount,
        incomes.date_of_income AS date,
        incomes.income_comment AS comment, 
        incomes.id AS income_index
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
        $expenseCategories->bindValue(':date_begin', $dateBegin, PDO::PARAM_STR);
        $expenseCategories->bindValue(':date_end', $dateEnd, PDO::PARAM_STR);
        $expenseCategories->execute();

        return $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSumOFIncomes($dateBegin, $dateEnd)
    {
        $show = static::getParticularIncomes($dateBegin, $dateEnd);
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

    public static function getBalanceWithSumOfAllCategoriesExpenses($dateBegin, $dateEnd)
    {

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
        $expenseCategories->bindValue(':date_begin', $dateBegin, PDO::PARAM_STR);
        $expenseCategories->bindValue(':date_end', $dateEnd, PDO::PARAM_STR);
        $expenseCategories->execute();

        return $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getParticularExpenses($dateBegin, $dateEnd)
    {

        $sql = "SELECT expenses_category_assigned_to_users.name AS category, 
        expenses.amount AS amount,
        expenses.date_of_expense AS date,
        expenses.expense_comment AS comment,
        payment_methods_assigned_to_users.name AS payment, 
        expenses.id AS expense_index
        FROM 
        expenses 
        INNER JOIN expenses_category_assigned_to_users ON 
        expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id 
        INNER JOIN payment_methods_assigned_to_users ON 
        expenses.payment_method_assigned_to_user_id = payment_methods_assigned_to_users.id 
        WHERE expenses.user_id = :user_id AND 
        expenses.date_of_expense BETWEEN :date_begin AND :date_end
        ORDER BY amount DESC";

        $db = static::getDB();
        $expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenseCategories->bindValue(':date_begin', $dateBegin, PDO::PARAM_STR);
        $expenseCategories->bindValue(':date_end', $dateEnd, PDO::PARAM_STR);
        $expenseCategories->execute();

        return $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSumOFExpenses($dateBegin, $dateEnd)
    {
        $show = static::getParticularExpenses($dateBegin, $dateEnd);
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

    public static function updateParticularExpenses($amount, $date, $payment, $category, $expenseID, $comment)
    {

        $sql = 'UPDATE expenses
        SET expense_category_assigned_to_user_id = (SELECT expenses_category_assigned_to_users.id FROM expenses_category_assigned_to_users WHERE expenses_category_assigned_to_users.name = :expenseCategory AND expenses_category_assigned_to_users.user_id = :user_id),

        payment_method_assigned_to_user_id = (SELECT payment_methods_assigned_to_users.id FROM payment_methods_assigned_to_users WHERE payment_methods_assigned_to_users.name = :paymentMethod AND payment_methods_assigned_to_users.user_id = :user_id),

        amount = :amount,
        date_of_expense = :date_of_expense,
        expense_comment = :comment
        WHERE id = :id AND user_id = :user_id';


        $db = static::getDB();
        $expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenseCategories->bindValue(':expenseCategory', $category, PDO::PARAM_STR);
        $expenseCategories->bindValue(':paymentMethod', $payment, PDO::PARAM_STR);
        $expenseCategories->bindValue(':amount', $amount, PDO::PARAM_INT);
        $expenseCategories->bindValue(':date_of_expense', $date, PDO::PARAM_STR);
        $expenseCategories->bindValue(':comment', $comment, PDO::PARAM_STR);
        $expenseCategories->bindValue(':id', $expenseID, PDO::PARAM_INT);
        $expenseCategories->execute();
    }

    public static function updateParticularIncomes($amount, $date, $category, $incomeID, $comment)
    {
        $sql = 'UPDATE incomes
        SET income_category_assigned_to_user_id = (SELECT incomes_category_assigned_to_users.id FROM incomes_category_assigned_to_users WHERE incomes_category_assigned_to_users.name = :incomeCategory AND incomes_category_assigned_to_users.user_id = :user_id),

        amount = :amount,
        date_of_income = :date_of_income,
        income_comment = :comment
        WHERE id = :id AND user_id = :user_id';


        $db = static::getDB();
        $expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenseCategories->bindValue(':incomeCategory', $category, PDO::PARAM_STR);
        $expenseCategories->bindValue(':amount', $amount, PDO::PARAM_INT);
        $expenseCategories->bindValue(':date_of_income', $date, PDO::PARAM_STR);
        $expenseCategories->bindValue(':comment', $comment, PDO::PARAM_STR);
        $expenseCategories->bindValue(':id', $incomeID, PDO::PARAM_INT);
        $expenseCategories->execute();
    }

    public static function deleteParticularIncomes($incomeID)
    {

        $sql = 'DELETE FROM incomes
        WHERE id = :id AND user_id = :user_id';

        $db = static::getDB();
        $expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenseCategories->bindValue(':id', $incomeID, PDO::PARAM_INT);
        $expenseCategories->execute();
    }

    public static function deleteParticularExpenses($expenseID)
    {
        $sql = 'DELETE FROM expenses
        WHERE id = :id AND user_id = :user_id';

        $db = static::getDB();
        $expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenseCategories->bindValue(':id', $expenseID, PDO::PARAM_INT);
        $expenseCategories->execute();
    }

    public static function calculateBalance($dateBegin, $dateEnd)
    {
        $sumOFIncome = static::getSumOFIncomes($dateBegin, $dateEnd);
        $sumOfExpense = static::getSumOFExpenses($dateBegin, $dateEnd);

        return $sumOFIncome - $sumOfExpense;
    }
}

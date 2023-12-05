<?php

namespace App\Models;

use PDO;
use \App\Date;

class ExpenseDB extends \Core\Model
{

    public $errors = [];

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $this->$key  = $value;
        };
    }

    public static function getUserExpenseCategories()
    {
        $sql = "SELECT name, id FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name != :name";

        $db = static::getDB();
        $expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenseCategories->bindValue(':name', 'Inne', PDO::PARAM_STR);
        $expenseCategories->execute();

        return $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUserPaymentMethods()
    {
        $sql = "SELECT name, id FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name != :name";

        $db = static::getDB();
        $paymentMethods = $db->prepare($sql);
        $paymentMethods->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $paymentMethods->bindValue(':name', 'Inne', PDO::PARAM_STR);
        $paymentMethods->execute();

        return $paymentMethods->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {
            $sql = 'INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
            SELECT :user_id, expenses_category_assigned_to_users.id, payment_methods_assigned_to_users.id, :amount, :date_of_expense, :expense_comment
            FROM expenses_category_assigned_to_users, payment_methods_assigned_to_users
            WHERE expenses_category_assigned_to_users.name = :expenseCategory AND expenses_category_assigned_to_users.user_id = :user_id AND payment_methods_assigned_to_users.name = :paymentMethod AND
            payment_methods_assigned_to_users.user_id = :user_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_INT);
            $stmt->bindValue(':paymentMethod', $this->paymentMethod, PDO::PARAM_STR);
            $stmt->bindValue(':expenseCategory', $this->expenseCategory, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_expense', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':expense_comment', $this->comment, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function findTheSameNameOfExpensesCategory()
    {
        $sql = "SELECT name FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND expenses_category_assigned_to_users.name = :name";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':name', $this->newExpenseCategory, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addExpensesCategory()
    {
        $this->validateSameExpensesCategory();

        $this->validateLimitForCategory();

        if (empty($this->errors)) {
            $sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name, limit_category)
            VALUES (:user_id, :name';

            if ($this->newLimitCategory == "") {
                $sql .= ', NULL)';
            } else {
                $sql .= ', :new_limit';
            }

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':name', $this->newExpenseCategory, PDO::PARAM_STR);

            if (!($this->newLimitCategory == "")) {
                $stmt->bindValue(':new_limit', $this->newLimitCategory, PDO::PARAM_INT);
            }

            return $stmt->execute();
        }
        return false;
    }

    public function changeExpensesCategory()
    {
        $this->validateSameExpensesCategory();

        if (empty($this->errors)) {
            $sql = 'UPDATE expenses_category_assigned_to_users
                    SET expenses_category_assigned_to_users.name = :name
                    WHERE expenses_category_assigned_to_users.name = :selectCategory AND user_id = :user_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->newExpenseCategory, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':selectCategory', $this->expenseCategory, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function deleteExpensesCategory()
    {
        if ($this->deleteExpensesCategoryCD()) {
            $sql = 'DELETE FROM expenses_category_assigned_to_users
        WHERE expenses_category_assigned_to_users.name = :name AND user_id = :user_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->expenseCategory, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

            return $stmt->execute();
        }
        return false;
    }

    public function deleteExpensesCategoryCD()
    {
        $sql = 'DELETE expenses 
        FROM expenses 
        INNER JOIN expenses_category_assigned_to_users ON
        expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id 
        WHERE expenses_category_assigned_to_users.name = :name AND expenses.user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $this->expenseCategory, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function changeLimitCategory()
    {
        if (empty($this->errors)) {
            $sql = 'UPDATE expenses_category_assigned_to_users
                    SET expenses_category_assigned_to_users.limit_category = :limitValue
                    WHERE expenses_category_assigned_to_users.name = :selectCategory AND user_id = :user_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':limitValue', $this->newLimitCategory, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':selectCategory', $this->expenseCategory, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function findTheSameNameOfPaymentMethods()
    {
        $sql = "SELECT name FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND payment_methods_assigned_to_users.name = :name";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':name', $this->newPaymentMethod, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPaymentMethods()
    {
        $this->validateSamePaymentMethods();

        if (empty($this->errors)) {
            $sql = 'INSERT INTO payment_methods_assigned_to_users (user_id, name)
            VALUES (:user_id, :name)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':name', $this->newPaymentMethod, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function changePaymentMethods()
    {
        $this->validateSamePaymentMethods();

        if (empty($this->errors)) {
            $sql = 'UPDATE payment_methods_assigned_to_users
                    SET payment_methods_assigned_to_users.name = :name
                    WHERE payment_methods_assigned_to_users.name = :selectCategory AND user_id = :user_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->newPaymentMethod, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':selectCategory', $this->paymentMethod, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function deletePaymentMethods()
    {
        if ($this->deletePaymentMethodsCD()) {
            $sql = 'DELETE FROM payment_methods_assigned_to_users
        WHERE payment_methods_assigned_to_users.name = :name AND user_id = :user_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->paymentMethod, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

            return $stmt->execute();
        }
        return false;
    }

    public function deletePaymentMethodsCD()
    {
        $sql = 'DELETE expenses FROM expenses
        INNER JOIN payment_methods_assigned_to_users ON
        expenses.payment_method_assigned_to_user_id = payment_methods_assigned_to_users.id
        WHERE payment_methods_assigned_to_users.name = :name AND expenses.user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $this->paymentMethod, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function getLimit($user_id, $category)
    {
        $sql = "SELECT expenses_category_assigned_to_users.limit_category AS limitCategory
        FROM expenses_category_assigned_to_users 
        WHERE  expenses_category_assigned_to_users.user_id = :user_id AND expenses_category_assigned_to_users.name = :name";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $category, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if (!empty($result)) {
            return (int) $result->limitCategory;
        } else {
            return '';
        }
    }

    public static function getMonthlyExpenses($user_id, $category, $date)
    {
        $date_begin = Date::getTheFirstDayOfMonthFromUser($date);
        $date_end = Date::getTheLastDayOfMonthFromUser($date);

        $sql = "SELECT expenses_category_assigned_to_users.name AS category, 
        SUM(expenses.amount) AS amount 
        FROM 
        expenses_category_assigned_to_users 
        INNER JOIN expenses ON 
        expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id 
        WHERE expenses.user_id = :user_id AND 
        expenses_category_assigned_to_users.name = :name AND 
        expenses.date_of_expense BETWEEN :date_begin AND :date_end
        GROUP BY expenses.expense_category_assigned_to_user_id";

        $db = static::getDB();
        $expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $expenseCategories->bindValue(':date_begin', $date_begin, PDO::PARAM_STR);
        $expenseCategories->bindValue(':date_end', $date_end, PDO::PARAM_STR);
        $expenseCategories->bindValue(':name', $category, PDO::PARAM_STR);
        $expenseCategories->execute();

        return $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSumOFMonthlyExpenses($user_id, $category, $date)
    {
        $show = static::getMonthlyExpenses($user_id, $category, $date);
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

        return (int) $sumOfAmounts;
    }

    public function validateLimit()
    {
        if (NULL == $this->expenseCategory) {
            $this->errors[] = 'Należy wybrać kategorię, do której ustawiany jest limit';
        }
        if (!((is_numeric($this->newLimitCategory)) && ($this->newLimitCategory))) {
            $this->errors[] = 'Wprowadź dodatnią liczbę';
        }

        if (NULL == $this->newLimitCategory) {
            $this->errors[] = 'Nie można dodać żadnego limitu. Domyślnie ustawiony jest brak.';
        }
    }

    public function validateLimitForCategory()
    {
        if ($this->newLimitCategory == "") {
            return true;
        } else if (!((is_numeric($this->newLimitCategory)) && ($this->newLimitCategory > 0))) {
            $this->errors[] = 'Wprowadź dodatnią liczbę';
        }
    }

    public function validateSameExpensesCategory()
    {
        if (NULL != $this->findTheSameNameOfExpensesCategory()) {
            $this->errors[] = 'Taka kategoria już istnieje - wpisz inną nazwę';
        }

        if (NULL == $this->newExpenseCategory) {
            $this->errors[] = 'Nie można dodać pustej kategorii - wpisz nazwę.';
        }
    }

    public function validateSamePaymentMethods()
    {
        if (NULL != $this->findTheSameNameOfPaymentMethods()) {
            $this->errors[] = 'Taka kategoria już istnieje - wpisz inną nazwę';
        }

        if (NULL == $this->newPaymentMethod) {
            $this->errors[] = 'Nie można dodać pustej metody - wpisz nazwę.';
        }
    }

    public function validate()
    {
        if (!isset($this->paymentMethod)) {
            $this->errors[] = 'Wybór płatności jest wymagany';
        }

        if (!isset($this->expenseCategory)) {
            $this->errors[] = 'Wybór kategorii wydatku jest wymagany';
        }

        if (!((is_numeric($this->amount)) && ($this->amount > 0))) {
            $this->errors[] = 'Wprowadź dodatnią liczbę';
        }
    }
}

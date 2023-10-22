<?php

namespace App\Models;

use PDO;

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

        if (empty($this->errors)) {
            $sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name)
            VALUES (:user_id, :name)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':name', $this->newExpenseCategory, PDO::PARAM_STR);

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
        $sql = 'DELETE FROM expenses_category_assigned_to_users
        WHERE expenses_category_assigned_to_users.name = :name AND user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $this->expenseCategory, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        return $stmt->execute();
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
        $sql = 'DELETE FROM payment_methods_assigned_to_users
        WHERE payment_methods_assigned_to_users.name = :name AND user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $this->paymentMethod, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function validateSameExpensesCategory()
    {
        if (NULL != $this->findTheSameNameOfExpensesCategory()) {
            $this->errors[] = 'Taka kategoria już istnieje - wpisz inną nazwę';
        }
    }

    public function validateSamePaymentMethods()
    {
        if (NULL != $this->findTheSameNameOfPaymentMethods()) {
            $this->errors[] = 'Taka kategoria już istnieje - wpisz inną nazwę';
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

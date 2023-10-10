<?php

namespace App\Models;

use PDO;

class ExpenseDB extends \Core\Model
{

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
}

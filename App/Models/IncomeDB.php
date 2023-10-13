<?php

namespace App\Models;

use PDO;

class IncomeDB extends \Core\Model
{

    public $errors = [];

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $this->$key  = $value;
        };
    }

    public static function getUserIncomeCategories()
    {
        $sql = "SELECT name, id FROM incomes_category_assigned_to_users WHERE user_id = :user_id";
        //name != :name";

        $db = static::getDB();
        $expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        //$expenseCategories->bindValue(':name', 'Inne', PDO::PARAM_STR);
        $expenseCategories->execute();

        return $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {
            $sql = 'INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
            SELECT :user_id, incomes_category_assigned_to_users.id, :amount, :date_of_expense, :expense_comment
            FROM incomes_category_assigned_to_users
            WHERE incomes_category_assigned_to_users.name = :expenseCategory AND incomes_category_assigned_to_users.user_id = :user_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_INT);
            $stmt->bindValue(':expenseCategory', $this->incomeCategory, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_expense', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':expense_comment', $this->comment, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function validate()
    {
        if (!isset($this->incomeCategory)) {
            $this->errors[] = 'Wybór kategorii przychodu jest wymagany';
        }
    }
}

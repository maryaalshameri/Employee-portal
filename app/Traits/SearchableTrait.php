<?php
namespace App\Traits;
trait SearchableTrait {
    public function searchByColumn($table, $column, $value){
        $stmt = App::db()->prepare("SELECT * FROM $table WHERE $column LIKE :value");
        $stmt->execute(['value' => "%$value%"]);
        return $stmt->fetchAll();
    }
}
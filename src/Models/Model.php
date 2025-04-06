<?php

namespace MediQuick\Models;

use PDO;
use MediQuick\Config\Config;

abstract class Model
{
    protected static $db = null;
    protected static $table = '';
    protected static $fillable = [];

    protected static function getDB()
    {
        if (self::$db === null) {
            $config = Config::getInstance();
            $dbConfig = $config->get('database');
            
            $dsn = sprintf(
                "%s:host=%s;port=%s;dbname=%s",
                $dbConfig['connection'],
                $dbConfig['host'],
                $dbConfig['port'],
                $dbConfig['database']
            );

            try {
                self::$db = new PDO(
                    $dsn,
                    $dbConfig['username'],
                    $dbConfig['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (\PDOException $e) {
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$db;
    }

    public static function find($id)
    {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM " . static::$table . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function all()
    {
        $db = self::getDB();
        $stmt = $db->query("SELECT * FROM " . static::$table);
        return $stmt->fetchAll();
    }

    public static function create(array $data)
    {
        $db = self::getDB();
        $data = array_intersect_key($data, array_flip(static::$fillable));
        
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        
        $stmt = $db->prepare("INSERT INTO " . static::$table . " ($columns) VALUES ($values)");
        $stmt->execute(array_values($data));
        
        return $db->lastInsertId();
    }

    public static function update($id, array $data)
    {
        $db = self::getDB();
        $data = array_intersect_key($data, array_flip(static::$fillable));
        
        $set = implode(', ', array_map(function($column) {
            return "$column = ?";
        }, array_keys($data)));
        
        $stmt = $db->prepare("UPDATE " . static::$table . " SET $set WHERE id = ?");
        $values = array_values($data);
        $values[] = $id;
        
        return $stmt->execute($values);
    }

    public static function delete($id)
    {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM " . static::$table . " WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM " . static::$table . " WHERE $column $operator ?");
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }
} 
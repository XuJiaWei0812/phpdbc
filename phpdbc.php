<?php
class Database
{

    private $conn;
    private $table_name;

    public function __construct()
    {
        $db_setting = parse_ini_file('config.ini');
        $host = $db_setting['host'];
        $user = $db_setting['user'];
        $password = $db_setting['password'];
        $database = $db_setting['db'];
        $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
        $this->conn = new PDO($dsn, $user, $password);
        $this->table_name = null;
    }

    public function set_table($table_name)
    {
        $this->table_name = $table_name;
    }

    public function insert($data)
    {
        $keys = array_keys($data);
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $columns = implode(',', $keys);
        $values = array_values($data);
        $stmt = $this->conn->prepare("INSERT INTO $this->table_name ($columns) VALUES ($placeholders)");
        $stmt->execute($values);
    }

    public function select($columns = '*', $where = '', $order_by = '', $limit = '', $fetchone = false)
    {
        $query = "SELECT $columns FROM $this->table_name";
        if ($where) {
            $query .= " WHERE $where";
        }
        if ($order_by) {
            $query .= " ORDER BY $order_by";
        }
        if ($limit) {
            $query .= " LIMIT $limit";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if ($fetchone) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function update($set_values, $where = null)
    {
        $query = "UPDATE $this->table_name SET $set_values";
        if ($where !== null) {
            $query .= " WHERE $where";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    public function delete($where = null)
    {
        $query = "DELETE FROM $this->table_name";
        if ($where !== null) {
            $query .= " WHERE $where";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}

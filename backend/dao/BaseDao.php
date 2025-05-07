<?php
require_once __DIR__ . '/../config.php';

class BaseDao {
    protected $connection;
    protected $table_name;
    protected $primary_key;

    public function __construct($table_name, $primary_key = 'id') {
        $this->table_name = $table_name;
        $this->primary_key = $primary_key;
        $this->connect();
    }

    private function connect() {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO(
                    "mysql:host=" . Config::DB_HOST() . 
                    ";dbname=" . Config::DB_NAME() . 
                    ";port=" . Config::DB_PORT() . 
                    ";charset=utf8mb4",
                    Config::DB_USER(),
                    Config::DB_PASSWORD(),
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_PERSISTENT => false
                    ]
                );
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                throw new Exception("Database connection error");
            }
        }
    }

    protected function query($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->handleException($e, $query);
        }
    }

    protected function query_unique($query, $params = []) {
        $results = $this->query($query, $params);
        return reset($results);
    }

    public function beginTransaction() {
        $this->connection->beginTransaction();
    }

    public function commit() {
        $this->connection->commit();
    }

    public function rollBack() {
        $this->connection->rollBack();
    }

    public function add($entity) {
        $columns = implode(', ', array_keys($entity));
        $placeholders = ':' . implode(', :', array_keys($entity));
        $query = "INSERT INTO {$this->table_name} ($columns) VALUES ($placeholders)";
        
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($entity);
            $entity[$this->primary_key] = $this->connection->lastInsertId();
            return $entity;
        } catch (PDOException $e) {
            $this->handleException($e, $query);
        }
    }

    public function update($id, $entity, $id_column = "id") {
        $setParts = [];
        $params = [];
        foreach ($entity as $column => $value) {
            $setParts[] = "$column = :$column";
            $params[$column] = $value;
        }
        $setClause = implode(', ', $setParts);
        $query = "UPDATE {$this->table_name} SET $setClause WHERE $id_column = :id";
        $params['id'] = $id;

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->handleException($e, $query);
        }
    }

    public function delete($id, $id_column = "id") {
        $query = "DELETE FROM {$this->table_name} WHERE $id_column = :id";
        
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute(['id' => $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->handleException($e, $query);
        }
    }

    public function getById($id, $id_column = "id") {
        $query = "SELECT * FROM {$this->table_name} WHERE $id_column = :id";
        return $this->query_unique($query, ['id' => $id]);
    }

    public function getAll($order_column = null, $order_direction = "ASC") {
        try {
            $order_column = $order_column ?: $this->primary_key;
            $order_direction = strtoupper($order_direction) === 'DESC' ? 'DESC' : 'ASC';
            
            // Validate  order column exists in the table
            $columns = $this->getTableColumns();
            if (!in_array($order_column, $columns)) {
                throw new Exception("Invalid order column: $order_column");
            }

            $query = "SELECT * FROM {$this->table_name} ORDER BY `$order_column` $order_direction";
            return $this->query($query);
        } catch (Exception $e) {
            error_log("Database error in getAll(): " . $e->getMessage());
            throw new Exception("Database operation failed: " . $e->getMessage());
        }
    }

    private function getTableColumns() {
        $query = "DESCRIBE {$this->table_name}";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'Field');
    }

    protected function handleException(PDOException $e, $query = "") {
        error_log("Database error: " . $e->getMessage() . " in query: " . $query);
        throw new Exception("Database operation failed: " . $e->getMessage());
    }

    public function __destruct() {
        $this->connection = null;
    }
    
}
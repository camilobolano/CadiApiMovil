<?php

class Database
{
    protected $connection = null;

    public function __construct()
    {
        try {
            // Definiendo la cadena de conexión (DSN)
            $host = Config::DB_HOST;
            $port = Config::DB_PORT;
            $dbname = Config::DB_DATABASE;
            $username = Config::DB_USER;
            $password = Config::DB_PASSWORD;
            $endpointId = Config::DB_ENDPOINT_ID;

            // Cadena de conexión sin el parámetro de endpoint
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

            // Modificando la contraseña para incluir el endpoint ID
            $passwordWithEndpoint = "endpoint=$endpointId;$password";

            // Estableciendo la conexión usando PDO
            $this->connection = new PDO($dsn, $username, $passwordWithEndpoint);

            // Configurando el modo de error de PDO a excepción
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("No se pudo conectar a la base de datos: " . $e->getMessage());
        }
    }


    public function __destruct()
    {
        // Cerrando la conexión
        $this->connection = null;
    }

    public function update($query = "", $params = [])
    {
        try {
            $stmt = $this->executeStatement($query, $params);
            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("Error en la actualización: " . $e->getMessage());
        }
    }

    public function select($query = "", $params = [])
    {
        try {
            $stmt = $this->executeStatement($query, $params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error en la selección: " . $e->getMessage());
        }
    }

    public function insert($query = "", $params = [])
    {
        try {
            $stmt = $this->executeStatement($query, $params);
            // Obtén el ID del registro insertado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['documento'] : null;
        } catch (Exception $e) {
            throw new Exception("Error en la inserción: " . $e->getMessage());
        }
    }


    public function delete($query = "", $params = [])
    {
        try {
            $stmt = $this->executeStatement($query, $params);
            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("Error en la eliminación: " . $e->getMessage());
        }
    }

    private function executeStatement($query = "", $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("No se pudo preparar la declaración: " . $query);
            }
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Error al ejecutar la declaración: " . $e->getMessage());
        }
    }
}

?>

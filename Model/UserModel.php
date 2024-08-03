<?php

require_once PROJECT_ROOT_PATH . "/../Model/Database.php";

class UserModel extends Database
{
    public function getUsers($limit)
    {
        // Usando named placeholders en lugar de signos de interrogación
        $sql = "SELECT * FROM usuario ORDER BY documento ASC LIMIT :limit";
        // Usando un array asociativo para los parámetros
        return $this->select($sql, ['limit' => $limit]);
    }

    public function deleteUser($documento)
    {
        // Usando named placeholders en lugar de signos de interrogación
        $sql = "DELETE FROM usuario WHERE documento = :documento";
        return $this->delete($sql, ['documento' => $documento]);
    }

    public function insertUser($documento, $nombre, $apellido, $usuario, $contrasenia)
    {
        // Verificar si el documento ya existe
        $existingUser = $this->getOneUser($documento);
        if (!empty($existingUser)) {
            throw new Exception('El documento ya está registrado.');
        }

        $sql = "INSERT INTO usuario (documento, nombre, apellido, usuario, contrasenia) VALUES (:documento, :nombre, :apellido, :usuario, :contrasenia) RETURNING documento";
        $params = [
            'documento' => $documento,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'usuario' => $usuario,
            'contrasenia' => $contrasenia
        ];
        return $this->insert($sql, $params);
    }


    public function getOneUser($documento)
    {
        // Usando named placeholders en lugar de signos de interrogación
        $sql = "SELECT * FROM usuario WHERE documento = :documento";
        return $this->select($sql, ['documento' => $documento]);
    }

    public function updateUser($documento, $nombre, $apellido, $usuario, $contrasenia)
    {
        // Usando named placeholders en lugar de signos de interrogación
        $sql = "UPDATE usuario SET nombre = :nombre, apellido = :apellido, usuario = :usuario, contrasenia = :contrasenia WHERE documento = :documento";
        $params = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'usuario' => $usuario,
            'contrasenia' => $contrasenia,
            'documento' => $documento
        ];
        return $this->update($sql, $params);
    }

    public function login($usuario, $contrasenia)
    {
        // Usando named placeholders en lugar de signos de interrogación
        $sql = "SELECT * FROM usuario WHERE usuario = :usuario AND contrasenia = :contrasenia limit 1";
        $params = [
            'usuario' => $usuario,
            'contrasenia' => $contrasenia
        ];
        return $this->select($sql, $params);
    }
}

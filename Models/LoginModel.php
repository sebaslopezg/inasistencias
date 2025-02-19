<?php

class LoginModel extends Mysql
{

    private $intIdUsuario;
    private $strUsuario;
    private $strToken;

    public function __construct()
    {
        parent::__construct();
    }

    public function loginUser(string $usuario, string $password)
    {
        $this->strUsuario = $usuario;
        $this->strPassword = $password;
        $sql = "SELECT idUsuarios, status FROM usuario WHERE
        documento = '{$this->strUsuario}' AND
        password = '{$this->strPassword}' AND
        status != 0";

        $request = $this->select($sql);
        return $request;
    }

    public function sessionLogin(int $idUser)
    {
        $this->intUsuario = $idUser;

        $sql = "SELECT u.idUsuarios, 
        u.nombre,
        u.apellido,
        u.documento,
        u.telefono,
        u.correo,
        u.codigo,
        u.rol,
        u.status
        FROM usuario u
        WHERE u.idUsuarios = {$this->intUsuario}";

        $request = $this->select($sql);
        return $request;
    }

    public function insertRecuperacion($correo, $codigo)
    {
        $this->strCorreo = $correo;
        $this->codigo = $codigo;
        $sql = "INSERT INTO recuperacion(correo, codigo) VALUES(?,?)";
        $arrData = array($this->strCorreo, $this->codigo);
        $request_insert = $this->insert($sql, $arrData);
        $respuesta = $request_insert;

        return $respuesta;
    }
}

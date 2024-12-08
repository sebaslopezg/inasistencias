<?php

class UsuariosModel extends Mysql{

    public function __construct(){
        parent::__construct();
    }

    public function selectUsuarios(){
        $sql = "SELECT idUsuarios AS ID, CONCAT(u.nombre, ' ', u.apellido) AS nombre_completo, u.documento, u.telefono, u.correo, u.rol, u.status FROM usuario u WHERE u.status > 0";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectUsuariosById(int $idUsuario){
        $this->idUsuario = $idUsuario;
        $sql = "SELECT idUsuarios AS id, u.nombre, u.apellido, u.documento, u.telefono, u.genero, u.correo, u.codigo, u.rol, u.status FROM usuario u WHERE u.status > 0 AND idUsuarios = {$this->idUsuario}";
        $request = $this->select($sql);
        return $request;
    }

    public function insertUsuario(string $strNombre, string $strApellido, int $intDocumento, int $intTelefono, int $intGenero, string $strEmail, string $strCodigo, string $strRol, string $strFirma){
        $this->strNombre = $strNombre;
        $this->strApellido = $strApellido;
        $this->intDocumento = $intDocumento;
        $this->intTelefono = $intTelefono;
        $this->intGenero = $intGenero;
        $this->strEmail = $strEmail;
        $this->strCodigo = $strCodigo;
        $this->strFirma = $strFirma;
        $this->strRol = $strRol;
        $this->password = strval($this->intDocumento);
        
        $query_usuarios = "SELECT * FROM usuario WHERE documento = {$this->intDocumento} AND status > 0";

        $request = $this->select_all($query_usuarios);

        if (!empty($request)) {
            $respuesta = 'exist';
        }else{
            $query_insert = "INSERT INTO usuario(nombre, apellido, documento , telefono, genero, correo, codigo, firma, rol, password, status) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
            $arrData = array($this->strNombre, $this->strApellido, $this->intDocumento, $this->intTelefono, $this->intGenero, $this->strEmail, $this->strCodigo, $this->strFirma, $this->strRol, $this->password, 1);
            $reques_insert = $this->insert($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function updateUsuario(int $idUsuario, string $strNombre, string $strApellido, int $intDocumento, int $intTelefono, int $intGenero, string $strEmail, string $strCodigo, string $strRol, string $strFirma){
        $this->strNombre = $strNombre;
        $this->strApellido = $strApellido;
        $this->intDocumento = $intDocumento;
        $this->intTelefono = $intTelefono;
        $this->intGenero = $intGenero;
        $this->strEmail = $strEmail;
        $this->strCodigo = $strCodigo;
        $this->strFirma = $strFirma;
        $this->strRol = $strRol;
        $this->idUduario = $idUsuario;

        $sql = "SELECT * FROM usuario WHERE (documento = '{$this->intDocumento}' AND codigo = '{$this->strCodigo}' AND idUsuarios != {$this->idUduario})";

        $request = $this->select_all($sql);

        if (!empty($request)) {
            $respuesta = 'exist';
        }else{
            $query_insert = "UPDATE usuario SET nombre = ?, apellido = ?, telefono = ?, genero = ?, correo = ?, codigo = ?, firma = ?, rol = ? WHERE status > 0 AND idUsuarios = {$this->idUduario}";
            $arrData = array(
                $this->strNombre,
                $this->strApellido,
                $this->intTelefono,
                $this->intGenero,
                $this->strEmail,
                $this->strCodigo,
                $this->strFirma,
                $this->strRol
            );
            $reques_insert = $this->update($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function deleteUsuario(int $idUsuario){
        $this->idUsuario = $idUsuario;

        $sql = "UPDATE usuario SET status = ? WHERE idUsuarios = {$this->idUsuario}";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        return $request;
    }

}
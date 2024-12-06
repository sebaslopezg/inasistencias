<?php

class UsuariosModel extends Mysql{

    public function __construct(){
        parent::__construct();
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
        
        $query_usuarios = "SELECT * FROM usuario WHERE documento = {$this->intDocumento} OR codigo = '{$this->strCodigo}' AND status > 0";

        $request = $this->select_all($query_usuarios);

        if (!empty($request)) {
            $respuesta = 'exist';
        }else{
            $query_insert = "INSERT INTO usuario VALUES(?,?,?,?,?,?,?,?,?,?,?)";
            $arrData = array($this->strNombre, $this->strApellido, $this->intDocumento, $this->intTelefono, $this->intGenero, $this->strEmail, $this->strCodigo, $this->password, $this->strFirma, $this->strRol, 0);
            $reques_insert = $this->insert($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

}
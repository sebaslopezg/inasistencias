<?php

class IngresosModel extends Mysql{

    public function __construct(){
        parent::__construct();
    }

    public function selectCodigo(string $codigo){
        $this->strCodigo = $codigo;

        $sql = "SELECT idUsuarios, nombre, apellido FROM usuario WHERE codigo = '{$this->strCodigo}' AND status > 0;";
        $request = $this->select($sql);
        return $request;
    }

    public function insertAsistencia(int $idUsuarios){
        $this->intIdUsuarios = $idUsuarios;
        $sql = "INSERT INTO ingresos(fecha, hora, codigoNovedad, usuario_idUsuarios, status) VALUES (?,?,?,?,?)";
        $arrData = array(date('Y/m/d'),date('H:i'), 0,$this->intIdUsuarios, 1);
        $request = $this->insert($sql, $arrData);
        return $request;
    }

}
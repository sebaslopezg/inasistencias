<?php

class horarioModel extends Mysql{

    public function __construct(){
        parent::__construct();
    }

    public function selectHorarios(){
/*         $sql = "";
        $request = $this->select_all($sql);
        return $request; */
    }

    public function selectInstructorByName($nombreInstructor){
        $this->nombreInstructor = $nombreInstructor;
        $sql = "SELECT idUsuarios FROM usuario WHERE nombre = '{$this->nombreInstructor}'";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertHorario(string $fecha, string $horaInicio, string $horaFin, string $instructor){

        $this->strFecha = $fecha;
        $this->strHoraInicio = $horaInicio;
        $this->strHoraFin = $horaFin;
        $this->strInstructor = $instructor;
        
        $query_usuarios = "SELECT * FROM horario WHERE fechaInicio = '{$this->strFecha}' AND horaInicio = '{$this->strHoraInicio}' AND horaInicio = '{$this->strHoraFin}' AND status > 0";

        $request = $this->select_all($query_usuarios);

        if (!empty($request)) {
            $respuesta = 'exist';
        }else{
            $query_insert = "INSERT INTO horario(fechaInicio, horaInicio, horaFin, usuarioId,status) VALUES(?,?,?,?,?)";
            $arrData = array($this->strFecha, $this->strHoraInicio, $this->strHoraFin, $this->strInstructor, 1);
            $reques_insert = $this->insert($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

}
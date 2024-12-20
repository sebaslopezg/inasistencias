<?php

class horarioModel extends Mysql{

    public function __construct(){
        parent::__construct();
    }

    public function selectAllHorarios(){
        $sql = "SELECT h.idHorario AS ID, h.fechaInicio, h.horaInicio, h.horaFin, h.ficha, CONCAT(u.nombre, ' ', u.apellido) AS nombre FROM horario h INNER JOIN usuario u ON u.idUsuarios = h.usuarioId  WHERE h.status > 0";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectHorarios(int $ficha, string $fecha, string $horaInicio){
        $this->intFicha = $ficha;
        $this->strFecha = $fecha;
        $this->strHoraInicio = $horaInicio;

        $sql = "SELECT * FROM horario WHERE fechaInicio = '{$this->strFecha}' AND horaInicio = '{$this->strHoraInicio}' AND ficha = {$this->intFicha}";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectFicha($ficha){
        $this->intFicha = $ficha;

        $sql = "SELECT * FROM ficha WHERE numeroFicha = {$this->intFicha} AND status > 0;";
        $request = $this->select($sql);
        return $request;
    }

    public function selectInstructorByName($nombreInstructor){
        $this->nombreInstructor = $nombreInstructor;
        $this->arrNombreCompleto = explode(' ', $this->nombreInstructor);
        $this->nombre = $this->arrNombreCompleto[0];
        $this->lastElement = count($this->arrNombreCompleto)-1;
        $this->apellido = $this->arrNombreCompleto[$this->lastElement];

        $sql = "SELECT idUsuarios FROM usuario WHERE nombre like '{$this->nombre}%' AND apellido like '%{$this->apellido}' AND status > 0;";
        $request = $this->select($sql);
        return $request;
    }

    public function insertHorario(int $ficha, string $fecha, string $horaInicio, string $horaFin, string $instructor){

        $this->intFicha = $ficha;
        $this->strFecha = $fecha;
        $this->strHoraInicio = $horaInicio;
        $this->strHoraFin = $horaFin;
        $this->strInstructor = $instructor;
        
        $query_usuarios = "SELECT * FROM horario WHERE fechaInicio = '{$this->strFecha}' AND horaInicio = '{$this->strHoraInicio}' AND horaInicio = '{$this->strHoraFin}' AND status > 0";

        $request = $this->select_all($query_usuarios);

        if (!empty($request)) {
            $respuesta = 'exist';
        }else{
            $query_insert = "INSERT INTO horario(fechaInicio, horaInicio, horaFin, ficha, usuarioId,status) VALUES(?,?,?,?,?,?)";
            $arrData = array($this->strFecha, $this->strHoraInicio, $this->strHoraFin, $this->intFicha, $this->strInstructor, 1);
            $reques_insert = $this->insert($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function deleteHorario(int $id){
        $this->intId = $id;
        $sql = "UPDATE horario SET status = ? WHERE idHorario = {$this->intId}";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        return $request;
    }

}
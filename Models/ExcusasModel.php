<?php

class ExcusasModel extends mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function selectInasistencias($idUsuario)
    {
        $this->$idUsuario = $idUsuario;
        $sql = "SELECT i.idInasistencias as Id,concat(i.fecha,' ', i.hora) as fechaCompleta,i.fecha,concat(u.nombre,' ',u.apellido) as nombreCompleto,i.idInstructor,i.status,usuario_idUsuarios FROM inasistencias i JOIN usuario u on u.idUsuarios = i.usuario_idUsuarios WHERE i.codigoNovedad = 0 and i.status > 0 AND u.idUsuarios = {$this->$idUsuario};";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectUsuario($idUsuario)
    {
        $this->$idUsuario = $idUsuario;
        $sql = "SELECT u.idUsuarios,u.rol FROM usuario u WHERE u.idUsuarios = {$this->$idUsuario};";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectInasistenciasPorInstru($idInstructor)
    {
        $this->$idInstructor = $idInstructor;
        $sql = "SELECT e.idExcusas,i.idInasistencias,e.observacion,e.status,i.status as estIna,i.fecha as fechaIna,e.uriArchivo,concat(i.fecha,' ', i.hora) as feIna,concat(u.nombre, ' ',u.apellido)as nombreCompleto,f.nombre as ficha,f.numeroFicha,e.fecha as feExc FROM excusas e JOIN inasistencias i on i.idInasistencias = e.inasistencias_idInasistencias JOIN usuario u on u.idUsuarios = i.usuario_idUsuarios join usuario_has_ficha p on p.usuario_idUsuarios = u.idUsuarios JOIN ficha f on f.idFicha = p.ficha_idFicha WHERE e.status > 0 and (i.status = 1 OR i.status = 2 OR i.status = 3) AND e.idInstructor = {$this->$idInstructor};";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectFilePorId($id)
    {
        $this->$id = $id;
        $sql = "SELECT e.uriArchivo FROM excusas e WHERE e.idExcusas = {$this->$id};";
        $request = $this->select($sql);
        return $request;
    }
    public function selectIdExcusa(int $idInasistencia)
    {
        $this->$idInasistencia = $idInasistencia;
        $sql = "SELECT e.idExcusas as excusaId from excusas e JOIN inasistencias i on i.idInasistencias = e.inasistencias_idInasistencias WHERE e.status > 0 and e.inasistencias_idInasistencias = '{$this->$idInasistencia}'";
        $request = $this->select($sql);
        return $request;
    }
    public function selectObsId(int $excusaId)
    {
        $this->$excusaId = $excusaId;
        $sql = "SELECT e.observacion from excusas e WHERE e.status > 0 and e.idExcusas = '{$this->$excusaId}'";
        $request = $this->select($sql);
        return $request;
    }
    public function validacionExcusa(int $idInasistencia)
    {
        $this->$idInasistencia = $idInasistencia;
        $sql = "SELECT COUNT(*) AS total FROM excusas e JOIN inasistencias i on i.idInasistencias = e.inasistencias_idInasistencias WHERE e.status > 0 and e.inasistencias_idInasistencias = '{$this->$idInasistencia}'";
        $request = $this->select($sql);
        return $request;
    }

    public function selectInasistenciaById(int $idInasistencia)
    {
        $this->$idInasistencia = $idInasistencia;
        $sql = "SELECT i.idInasistencias as idIna,i.idInstructor,i.status as estado,u.idUsuarios as idUsu FROM inasistencias i JOIN usuario u on u.idUsuarios = i.usuario_idUsuarios WHERE i.codigoNovedad = 0 and i.idInasistencias = '{$this->$idInasistencia}'";
        $request = $this->select($sql);
        return $request;
    }

    public function selectInstructor(int $id)
    {
        $this->id = $id;
        $sql = "SELECT concat(u.nombre,' ',u.apellido) as nombreIntru FROM usuario u WHERE u.idUsuarios = '{$this->id}' and rol = 'INSTRUCTOR'";
        $request = $this->select($sql);
        return $request;
    }

    public function insertExcusas(int $idInasistencia, int $idUsuario, int $idInstructor, string $uriExcusa)
    {
        $this->idInasistencia = $idInasistencia;
        $this->idUsuario = $idUsuario;
        $this->idInstructor = $idInstructor;
        $this->uriExcusa = $uriExcusa;

        $query_insert = "INSERT INTO excusas(inasistencias_idInasistencias,usuario_idUsuarios,idInstructor,uriArchivo,status) VALUES(?,?,?,?,?)";
        $arrData = array($this->idInasistencia, $this->idUsuario, $this->idInstructor,$this->uriExcusa, 1);
        $request_insert = $this->insert($query_insert, $arrData);
        $respuesta = $request_insert;

        return $respuesta;
    }

    public function selectExcusasId(int $idExcusa)
    {
        $this->$idExcusa = $idExcusa;
        $sql = "SELECT * FROM excusas e where e.status > 0 and e.idExcusas = '{$this->$idExcusa}'";
        $request = $this->select($sql);
        return $request;
    }

    public function aceptarExcusa(int $idInasistencia)
    {
        $this->idInasistencia = $idInasistencia;
        $this->status = 2;

        $sql = "UPDATE inasistencias set status = ? where idInasistencias = ?";
        $arrData = array($this->status,$this->idInasistencia);
        $reques_insert = $this->update($sql, $arrData);
        $respuesta = $reques_insert;

        return $respuesta;
    }

    public function denegarExcusa(int $idInasistencia)
    {
        $this->idInasistencia = $idInasistencia;
        $this->status = 3;

        $sql = "UPDATE inasistencias set status = ? where idInasistencias = ?";
        $arrData = array($this->status,$this->idInasistencia);
        $reques_insert = $this->update($sql, $arrData);
        $respuesta = $reques_insert;

        return $respuesta;
    }

    public function updateExcusa($idExcusa, $uriExcusa)
    {
        $this->intIdexcusa = $idExcusa;
        $this->uriExcusa = $uriExcusa;

        $query_insert = "UPDATE excusas set uriArchivo = ?, fecha = now(), observacion = null WHERE status > 0 AND idExcusas = {$this->intIdexcusa}";
        $arrData = array($this->uriExcusa);
        $reques_insert = $this->update($query_insert, $arrData);
        $respuesta = $reques_insert;

        return $respuesta;
    }

    public function cambiarEstIna($idIna)
    {
        $this->idIna = $idIna;
        $sql = "UPDATE inasistencias i SET i.status = 1 WHERE i.idInasistencias =  {$this->idIna};";
        $request = $this->select($sql);
        return $request;
    }

    public function updateObservacion($idExcusa, $observacion)
    {
        $this->intIdexcusa = $idExcusa;
        $this->observacion = $observacion;

        $query_insert = "UPDATE excusas set observacion = ? WHERE status > 0 AND idExcusas = {$this->intIdexcusa}";
        $arrData = array($this->observacion);
        $reques_insert = $this->update($query_insert, $arrData);
        $respuesta = $reques_insert;

        return $respuesta;
    }
}

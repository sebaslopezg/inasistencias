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
        $sql = "SELECT i.idInasistencias as Id,concat(i.fecha,' ', i.hora) as fechaCompleta,concat(u.nombre,' ',u.apellido) as nombreCompleto,i.idInstructor,i.status FROM inasistencias i JOIN usuario u on u.idUsuarios = i.usuario_idUsuarios WHERE i.codigoNovedad = 0 and i.status > 0 AND u.idUsuarios = {$this->$idUsuario};";
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
        $sql = "SELECT e.idExcusas,i.idInasistencias,e.status,e.uriArchivo,i.fecha as feIna,concat(u.nombre, ' ',u.apellido)as nombreCompleto,f.nombre as ficha,f.numeroFicha,e.fecha as feExc FROM excusas e JOIN inasistencias i on i.idInasistencias = e.inasistencias_idInasistencias JOIN usuario u on u.idUsuarios = i.usuario_idUsuarios join usuario_has_ficha p on p.usuario_idUsuarios = u.idUsuarios JOIN ficha f on f.idFicha = p.ficha_idFicha WHERE e.status > 0 and i.status = 1 and e.idInstructor = {$this->$idInstructor};";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectFilePorId($id)
    {
        $this->$id = $id;
        $sql = "SELECT e.uriArchivo,e.nomArchivo FROM excusas e WHERE e.idExcusas = {$this->$id};";
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

    public function selectExcusaId(int $idExcusa)
    {
        $this->$idExcusa = $idExcusa;
        $sql = "SELECT * FROM excusas where idExcusas = '{$this->$idExcusa}'";
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

    public function insertExcusas(int $idInasistencia, int $idUsuario, int $idInstructor, string $nameExcusa, string $uriExcusa)
    {
        $this->idInasistencia = $idInasistencia;
        $this->idUsuario = $idUsuario;
        $this->idInstructor = $idInstructor;
        $this->nameExcusa = $nameExcusa;
        $this->uriExcusa = $uriExcusa;

        $query_insert = "INSERT INTO excusas(inasistencias_idInasistencias,usuario_idUsuarios,idInstructor,nomArchivo,uriArchivo,status) VALUES(?,?,?,?,?,?)";
        $arrData = array($this->idInasistencia, $this->idUsuario, $this->idInstructor,$this->nameExcusa,$this->uriExcusa, 1);
        $request_insert = $this->insert($query_insert, $arrData);
        $respuesta = $request_insert;

        return $respuesta;
    }

    public function deleteExcusa(int $idExcusa)
    {
        $this->intIdExcusa = $idExcusa;

        $sql = "UPDATE excusas set status = ? where idExcusas = {$this->intIdExcusa}";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
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

        $query_insert = "UPDATE excusas set uriArchivo = ?, fecha = now() WHERE status > 0 AND idExcusas = {$this->intIdexcusa}";
        $arrData = array($this->uriExcusa);
        $reques_insert = $this->update($query_insert, $arrData);
        $respuesta = $reques_insert;

        return $respuesta;
    }
}

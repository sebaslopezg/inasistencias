<?php

class ExcusasModel extends mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function selectExcusas()
    {
        $sql = "SELECT i.idInasistencias as Id,concat(i.fecha,' ', i.hora) as fechaCompleta,concat(u.nombre,' ',u.apellido) as nombreCompleto,i.idInstructor,i.status FROM inasistencias i JOIN usuario u on u.idUsuarios = i.usuario_idUsuarios WHERE i.codigoNovedad = 0;";
        $request = $this->select_all($sql);
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

    public function insertExcusas(int $idInasistencia, int $idUsuario, int $idInstructor, string $uriExcusa)
    {
        $this->idInasistencia = $idInasistencia;
        $this->idUsuario = $idUsuario;
        $this->idInstructor = $idInstructor;
        $this->uriExcusa = $uriExcusa;

        $query_insert = "INSERT INTO excusas(inasistencias_idInasistencias,usuario_idUsuarios,idInstructor,uriArchivo,status) VALUES(?,?,?,?,?)";
        $arrData = array($this->idInasistencia, $this->idUsuario, $this->idInstructor, $this->uriExcusa, 1);
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

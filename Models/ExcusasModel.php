<?php

class ExcusasModel extends mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function selectExcusas()
    {
        $sql = "SELECT i.idInasistencias as Id,concat(i.fecha,' ', i.hora) as fechaCompleta,i.codigoNovedad,concat(u.nombre,' ',u.apellido) as nombreCompleto,idInstructor,i.status FROM inasistencias i JOIN usuario u on u.idUsuarios = i.usuario_idUsuarios WHERE i.codigoNovedad = 0";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectInasistenciaById(int $idInasistencia)
    {
        $this->$idInasistencia = $idInasistencia;
        $sql = "SELECT i.idInasistencias as idIna,i.idInstructor,i.status,u.idUsuarios as idUsu FROM inasistencias i JOIN usuario u on u.idUsuarios = i.usuario_idUsuarios WHERE i.codigoNovedad = 0 and i.idInasistencias = '{$this->$idInasistencia}'";
        $request = $this->select($sql);
        return $request;
    }

    public function selectInstructor(int $id){
        $this->id = $id;
        $sql = "SELECT concat(u.nombre,' ',u.apellido) as nombreIntru FROM usuario u WHERE u.idUsuarios = '{$this->id}' and rol = 'Instructor'";
        $request = $this->select($sql);
        return $request;
    }

    public function insertExcusas(int $idInasistencia,int $idUsuario,int $idInstructor,string $uriExcusa){
        $this->idInasistencia = $idInasistencia;
        $this->idUsuario = $idUsuario;
        $this->idInstructor = $idInstructor;
        $this->uriExcusa = $uriExcusa;

        $query_insert ="INSERT INTO excusas(inasistencias_idInasistencias,usuario_idUsuarios,idInstructor,uriArchivo,status) VALUES(?,?,?,?,?)";
        $arrData = array($this->idInasistencia,$this->idUsuario,$this->idInstructor,$this->uriExcusa,1);
        $request_insert = $this->insert($query_insert,$arrData);
        $respuesta = $request_insert;
        
        return$respuesta;
    }
   
}

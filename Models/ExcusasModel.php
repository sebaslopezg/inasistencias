<?php

class ExcusasModel extends mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function selectExcusas()
    {
        $sql = "SELECT i.idInasistencias as id,concat(i.fecha,' ', i.hora) as fechaCompleta,i.codigoNovedad,concat(u.nombre,' ',u.apellido) as nombreCompleto,i.status FROM inasistencias i JOIN usuario u on u.idUsuarios = i.usuario_idUsuarios WHERE i.codigoNovedad = 0";
        $request = $this->select_all($sql);
        return $request;
    }

    /* public function selectInstructoresPorFicha($idFicha)
    {
        $sql = "SELECT usuario.idUsuarios, concat(nombre,' ',apellido)as nomCompleto FROM usuario JOIN usuario_has_ficha has on has.usuario_idUsuarios = idUsuarios WHERE rol = 'Instructor' AND status > 0 AND has.ficha_idFicha = $idFicha;";
        $request = $this->select_all($sql);
        return $request;
    } */

/*     public function selectInstructores()
    {
        $sql = "SELECT usuario.idUsuarios, concat(nombre,' ',apellido)as nomCompleto FROM usuario JOIN usuario_has_ficha has on has.usuario_idUsuarios = idUsuarios WHERE rol = 'Instructor' AND status > 0 AND has.ficha_idFicha = 1;";
        $request = $this->select_all($sql);
        return $request;
    } */

    public function selectInstructores()
    {
        $sql = "SELECT usuario.idUsuarios, concat(nombre,' ',apellido)as nomCompleto FROM usuario JOIN usuario_has_ficha has on has.usuario_idUsuarios = idUsuarios WHERE rol = 'Instructor' AND status > 0 AND has.ficha_idFicha = 1;";
        $request = $this->select_all($sql);
        return $request;
    }
   
}

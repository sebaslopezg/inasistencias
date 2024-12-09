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
}

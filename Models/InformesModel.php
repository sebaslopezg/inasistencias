<?php

class InformesModel extends Mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function selectFicha()
    {
        $sql = "SELECT ficha.nombre as nombre_ficha,ficha.numeroFicha , idFicha AS id, usuario_has_ficha.status FROM usuario_has_ficha
        INNER JOIN usuario ON usuario.idUsuarios = usuario_has_ficha.usuario_idUsuarios 
        INNER JOIN ficha ON ficha.idFicha = usuario_has_ficha.ficha_idFicha 
        WHERE usuario_has_ficha.usuario_idUsuarios = 4 AND ficha.status > 0";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectInfoAprendicesById(int $idFicha)
    {
        $this->idFicha = $idFicha;
        $sql = "SELECT CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo, usuario.correo,usuario.idUsuarios as id, COUNT(usuario_idUsuarios) as faltas 
        FROM inasistencias
        INNER JOIN usuario ON usuario.idUsuarios = inasistencias.usuario_idUsuarios
        WHERE inasistencias.idInstructor = 2 AND usuario.status = 1 AND usuario.rol = 'APRENDIZ'
        AND  EXISTS ( SELECT 1 FROM usuario_has_ficha 
        WHERE usuario_has_ficha.usuario_idUsuarios = usuario.idUsuarios 
        AND usuario_has_ficha.ficha_idFicha = {$this->idFicha} ) GROUP BY inasistencias.usuario_idUsuarios ORDER BY COUNT(usuario_idUsuarios) DESC ";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectFechasFaltas(int $idAprendiz)
    {
        $this->idAprendiz = $idAprendiz;
        $sql = "SELECT CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo, inasistencias.fecha
        FROM inasistencias
        INNER JOIN usuario ON usuario.idUsuarios = inasistencias.usuario_idUsuarios
        WHERE inasistencias.usuario_idUsuarios = {$this->idAprendiz} AND usuario.status = 1 AND usuario.rol = 'APRENDIZ';";
        $request = $this->select_all($sql);
        return $request;
    }
}

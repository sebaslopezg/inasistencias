<?php

class InformesModel extends Mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function selectFicha(int $idInstructor)
    {
        $this->idInstructor = $idInstructor;
        $sql = "SELECT ficha.nombre as nombre_ficha,ficha.numeroFicha , idFicha AS id, usuario_has_ficha.status FROM usuario_has_ficha
        INNER JOIN usuario ON usuario.idUsuarios = usuario_has_ficha.usuario_idUsuarios 
        INNER JOIN ficha ON ficha.idFicha = usuario_has_ficha.ficha_idFicha 
        WHERE usuario_has_ficha.usuario_idUsuarios = {$this->idInstructor} AND ficha.status > 0";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectInfoAprendicesById(int $idFicha, int $idInstructor)
    {
        $this->idFicha = $idFicha;
        $this->idInstructor = $idInstructor;
        $sql = "SELECT CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo, usuario.correo,usuario.idUsuarios as id, COUNT(usuario_idUsuarios) as faltas 
        FROM inasistencias
        INNER JOIN usuario ON usuario.idUsuarios = inasistencias.usuario_idUsuarios
        WHERE inasistencias.idInstructor = {$this->idInstructor} AND usuario.status = 1 AND usuario.rol = 'APRENDIZ'
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

    public function selectFechaHorario(int $numeroFicha, int $idInstructor)
    {
        $this->numeroFicha = $numeroFicha;
        $this->idInstructor = $idInstructor;
        $sql = "SELECT  CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo, horario.fechaInicio,  Ficha.nombre,Ficha.numeroFicha
        FROM horario 
        INNER JOIN usuario ON usuario.idUsuarios = horario.usuarioId 
        INNER JOIN usuario_has_ficha ON usuario_has_ficha.usuario_idUsuarios = horario.usuarioId 
        INNER JOIN Ficha ON Ficha.idFicha = usuario_has_ficha.ficha_idFicha 
        WHERE horario.usuarioId = {$this->idInstructor} AND ficha.numeroFicha = {$this->numeroFicha} AND  horario.status = 1";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectAprendices(int $idFicha)
    {


        $this->idFicha = $idFicha;
        $request = array();
        $sql = "SELECT CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo , usuario.idUsuarios as id
        FROM usuario_has_ficha INNER JOIN usuario ON usuario.idUsuarios = usuario_has_ficha.usuario_idUsuarios
         WHERE usuario_has_ficha.ficha_idFicha = {$this->idFicha} AND usuario.status = 1 AND usuario.rol = 'APRENDIZ'";
        $aprendices =  $this->select_all($sql);

        return $aprendices;
    }
}

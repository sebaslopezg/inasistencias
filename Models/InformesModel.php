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
        AND inasistencias.status = 1 OR inasistencias.status = 3
        AND  EXISTS ( SELECT 1 FROM usuario_has_ficha 
        WHERE usuario_has_ficha.usuario_idUsuarios = usuario.idUsuarios 
        AND usuario_has_ficha.ficha_idFicha = {$this->idFicha} ) 
        GROUP BY inasistencias.usuario_idUsuarios
        ORDER BY COUNT(usuario_idUsuarios) DESC ";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectFechasFaltas(int $idAprendiz)
    {
        $this->idAprendiz = $idAprendiz;
        $sql = "SELECT CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo, inasistencias.fecha FROM inasistencias
        INNER JOIN usuario ON usuario.idUsuarios = inasistencias.usuario_idUsuarios
        WHERE  inasistencias.usuario_idUsuarios = {$this->idAprendiz} AND usuario.status = 1 AND inasistencias.status = 1 OR inasistencias.status = 3 AND usuario.rol = 'APRENDIZ' AND EXISTS ( SELECT 1 FROM inasistencias WHERE usuario.idUsuarios = {$this->idAprendiz});";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectFechaHorario(string $numeroFicha, string $idInstructor, string $Fecha)
    {
        $this->numeroFicha = $numeroFicha;
        $this->Fecha = $Fecha;
        $this->idInstructor = $idInstructor;
        $sql = "SELECT  CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo, horario.fechaInicio,  Ficha.nombre,Ficha.numeroFicha
        FROM horario 
        INNER JOIN usuario ON usuario.idUsuarios = horario.usuarioId 
        INNER JOIN usuario_has_ficha ON usuario_has_ficha.usuario_idUsuarios = horario.usuarioId 
        INNER JOIN Ficha ON Ficha.idFicha = usuario_has_ficha.ficha_idFicha 
        WHERE  horario.fechaInicio >= '{$this->Fecha}-01' 
        AND horario.fechaInicio <= '{$this->Fecha}-31' 
        AND horario.usuarioId = {$this->idInstructor} 
        AND ficha.numeroFicha = {$this->numeroFicha} 
        AND  horario.status = 1";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectInfoAprendiz(int $idInstructor, string $idFicha, string $Fecha)
    {
        $this->idFicha = $idFicha;
        $this->Fecha = $Fecha;
        $this->idInstructor = $idInstructor;
        $sql = "SELECT CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo , inasistencias.fecha, inasistencias.status 
        FROM inasistencias INNER JOIN usuario ON usuario.idUsuarios = inasistencias.usuario_idUsuarios
        WHERE inasistencias.fecha >= '{$this->Fecha}-01' 
        AND inasistencias.fecha <= '{$this->Fecha}-31' 
        AND (usuario.status = 1 OR inasistencias.status = 3 )
        AND usuario.rol = 'APRENDIZ'
        AND inasistencias.idInstructor = {$this->idInstructor}
        AND EXISTS ( SELECT 1 FROM usuario_has_ficha 
        WHERE usuario_has_ficha.usuario_idUsuarios = usuario.idUsuarios
        AND usuario_has_ficha.ficha_idFicha = {$this->idFicha})
        ORDER BY inasistencias.fecha ASC
        ";
        $aprendices =  $this->select_all($sql);
        return $aprendices;
    }
    public function selectNombreAprendices(int $idFicha)
    {

        $this->idFicha = $idFicha;
        $sql = "SELECT  CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo
        FROM inasistencias INNER JOIN usuario ON usuario.idUsuarios = inasistencias.usuario_idUsuarios
        WHERE usuario.status = 1 AND usuario.rol = 'APRENDIZ' 
        AND EXISTS ( SELECT 1 FROM usuario_has_ficha 
        WHERE usuario_has_ficha.usuario_idUsuarios = usuario.idUsuarios 
        AND usuario_has_ficha.ficha_idFicha = {$this->idFicha} )
        GROUP BY usuario.nombre
        ORDER BY inasistencias.fecha ASC";
        $aprendices =  $this->select_all($sql);

        return $aprendices;
    }
}

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
        $sql = "SELECT ficha.nombre as nombre_ficha, CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo, usuario.correo,usuario.idUsuarios as id FROM usuario_has_ficha
        INNER JOIN usuario ON usuario.idUsuarios = usuario_has_ficha.usuario_idUsuarios 
        INNER JOIN ficha ON ficha.idFicha = usuario_has_ficha.ficha_idFicha 
        WHERE usuario_has_ficha.ficha_idFicha = {$this->idFicha}  AND usuario.status = 1 AND usuario.rol = 'APRENDIZ'";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectInstDispo(int $idFicha)
    {
        $this->idFicha = $idFicha;
        $sql = "SELECT CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo, usuario.idUsuarios FROM usuario WHERE rol = 'INSTRUCTOR' AND NOT EXISTS ( SELECT 1 FROM usuario_has_ficha WHERE usuario_has_ficha.usuario_idUsuarios = usuario.idUsuarios AND usuario_has_ficha.ficha_idFicha = {$this->idFicha} );";
        $request = $this->select_all($sql);
        return $request;
    }
}

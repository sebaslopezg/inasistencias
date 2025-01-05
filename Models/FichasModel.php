<?php

class FichasModel extends Mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function selectFicha()
    {
        $sql = "SELECT idFicha AS id,  f.nombre, f.numeroFicha, f.status FROM Ficha f WHERE f.status > 0";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectFichaById(int $idFicha)
    {
        $this->idFicha = $idFicha;
        $sql = "SELECT idFicha AS id, f.nombre, f.numeroFicha, f.status FROM Ficha f WHERE f.status > 0 AND idFicha = {$this->idFicha}";
        $request = $this->select($sql);
        return $request;
    }
    public function selectInfoInstructoresById(int $idFicha)
    {
        $this->idFicha = $idFicha;
        $sql = "SELECT ficha.nombre as nombre_ficha, CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo, usuario.correo,usuario.idUsuarios FROM usuario_has_ficha
        INNER JOIN usuario ON usuario.idUsuarios = usuario_has_ficha.usuario_idUsuarios 
        INNER JOIN ficha ON ficha.idFicha = usuario_has_ficha.ficha_idFicha 
        WHERE usuario_has_ficha.ficha_idFicha = {$this->idFicha}  AND usuario.status = 1 AND usuario.rol = 'INSTRUCTOR'";
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
    public function selectInfoAprendicesFichaById(int $idFicha)
    {
        $this->idFicha = $idFicha;
        $sql = "SELECT  CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo, usuario.correo FROM usuario_has_ficha
        INNER JOIN usuario ON usuario.idUsuarios = usuario_has_ficha.usuario_idUsuarios 
        WHERE usuario_has_ficha.ficha_idFicha = {$this->idFicha}  AND usuario.status = 1 AND usuario.rol = 'APRENDIZ'";
        $request = $this->select_all($sql);
        return $request;
    }
    public function insertFicha(string $strNombreFicha, int $intNumeroFicha)
    {
        $this->strNombreFicha = $strNombreFicha;

        $this->intNumeroFicha = $intNumeroFicha;

        $query_usuarios = "SELECT * FROM Ficha WHERE numeroFicha = {$this->intNumeroFicha} AND status > 0";

        $request = $this->select_all($query_usuarios);

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {
            $query_insert = "INSERT INTO Ficha(nombre, numeroFicha, status) VALUES(?,?,?)";
            $arrData = array($this->strNombreFicha, $this->intNumeroFicha, 1);
            $reques_insert = $this->insert($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function insertInstructor(int $intIdFicha, int $intIdInstru)
    {
        $this->intIdFicha = $intIdFicha;

        $this->intIdInstru = $intIdInstru;

        $query_insert = "INSERT INTO usuario_has_ficha (usuario_idUsuarios, ficha_idFicha, status ) VALUES (?,?,1)";
        $arrData = array($this->intIdInstru, $this->intIdFicha);

        $reques_insert = $this->insert($query_insert, $arrData);

        $respuesta = $reques_insert;
        return $respuesta;
    }
    public function updateFicha(string $strNombreFicha, int $intIdFicha, int $intStatus)
    {
        $this->strNombreFicha = $strNombreFicha;
        $this->intIdFicha = $intIdFicha;
        $this->intStatus = $intStatus;
        /*    $sql = "SELECT * FROM usuario WHERE (documento = '{$this->intDocumento}' AND codigo = '{$this->strCodigo}' AND idUsuarios != {$this->idUduario})";
        $request = $this->select_all($sql); */

        if (!empty($request)) {
            $respuesta = 'exist';
        } else {
            $query_insert = "UPDATE ficha SET nombre = ?, status = ? WHERE status > 0 AND idFicha = ?";
            $arrData = array(
                $this->strNombreFicha,
                $this->intStatus,
                $this->intIdFicha
            );
            $reques_insert = $this->update($query_insert, $arrData);
            $respuesta = $reques_insert;
        }

        return $respuesta;
    }

    public function updateInstructor(int $idFicha, int $intIdInstru, int $status)
    {
        $this->intIdInstru = $intIdInstru;
        $this->idFicha = $idFicha;
        $this->status = $status;

        $query_insert = "UPDATE usuario_has_ficha SET status = ? WHERE usuario_idUsuarios  = ? AND ficha_idFicha = ? ";
        $arrData = array(
            $this->status,
            $this->intIdInstru,
            $this->idFicha

        );
        $reques_insert = $this->update($query_insert, $arrData);
        $respuesta = $reques_insert;

        return $respuesta;
    }
    public function deleteFicha(int $idFicha)
    {
        $this->idFicha = $idFicha;
        $sql = "UPDATE ficha SET status = ? WHERE idFicha = {$this->idFicha}";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        return $request;
    }
}

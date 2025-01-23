<?php

class ExcepcionesModel extends mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function selectExcepciones()
    {
        $sql = "SELECT e.idExcepciones, e.descripcion, e.fechaInicio, e.fechaFin, CONCAT(u.nombre, ' ', u.apellido) AS nombreUsu,CONCAT(f.nombre, ' - ',f.numeroFicha) AS nombreFicha, e.tipoExcepcion, e.status FROM excepciones e LEFT JOIN usuario u ON u.idUsuarios = e.usuarioId LEFT JOIN ficha f ON f.idFicha = e.fichaId WHERE e.status > 0 ORDER BY e.tipoExcepcion ASC";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectExcepcionById(int $idExcepcion)
    {
        $this->idExcepcion = $idExcepcion;
        $sql = "SELECT * FROM excepciones where status > 0 and idExcepciones = '{$this->idExcepcion}'";
        $request = $this->select($sql);
        return $request;
    }
    public function selectFicha()
    {
        $sql = "SELECT idFicha,nombre,numeroFicha from ficha where status > 0";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectAprendiz($idFicha)
    {
        $this->$idFicha = $idFicha;
        $sql = "SELECT CONCAT(u.nombre, ' ',u.apellido) as nombre,u.documento,p.usuario_idUsuarios FROM usuario_has_ficha p JOIN usuario u ON u.idUsuarios = p.usuario_idUsuarios WHERE u.status > 0 AND p.ficha_idFicha =  '{$this->$idFicha}'";
        $request = $this->select_all($sql);
        return $request;
    }
    public function insertExcepcion(int $txtTipoExcepcion, ?int $txtFicha, ?int $txtAprendiz, string $txtFechaInicio, string $txtFechaFin, string $txtMotivo)
    {
        $this->txtTipoExcepcion = $txtTipoExcepcion;
        $this->txtFicha = $txtFicha;
        $this->txtAprendiz = $txtAprendiz;
        $this->txtFechaInicio = $txtFechaInicio;
        $this->txtFechaFin = $txtFechaFin;
        $this->txtMotivo = $txtMotivo;


        if ($this->txtTipoExcepcion == 3) {

            $queryExcep = "SELECT * FROM excepciones WHERE status > 0 and tipoExcepcion = 3 
            and (
                (fechaInicio <= '{$this->txtFechaInicio}' AND fechaFin >= '{$this->txtFechaInicio}') 
                OR
                (fechaInicio <= '{$this->txtFechaFin}' AND fechaFin >= '{$this->txtFechaFin}') 
                OR
                (fechaInicio >= '{$this->txtFechaInicio}' AND fechaFin <= '{$this->txtFechaFin}')
            ) ";

            $requestGlobal = $this->select_all($queryExcep);
        } elseif ($this->txtTipoExcepcion == 2) {

            $queryExcep = "SELECT * FROM excepciones WHERE status > 0 and tipoExcepcion = 2 and fichaId = {$this->txtFicha}
            and (
                (fechaInicio <= '{$this->txtFechaInicio}' AND fechaFin >= '{$this->txtFechaInicio}') 
                OR
                (fechaInicio <= '{$this->txtFechaFin}' AND fechaFin >= '{$this->txtFechaFin}') 
                OR
                (fechaInicio >= '{$this->txtFechaInicio}' AND fechaFin <= '{$this->txtFechaFin}')
            ) ";

            $requestFicha = $this->select_all($queryExcep);
        } elseif ($this->txtTipoExcepcion == 1) {

            $queryExcep = "SELECT * FROM excepciones WHERE status > 0 and tipoExcepcion = 3 and fichaId = {$this->txtFicha} and usuarioId = {$this->txtAprendiz}
            and (
                (fechaInicio <= '{$this->txtFechaInicio}' AND fechaFin >= '{$this->txtFechaInicio}') 
                OR
                (fechaInicio <= '{$this->txtFechaFin}' AND fechaFin >= '{$this->txtFechaFin}') 
                OR
                (fechaInicio >= '{$this->txtFechaInicio}' AND fechaFin <= '{$this->txtFechaFin}')
            ) ";

            $requestAprendiz = $this->select_all($queryExcep);
        }


        if (!empty($requestGlobal)) {
            $respuesta = 'existGlobal';
        } else if (!empty($requestFicha)) {
            $respuesta = 'existFicha';
        } else if (!empty($requestAprendiz)) {
            $respuesta = 'existAprendiz';
        } else {
            $query_insert = "INSERT INTO excepciones(descripcion,fechaInicio,fechaFin,usuarioId,fichaId,tipoExcepcion,status) VALUES(?,?,?,?,?,?,?)";
            $arrData = array($this->txtMotivo, $this->txtFechaInicio, $this->txtFechaFin, $this->txtAprendiz, $this->txtFicha, $this->txtTipoExcepcion, 1);
            $request_insert = $this->insert($query_insert, $arrData);
            $respuesta = $request_insert;
        }
        return $respuesta;
    }

    public function updateExcepcion(int $txtTipoExcepcion, ?int $txtFicha, ?int $txtAprendiz, string $txtFechaInicio, string $txtFechaFin, string $txtMotivo, int $idExcepcion)
    {
        $this->txtTipoExcepcion = $txtTipoExcepcion;
        $this->txtFicha = $txtFicha;
        $this->txtAprendiz = $txtAprendiz;
        $this->txtFechaInicio = $txtFechaInicio;
        $this->txtFechaFin = $txtFechaFin;
        $this->txtMotivo = $txtMotivo;
        $this->idExcepcion = $idExcepcion;

        $query_insert = "UPDATE excepciones SET descripcion = ?,fechaInicio = ?,fechaFin = ?, usuarioId = ?, fichaId = ?, tipoExcepcion = ? WHERE idExcepciones = '{$this->idExcepcion}'";
        $arrData = array(
            $this->txtMotivo,
            $this->txtFechaInicio,
            $this->txtFechaFin,
            $this->txtAprendiz,
            $this->txtFicha,
            $this->txtTipoExcepcion
        );
        $reques_insert = $this->update($query_insert, $arrData);
        $respuesta = $reques_insert;

        return $respuesta;
    }
    public function deleteExcepcion(int $idExcepcion)
    {
        $this->idExcepcion = $idExcepcion;

        $sql = "UPDATE excepciones SET status = ? WHERE idExcepciones = {$this->idExcepcion}";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        return $request;
    }
}

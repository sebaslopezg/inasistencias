<?php

class NotificacionesModel extends Mysql{

    public function __construct()
    {
        parent::__construct();
    }

    public function NotiExcusa (string $tipoNovedad, string $mensaje, int $usuarioId){
        $this->tipoNovedad = $tipoNovedad;
        $this->mensaje = $mensaje;
        $this->usuarioId = $usuarioId;

        $query_insert = "INSERT INTO notificaciones (tipoNovedad, fecha, hora, mensaje, usuarioId, status)
        VALUES (?,CURDATE(),CURTIME(),?,?,?)";
        $arrData = array($this->tipoNovedad,$this->mensaje,$this->usuarioId,1);
        $ruquest_insert = $this->insert($query_insert, $arrData);
        $respuesta = $ruquest_insert;   

        return $respuesta;
    }

    public function NotiSelect (int $usuarioId){
        $this->usuarioId = $usuarioId;
        $sql = "SELECT * FROM notificaciones WHERE usuarioId = {$this->usuarioId} AND status = 1";
        $request = $this->select_all($sql);

        return $request;
    }
    
    public function NotiDelet(int $idNoti)
    {
        $this->idNoti = $idNoti;
        $this->status = 0;

        $sql = "UPDATE notificaciones set status = ? where idNotificaciones = ?";
        $arrData = array($this->status,$this->idNoti);
        $reques_insert = $this->update($sql, $arrData);
        $respuesta = $reques_insert;

        return $respuesta;
    }
}
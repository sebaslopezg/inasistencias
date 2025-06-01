<?php

class ExcepcionesModel extends mysql
{

    public function __construct()
    {
        parent::__construct();
    }

    public function selectExcepciones()
    {
        $sql = "SELECT 
            c.idExcepciones,
            c.tipoExcepcion,
            CONCAT(f.nombre, ' - ', f.numeroFicha) AS nombreFicha,
            c.fechaInicio,
            c.fechaFin,
            c.motivo,
            GROUP_CONCAT(CONCAT(u.nombre, ' ', u.apellido) SEPARATOR ', ') AS aprendices,
            c.status
        FROM 
            excepcion_cabecera c
        LEFT JOIN 
            ficha f ON f.idFicha = c.fichaId
        LEFT JOIN 
            excepcion_detalle d ON d.idExcCabecera = c.idExcepciones
        LEFT JOIN 
            usuario u ON u.idUsuarios = d.usuarioId
        WHERE 
            c.status > 0
        GROUP BY 
            c.idExcepciones
        ORDER BY 
            c.tipoExcepcion ASC;";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectExcepcionById(int $idExcepcion)
    {
        // 1) Obtener la fila de cabecera
        $this->idExcepcion = $idExcepcion;
        $sql = "
        SELECT 
            idExcepciones,
            tipoExcepcion,
            fichaId,
            fechaInicio,
            fechaFin,
            motivo,
            status
          FROM excepcion_cabecera 
         WHERE status > 0 
           AND idExcepciones = '{$this->idExcepcion}'
    ";
        $cabecera = $this->select($sql);

        if (empty($cabecera)) {
            return null;
        }

        // 2) Si el tipo es 1 (Aprendiz), cargar los detalles
        $aprendices = [];
        if (intval($cabecera['tipoExcepcion']) === 1) {
            $sqlDetalle = "
            SELECT usuarioId 
              FROM excepcion_detalle 
             WHERE idExcCabecera = '{$this->idExcepcion}'
        ";
            $rows = $this->select_all($sqlDetalle);
            foreach ($rows as $r) {
                $aprendices[] = intval($r['usuarioId']);
            }
        }

        // 3) Añadir el arreglo de aprendices al resultado
        $cabecera['aprendices'] = $aprendices;

        return $cabecera;
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

    public function insertExcepcion(
        int $txtTipoExcepcion,
        ?int $txtFicha,
        ?array $arrAprendices,
        string $txtFechaInicio,
        string $txtFechaFin,
        string $txtMotivo
    ) {
        // Inicializamos los arrays de conflicto
        $conflictoGlobal   = [];
        $conflictoFicha    = [];
        $conflictoAprendiz = [];

        // 1) Verificar conflicto Global
        if ($txtTipoExcepcion === 3) {
            $sqlGlobal = "
            SELECT 1
              FROM excepcion_cabecera
             WHERE status > 0
               AND tipoExcepcion = 3
               AND (
                    (fechaInicio <= '{$txtFechaInicio}' AND fechaFin >= '{$txtFechaInicio}')
                 OR (fechaInicio <= '{$txtFechaFin}'    AND fechaFin >= '{$txtFechaFin}')
                 OR (fechaInicio >= '{$txtFechaInicio}' AND fechaFin <= '{$txtFechaFin}')
               )
        ";
            $conflictoGlobal = $this->select_all($sqlGlobal);
        }

        // 2) Verificar conflicto Ficha
        if ($txtTipoExcepcion === 2 && $txtFicha !== null) {
            $sqlFicha = "
            SELECT 1
              FROM excepcion_cabecera
             WHERE status > 0
               AND tipoExcepcion = 2
               AND fichaId = {$txtFicha}
               AND (
                    (fechaInicio <= '{$txtFechaInicio}' AND fechaFin >= '{$txtFechaInicio}')
                 OR (fechaInicio <= '{$txtFechaFin}'    AND fechaFin >= '{$txtFechaFin}')
                 OR (fechaInicio >= '{$txtFechaInicio}' AND fechaFin <= '{$txtFechaFin}')
               )
        ";
            $conflictoFicha = $this->select_all($sqlFicha);
        }

        // 3) Verificar conflicto Aprendiz
        if ($txtTipoExcepcion === 1 && !empty($arrAprendices)) {
            foreach ($arrAprendices as $aprendizId) {
                $sqlAprendiz = "
                SELECT u.idUsuarios, u.nombre, u.apellido
                  FROM excepcion_cabecera c
                  JOIN excepcion_detalle d 
                    ON d.idExcCabecera = c.idExcepciones
                  JOIN usuario u
                    ON u.idUsuarios = d.usuarioId
                 WHERE c.status > 0
                   AND c.tipoExcepcion = 1
                   AND c.fichaId = {$txtFicha}
                   AND d.usuarioId = {$aprendizId}
                   AND (
                        (c.fechaInicio <= '{$txtFechaInicio}' AND c.fechaFin >= '{$txtFechaInicio}')
                     OR (c.fechaInicio <= '{$txtFechaFin}'    AND c.fechaFin >= '{$txtFechaFin}')
                     OR (c.fechaInicio >= '{$txtFechaInicio}' AND c.fechaFin <= '{$txtFechaFin}')
                   )
            ";
                $resultado = $this->select_all($sqlAprendiz);
                if (!empty($resultado)) {
                    $conflictoAprendiz[] = $resultado[0];
                }
            }
        }

        // 4) Evaluar conflictos con if…elseif…elseif…else
        if (!empty($conflictoGlobal)) {
            return 'existGlobal';
        } elseif (!empty($conflictoFicha)) {
            return 'existFicha';
        } elseif (!empty($conflictoAprendiz)) {
            // Devolvemos un array con la etiqueta y la lista de aprendiz(es) en conflicto
            return ['existAprendiz', $conflictoAprendiz];
        } else {
            // 5) Insertar la cabecera
            $sqlCabecera = "
            INSERT INTO excepcion_cabecera (
                tipoExcepcion,
                fichaId,
                fechaInicio,
                fechaFin,
                motivo,
                status
            ) VALUES (?, ?, ?, ?, ?, ?)
        ";
            $arrCabecera = [
                $txtTipoExcepcion,
                $txtFicha,
                $txtFechaInicio,
                $txtFechaFin,
                $txtMotivo,
                1 // status = 1 (activo)
            ];
            $idCabecera = $this->insert($sqlCabecera, $arrCabecera);

            if (intval($idCabecera) <= 0) {
                // Falló la inserción de cabecera
                return 0;
            }

            // 6) Si tipo = 1 (Aprendiz), insertar detalle para cada aprendiz
            if ($txtTipoExcepcion === 1 && !empty($arrAprendices)) {
                $sqlDetalle = "
                INSERT INTO excepcion_detalle (
                    idExcCabecera,
                    usuarioId
                ) VALUES (?, ?)
            ";
                foreach ($arrAprendices as $aprendizId) {
                    $this->insert($sqlDetalle, [$idCabecera, intval($aprendizId)]);
                }
            }

            // 7) Devolver el ID de la cabecera (entero > 0)
            return intval($idCabecera);
        }
    }

    public function updateExcepcion(
        int $txtTipoExcepcion,
        ?int $txtFicha,
        array $usuarios,         // ahora recibimos un array de usuarios
        string $txtFechaInicio,
        string $txtFechaFin,
        string $txtMotivo,
        int $idExcepcion
    ) {
        $this->txtTipoExcepcion = $txtTipoExcepcion;
        $this->txtFicha         = $txtFicha;
        $this->txtFechaInicio   = $txtFechaInicio;
        $this->txtFechaFin      = $txtFechaFin;
        $this->txtMotivo        = $txtMotivo;
        $this->idExcepcion      = $idExcepcion;

        // 1) Validación de conflicto de fechas: solapamiento
        if ($this->txtTipoExcepcion === 3) {
            $sqlGlobal = "
            SELECT 1
              FROM excepcion_cabecera
             WHERE status > 0
               AND tipoExcepcion = 3
               AND idExcepciones <> {$this->idExcepcion}
               AND (
                    fechaInicio <= '{$this->txtFechaFin}'
                AND fechaFin   >= '{$this->txtFechaInicio}'
               )
            LIMIT 1
        ";
            $requestGlobal = $this->select_all($sqlGlobal);
            if (!empty($requestGlobal)) {
                $respuesta = 'existGlobal';
                return $respuesta;
            }
        } elseif ($this->txtTipoExcepcion === 2) {
            $sqlFicha = "
            SELECT 1
              FROM excepcion_cabecera
             WHERE status > 0
               AND tipoExcepcion = 2
               AND fichaId = {$this->txtFicha}
               AND idExcepciones <> {$this->idExcepcion}
               AND (
                    fechaInicio <= '{$this->txtFechaFin}'
                AND fechaFin   >= '{$this->txtFechaInicio}'
               )
            LIMIT 1
        ";
            $requestFicha = $this->select_all($sqlFicha);
            if (!empty($requestFicha)) {
                $respuesta = 'existFicha';
                return $respuesta;
            }
        } elseif ($this->txtTipoExcepcion === 1) {
            $conflictosAprendiz = [];
            foreach ($usuarios as $aprendizId) {
                $sqlAprendiz = "
                SELECT 1
                  FROM excepcion_cabecera c
                  JOIN excepcion_detalle d 
                    ON d.idExcCabecera = c.idExcepciones
                 WHERE c.status > 0
                   AND c.tipoExcepcion = 1
                   AND c.fichaId = {$this->txtFicha}
                   AND d.usuarioId = {$aprendizId}
                   AND c.idExcepciones <> {$this->idExcepcion}
                   AND (
                        c.fechaInicio <= '{$this->txtFechaFin}'
                    AND c.fechaFin   >= '{$this->txtFechaInicio}'
                   )
                LIMIT 1
            ";
                $res = $this->select_all($sqlAprendiz);
                if (!empty($res)) {
                    $conflictosAprendiz[] = $aprendizId;
                }
            }
            if (!empty($conflictosAprendiz)) {
                $respuesta = 'existAprendiz';
                return $respuesta;
            }
        }

        // 2) Actualizar cabecera
        $query_update = "
        UPDATE excepcion_cabecera 
           SET motivo    = ?, 
               fechaInicio   = ?, 
               fechaFin      = ?, 
               fichaId       = ?, 
               tipoExcepcion = ?
         WHERE idExcepciones = {$this->idExcepcion}
    ";
        $arrData = [
            $this->txtMotivo,
            $this->txtFechaInicio,
            $this->txtFechaFin,
            $this->txtFicha,
            $this->txtTipoExcepcion
        ];
        $resUpdate = $this->update($query_update, $arrData);

        if (!$resUpdate) {
            $respuesta = false;
            return $respuesta;
        }

        // 3) Eliminar detalles antiguos
        $sqlDeleteDetalle = "DELETE FROM excepcion_detalle WHERE idExcCabecera = {$this->idExcepcion}";
        $this->delete($sqlDeleteDetalle/* , [$this->idExcepcion] */);

        // 4) Insertar nuevos detalles
        foreach ($usuarios as $idUsuario) {
            $sqlInsertDetalle = "
            INSERT INTO excepcion_detalle (idExcCabecera, usuarioId)
            VALUES (?, ?)
        ";
            $this->insert($sqlInsertDetalle, [$this->idExcepcion, intval($idUsuario)]);
        }

        // 5) Retornar resultado del UPDATE
        $respuesta = $resUpdate;
        return $respuesta;
    }



    public function deleteDetallesByCabecera(int $idCabecera)
    {
        $sql = "DELETE FROM excepcion_detalle WHERE excepcionId = ?";
        $arrData = [$idCabecera];
        return $this->delete($sql, $arrData);
    }

    public function insertDetalle(int $idCabecera, int $usuarioId)
    {
        $sql = "INSERT INTO excepcion_detalle (excepcionId, usuarioId) VALUES (?, ?)";
        $arrData = [$idCabecera, $usuarioId];
        return $this->insert($sql, $arrData);
    }



    public function deleteExcepcion(int $idExcepcion)
    {
        $this->idExcepcion = $idExcepcion;

        $sql = "
            UPDATE excepcion_cabecera
               SET status = ?
             WHERE idExcepciones = {$this->idExcepcion}
        ";
        $arrData = array(0);
        $affected = $this->update($sql, $arrData);

        // 2) Opcional: eliminar detalles asociados (solo si no usas ON DELETE CASCADE)
        $sqlDetalle = "
            DELETE FROM excepcion_detalle 
             WHERE idExcCabecera = {$this->idExcepcion}
        ";
        $this->delete($sqlDetalle);

        return $affected;
    }
}

<?php

class Excusas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
        }
    }

    public function excusas()
    {
        $data['page_title'] = "Pagina de excusas";
        $data['page_name'] = "excusas";
        $data['script'] = "excusas";
        $this->views->getView($this, "excusas", $data);
    }

    public function getExcusas()
    {
        if ($_SESSION['userData']['rol'] == 'APRENDIZ') {
            $arrData = $this->model->selectInasistencias($_SESSION['userData']['idUsuarios']);
            for ($i = 0; $i < count($arrData); $i++) {
                $arrData[$i]['instructor'] = $this->model->selectInstructor($arrData[$i]['idInstructor']);
                $arrVali[$i]['total'] = $this->model->validacionExcusa($arrData[$i]['Id']);
                $arrExcu[$i]['excusaId'] = $this->model->selectIdExcusa($arrData[$i]['Id']);

                $arrData[$i]['total'] = $arrVali[$i]['total']['total'];

                if ($arrData[$i]['status'] == 2) {
                    $arrData[$i]['action'] = '<span class="badge rounded-pill bg-primary">Aprobada</span>';
                }else if ($arrData[$i]['status'] == 3) {
                    $arrData[$i]['action'] = '<span class="badge rounded-pill bg-danger">Denegada</span>';
                }else if (empty($arrData[$i]['total']) || $arrData[$i]['total'] == 0 || empty($arrExcu[$i]['excusaId'])) {
                    $arrData[$i]['action'] = '
                <button type="button" data-id="' . $arrData[$i]['Id'] . '" data-action="agregar" class="btn btn-primary"><i class="bi bi-paperclip"></i></button>';
                } else {
                    $arrData[$i]['excusaId'] = $arrExcu[$i]['excusaId']['excusaId'];
                    $arrData[$i]['action'] = '
                <button type="button" data-id="' . $arrData[$i]['excusaId'] . '" data-action="editar" class="btn btn-success"><i class="bi bi-pencil-square"></i></button>
                <button type="button" data-id="' . $arrData[$i]['excusaId'] . '" data-action="delete" class="btn btn-danger"><i class="bi bi-trash"></i></button>';
                }

                if ($arrData[$i]['status'] == 1 || $arrData[$i]['status'] == 3) {
                    $arrData[$i]['status'] = '<span class="badge rounded-pill bg-success">Activo</span>';
                }else {
                    $arrData[$i]['status'] = '<span class="badge rounded-pill bg-danger">Inactiva</span>';
                }
              /*   if ($arrData[$i]['status'] == 2) {
                    $arrData[$i]['status'] = '<span class="badge rounded-pill bg-primary">Aprobada</span>';
                }
                if ($arrData[$i]['status'] == 3) {
                    $arrData[$i]['status'] = '<span class="badge rounded-pill bg-danger">Desaprobada</span>';
                } */
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        } elseif ($_SESSION['userData']['rol'] == 'INSTRUCTOR') {
            $arrData = $this->model->selectInasistenciasPorInstru($_SESSION['userData']['idUsuarios']);

            for ($i = 0; $i < count($arrData); $i++) {
                $arrData[$i]['fileExc'] = '
                <button type="button" data-id="' . $arrData[$i]['idExcusas'] . '" data-action="descargar" class="btn btn-primary"><i class="bi bi-file-earmark-ruled-fill"></i></button>';
                $arrData[$i]['action'] = '
                <button type="button" data-id="' . $arrData[$i]['idInasistencias'] . '" data-action="aprobar" class="btn btn-success"><i class="bi bi-check-circle"></i></button>
                <button type="button" data-id="' . $arrData[$i]['idInasistencias'] . '" data-action="denegar" class="btn btn-danger"><i class="bi bi-x-circle"></i></button>';
          
                if ($arrData[$i]['status'] == 1) {
                    $arrData[$i]['status'] = '<span class="badge rounded-pill bg-success">Activo</span>';
                }
                if ($arrData[$i]['status'] == 2) {
                    $arrData[$i]['status'] = '<span class="badge rounded-pill bg-danger">Inactivo</span>';
                }
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
    }

    public function descargarArchivo() {
        
        if (isset($_GET['download'])) {
            // Obtener el ID del archivo desde la URL
            $id = $_GET['download'];
            $file = $this->model->selectFilePorId($id);
    
            if ($file && isset($file['uriArchivo'])) {
                $filepath = $file['uriArchivo'];
                
                // Verificar si el archivo existe en el sistema de archivos
                if (file_exists($filepath)) {
                    // Forzar la descarga del archivo
                    header('Content-Type: application/pdf'); 
                    header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
                    header('Content-Length: ' . filesize($filepath));
    
                    // Enviar el archivo al navegador
                    readfile($filepath);
                    exit;  // Asegura que el script termine aquí para evitar que se envíen más datos
                } else {
                    echo json_encode(['status' => false, 'msg' => 'El archivo no existe: ' . $filepath]);
                    exit;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Archivo no encontrado en la base de datos']);
                exit;
            }
        } else {
            echo json_encode(['status' => false, 'msg' => 'Falta el parámetro de descarga']);
            exit;
        }
    }

    public function getInasistenciaById($id)
    {

        $intId = intval(strClean($id));

        if ($intId > 0) {
            $arrData = $this->model->selectInasistenciaById($id);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'tipo de dato no permitido');
        }

        if (!empty($arrData)) {
            $arrResponse = array('status' => true, 'data' => $arrData);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos con este id');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function getUsuarioById()
    {

        $arrData = $this->model->selectUsuario($_SESSION['userData']['idUsuarios']);

        if (!empty($arrData)) {
            $arrResponse = array('status' => true, 'data' => $arrData);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos con este id');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function selectExcusaId($id)
    {

        $intExc = intval(strClean($id));

        if ($intExc > 0) {
            $arrData = $this->model->selectExcusaId($id);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'tipo de dato no permitido');
        }

        if (!empty($arrData)) {
            $arrResponse = array('status' => true, 'data' => $arrData);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos con este id');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }



    public function setExcusas()
    {
        $intInasistencia = intval(strClean($_POST['txtIdInasistencia']));
        $intIdUsuario = intval(strClean($_POST['txtIdUsuario']));
        $intIdInstructor = intval(strClean($_POST['txtIdInstructor']));
        $strArchivo = ($_FILES['txtArchivo']);
        $intEstado = intval(strClean($_POST['txtEstado']));
        $intExcusa = intval(strClean($_POST['txtIdExcusa']));
        $fileRuta = "";

        $arrPosts = [
            'txtIdInasistencia',
            'txtIdUsuario'
        ];
        $arrFile = [
            'txtArchivo'
        ];
        if (check_post($arrPosts) && check_file($arrFile)) {

            try {
                $response = uploadFile($_FILES['txtArchivo']);
                $responseData = json_decode($response, true);
                if ($responseData['status']) {
                    $fileRuta = $responseData['filePath'];
                } else {
                    echo json_encode(array('status' => false, 'msg' => $responseData['msg']));
                return; 
                }

                $fileName = basename($_FILES['txtArchivo']["name"]);
                if ($intExcusa == 0 || $intExcusa == "" || $intExcusa == "0") {
                    $insert = $this->model->insertExcusas(
                        $intInasistencia,
                        $intIdUsuario,
                        $intIdInstructor,
                        $fileName,
                        $fileRuta,
                    );
                    $option = 1;
                } else {
                    $insert = $this->model->updateExcusa(
                        $intExcusa,
                        $fileRuta,
                    );
                    $option = 2;
                }

                if (intval($insert) > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Excusa enviada correctamente');
                    }

                    if ($option == 2) {
                        $arrResponse = array('status' => true, 'msg' => 'Excusa editada correctamente');
                    }
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al insertar');
                }
            } catch (\Throwable $th) {
                $arrResponse = array('status' => false, 'msg' => "Error desconocido: $th");
            }
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Debe insertar todos los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }


    function deleteExcusas()
    {
        if ($_POST) {
            $intIdExcusa = intval($_POST['txtIdExcusa']);
            $requestDelete = $this->model->deleteExcusa($intIdExcusa);

            if ($requestDelete) {
                $arrResponse = array('status' => true, 'msg' => 'se ha eliminado la excusa');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'error al eliminar la excusa');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    function aceptarExcusas()
    {
        if ($_POST) {
            $intIdExcusa = intval($_POST['txtIdInasistencia']);
            $requestDelete = $this->model->aceptarExcusa($intIdExcusa);

            if ($requestDelete) {
                $arrResponse = array('status' => true, 'msg' => 'se ha eliminado la inasistencia');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'error al eliminar la inasistencia');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    function denegarExcusas()
    {
        if ($_POST) {
            $intIdExcusa = intval($_POST['txtIdInasistencia']);
            $requestDelete = $this->model->denegarExcusa($intIdExcusa);
            

            if ($requestDelete) {
                $arrResponse = array('status' => true, 'msg' => 'se ha denegado la excusa');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'error al denegar la excusa');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        
    }
}

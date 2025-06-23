<?php 

class Horario extends Controllers{
    public function __construct(){
        parent::__construct();
        session_start();
        if(empty($_SESSION['login'])){
           header('Location: ' . base_url().'/login' );
       } 
    }
    public function horario(){

        $data['script'] = "horario";
        $data['page_name'] = "horario";
        $data['page_title'] = "Cargar Horarios";

        $this->views->getView($this,"horario", $data);
    }

    public function gestionar(){

        $data['page_title'] = "Gestionar horario";
        $data['page_name'] = "horario";
        $data['script'] = "gestionarHorarios";
        $this->views->getView($this,"gestionar", $data);
    }

    public function verhorario(){

        $data['page_title'] = "Gestionar horario";
        $data['page_name'] = "horario";
        $data['script'] = "horario";
        $this->views->getView($this,"gestionar", $data);
    }

    public function getHorarios(){
        $arrData = $this->model->selectAllHorarios();
        for ($i=0; $i < count($arrData); $i++) { 
            $arrData[$i]['fechaInicio'] = date('d/m/Y', strtotime($arrData[$i]['fechaInicio']));
            $arrData[$i]['horaInicio'] = date('H:i', strtotime($arrData[$i]['horaInicio']));
            $arrData[$i]['horaFin'] = date('H:i', strtotime($arrData[$i]['horaFin']));

            $arrData[$i]['accion'] = '
            <button type="button" data-action="delete" data-id="'.$arrData[$i]['ID'].'" class="btn btn-danger"><i class="bi bi-trash"></i></button>
            <button type="button" data-action="edit" data-id="'.$arrData[$i]['ID'].'" class="btn btn-primary"><i class="bi bi-pencil-square"></i></button>
            ';
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function setHorario(){
        if ($_POST) {
            $statusLectura = true;
            $arrStatus = array();
            $insert = 0;
            $ficha  = intval(strClean($_POST['ficha']));

            $fichaEncontrada = $this->model->selectFicha($ficha);

            if (!empty($fichaEncontrada)) {

                for ($i=0; $i < count($_POST['hFecha']); $i++) { 
    
                    $fecha = strClean($_POST['hFecha'][$i]);
                    $horaInicio = intval(strClean($_POST['hHoraInicio'][$i]));
                    $horaFin = intval(strClean($_POST['hHoraFin'][$i]));
                    $instructor = strClean($_POST['hInstructor'][$i]);
    
                    if (
                        check_post_var($fecha) && 
                        check_post_var($horaInicio) &&
                        check_post_var($horaFin) &&
                        check_post_var($instructor)) {

                        $arrFecha = explode('/', $fecha);
    
                        if(count($arrFecha) === 3) {
                            

                            $strFechaFormateada = $arrFecha[2]."/".$arrFecha[1]."/".$arrFecha[0];

                            $horaInicioFormateada = mktime($horaInicio,0,0);
                            $horaFinFormateada = mktime($horaFin,0,0);
                    
                            $horaInicioConvertida = date('H:i:s', $horaInicioFormateada);
                            $horaFinConvertida = date('H:i:s', $horaFinFormateada);
        
                            $horariosRegistrados = $this->model->selectHorarios($ficha, $strFechaFormateada, $horaInicioConvertida);
    
                            if (empty($horariosRegistrados)) {
                                    
                                $idInstructor = $this->model->selectInstructorByName($instructor);
    
                                if (!empty($idInstructor)) {
                                    $idInstructor = $idInstructor['idUsuarios'];
                                    $insert = $this->model->insertHorario($ficha, $strFechaFormateada, $horaInicioConvertida, $horaFinConvertida, $idInstructor);
        
                                    if (intval($insert) > 0) {
                                        $arrStatusMessage = array('index' => $i, 'status' => true, 'msg' => 'Registro insertado exitosamente');
                                        array_push($arrStatus,$arrStatusMessage);
                                    }else if($insert == 'exist'){
                                        $arrStatusMessage = array('index' => $i, 'status' => false, 'msg' => 'El registro ya existe');
                                        array_push($arrStatus,$arrStatusMessage);
                                    }else{
                                        $arrStatusMessage = array('index' => $i, 'status' => false, 'msg' => 'No se pudo insertar el registro');
                                        array_push($arrStatus,$arrStatusMessage);
                                    }
                                }else{
                                    $arrStatusMessage = array('index' => $i, 'status' => false, 'msg' => 'No se encontró el nombre del instructor');
                                    array_push($arrStatus,$arrStatusMessage);
                                }
                            }else{
                                $arrStatusMessage = array('index' => $i, 'status' => false, 'msg' => 'Ya existe un registro con la misma fecha y hora');
                                array_push($arrStatus,$arrStatusMessage);
                            }      
                        }else{
                            $arrStatusMessage = array('index' => $i, 'status' => false, 'msg' => 'La fecha contiene un formato inválido');
                            array_push($arrStatus,$arrStatusMessage);
                        }
    
                    }else{
                        $statusLectura = false;
                        break;
                    }
                }

                if (!empty($arrStatus)){
                    $arrResponse = array('statusCode' => 1, 'msg' => 'algunos registros no se insertaron correctamente: ', 'log' => $arrStatus);
                }else if($statusLectura){
                    $arrResponse = array('statusCode' => 0, 'msg' => 'registros del horario insertados correctamente');
                }else{
                    $arrResponse = array('statusCode' => 2, 'msg' => 'Uno o varios campos estan vacios o contienen datos inválidos');
                }

            }else{
                $arrResponse = array('statusCode' => 3, 'msg' => 'Se debe insertar una ficha valida');
            }



            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function deleteHorario(){
        if ($_POST) {
            $id = intval(strClean($_POST['idHorario']));
            $delete = $this->model->deleteHorario($id);
            if ($delete) {
                $arrResponse = array('status' => true, 'msg' => 'Registro eliminado correctamente');
            }else{
                $arrResponse = array('status' => false, 'msg' => 'No se pudo eliminar el registro');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getHorarioById($id){

        $IntId = intval(strClean($id));
        $arrData = $this->model->selectHorario($IntId);
        if (!empty($arrData)) {
            $arrData['fecha'] = date('d/m/Y', strtotime($arrData['fechaInicio']));
            $arrData['horaInicio'] = date('H:i', strtotime($arrData['horaInicio']));
            $arrData['horaFin'] = date('H:i', strtotime($arrData['horaFin']));
            $arrResponse = array('status' => true, 'data' => $arrData);
        }else{
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos con ese ID');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        
        die();
    }
}
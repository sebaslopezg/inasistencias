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

    public function setHorario(){
        if ($_POST) {
            $statusLectura = true;
            $arrStatus = array();
            $insert = 0;
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

                        $strFechaFormateada = $arrFecha[2]."/".$arrFecha[1]."/".$arrFecha[0];

                        $horaInicioFormateada = mktime($horaInicio,0,0);
                        $horaFinFormateada = mktime($horaFin,0,0);
            
                        $horaInicioConvertida = date('H:i:s', $horaInicioFormateada);
                        $horaFinConvertida = date('H:i:s', $horaFinFormateada);
                        $fechaConvertida = date('Y/m/d', $fechaFormateada);

                        $idInstructor = $this->model->selectInstructorByName($instructor);
                        
                        if (!empty($idInstructor)) {
                            $idInstructor = $idInstructor['idUsuarios'];
                            $insert = $this->model->insertHorario($strFechaFormateada, $horaInicioConvertida, $horaFinConvertida, $idInstructor);
                        }else{
                            $insert = 0;
                            $arrStatusMessage = array('index' => $i, 'msg' => 'No se encontró el nombre del instructor');
                            array_push($arrStatus,$arrStatusMessage);
                        }
                        
                        if (intval($insert) > 0) {
                        }else if($insert == 'exist'){
                            $arrStatusMessage = array('index' => $i, 'msg' => 'El registro ya existe');
                            array_push($arrStatus,$arrStatusMessage);
                        }else{
                            $arrStatusMessage = array('index' => $i, 'msg' => 'No se pudo insertar el registro');
                            array_push($arrStatus,$arrStatusMessage);
                        }
                }else{
                    $statusLectura = false;
                    break;
                }
            }

            if (!empty($arrStatus)) {
                $arrResponse = array('status' => true, 'msg' => 'algunos registros no se insertaron correctamente: ', 'log' => $arrStatus);
            }else if($statusLectura){
                $arrResponse = array('status' => true, 'msg' => 'registros del horario insertados correctamente');
            }else{
                $arrResponse = array('status' => false, 'msg' => 'Uno o varios campos estan vacios o contienen datos inválidos');
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }
}
<?php 

class Usuarios extends Controllers{
    public function __construct(){
        parent::__construct();
    }
    public function usuarios(){

        $data['page_title'] = "Página de usuarios";
        $data['page_name'] = "usuarios";
        $data['script'] = "usuarios";
        $this->views->getView($this,"usuarios", $data);
    }

    public function getUsarios(){
        $arrData = $this->model->selectUsuarios();

        for($i = 0; $i < count($arrData); $i++){
            $arrData[$i]['accion'] = '
            <button type="button" data-action="delete" data-id="'.$arrData[$i]['ID'].'" class="btn btn-danger"><i class="bi bi-trash"></i></button>
            <button type="button" data-action="edit" data-id="'.$arrData[$i]['ID'].'" class="btn btn-primary"><i class="bi bi-pencil-square"></i></button>
            ';

            if ($arrData[$i]['status'] == 1) {
                $arrData[$i]['status'] = '<span class="badge rounded-pill bg-success">Activo</span>';
            }
            if ($arrData[$i]['status'] == 2) {
                $arrData[$i]['status'] = '<span class="badge rounded-pill bg-danger">Inactivo</span>';
            }
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function getUsariosById($id){

        $intId = intval(strClean($id));

        if ($intId > 0) {
            $arrData = $this->model->selectUsuariosById($id);
        }else{
            $arrResponse = array('status' => false, 'msg' => 'tipo de dato no permitido');
        }

        if (!empty($arrData)) {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }else{
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos con este id');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function setUsuario(){
        $arrPosts = [
            'txtNombre', 
            'txtApellido', 
            'txtDocumento', 
            'txtTelefono', 
            'genero', 
            'txtEmail',
            'txtCodigo'
        ];

        if (check_post($arrPosts)) {

            $strNombre = strClean($_POST['txtNombre']);
            $strApellido = strClean($_POST['txtApellido']);
            $intDocumento = intval(strClean($_POST['txtDocumento']));
            $intTelefono = intval(strClean($_POST['txtTelefono']));
            $intGenero = intval(strClean($_POST['genero']));
            $strEmail = strtolower(strClean($_POST['txtEmail']));
            $strCodigo = strClean($_POST['txtCodigo']);
            $strFirma = "";
            $strRol = "APRENDIZ";

            $insert = $this->model->insertUsuario($strNombre, $strApellido, $intDocumento, $intTelefono, $intGenero, $strEmail, $strCodigo, $strRol, $strFirma);

            if (intval($insert) > 0) {
                $arrResponse = array('status' => true, 'msg' => 'Usuario insertado correctamente');
            }else if($insert == 'exist'){
                $arrResponse = array('status' => false, 'msg' => 'Ya existe un usuario con el mismo documento');
            }else{
                $arrResponse = array('status' => false, 'msg' => 'Error al insertar');
            }

        }else{
            $arrResponse = array('status' => false, 'msg' => 'Debe insertar todos los datos');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        
    }
}
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

            if ($insert) {
                $arrResponse = array('status' => true, 'msg' => 'Usuario insertado correctamente');
            }else{
                $arrResponse = array('status' => false, 'msg' => 'Error al insertar');
            }

        }else{
            $arrResponse = array('status' => false, 'msg' => 'Debe insertar todos los datos');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        
    }
}
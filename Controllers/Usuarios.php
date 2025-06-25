<?php

class Usuarios extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
        }
    }
    public function usuarios()
    {
        $data['page_title'] = "Página de usuarios";
        $data['page_name'] = "usuarios";
        $data['script'] = "usuarios";
        $this->views->getView($this, "usuarios", $data);
    }

    public function cuenta()
    {

        $data['page_title'] = "Página de usuarios";
        $data['page_name'] = "Cuenta";
        $data['script'] = "cuenta";
        $this->views->getView($this, "cuenta", $data);
    }

    public function getUsarios()
    {
        $arrData = $this->model->selectUsuarios();

        for ($i = 0; $i < count($arrData); $i++) {
            $arrData[$i]['accion'] = '
            <button type="button" data-action="delete" data-id="' . $arrData[$i]['ID'] . '" class="btn btn-danger"><i class="bi bi-trash"></i></button>
            <button type="button" data-action="edit" data-id="' . $arrData[$i]['ID'] . '" class="btn btn-primary"><i class="bi bi-pencil-square"></i></button>
            ';

            if ($arrData[$i]['rol'] == "COORDINADOR") {
                $arrData[$i]['rol'] = '<span class="badge bg-primary">Coordinador</span>';
            }
            if ($arrData[$i]['rol'] == "INSTRUCTOR") {
                $arrData[$i]['rol'] = '<span class="badge bg-info text-dark">Instructor</span>';
            }
            if ($arrData[$i]['rol'] == "APRENDIZ") {
                $arrData[$i]['rol'] = '<span class="badge bg-secondary">Aprendiz</span>';
            }

            if ($arrData[$i]['status'] == 1) {
                $arrData[$i]['status'] = '<span class="badge rounded-pill bg-success">Activo</span>';
            }
            if ($arrData[$i]['status'] == 2) {
                $arrData[$i]['status'] = '<span class="badge rounded-pill bg-danger">Inactivo</span>';
            }
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function getUsariosById($id)
    {

        $intId = intval(strClean($id));

        if ($intId > 0) {
            $arrData = $this->model->selectUsuariosById($id);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'tipo de dato no permitido');
        }

        if (!empty($arrData)) {
            $arrResponse = array('status' => true, 'data' => $arrData);
        } else {
            $arrResponse = array('status' => false, 'msg' => 'No se encontraron datos con este id');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function setUsuario()
    {
        $arrPosts = [
            'txtNombre',
            'txtApellido',
            'txtDocumento',
            'txtTelefono',
            'genero',
            'txtEmail',
            'txtCodigo',
            'userRol',
            'userStatus'
        ];

        $firma = ['userFirma'];

        if (check_post($arrPosts)) {

            $strNombre = strClean($_POST['txtNombre']);
            $strApellido = strClean($_POST['txtApellido']);
            $intDocumento = intval(strClean($_POST['txtDocumento']));
            $intTelefono = intval(strClean($_POST['txtTelefono']));
            $intGenero = intval(strClean($_POST['genero']));
            $strEmail = strtolower(strClean($_POST['txtEmail']));
            $strCodigo = strClean($_POST['txtCodigo']);
            $intStatus = intval(strClean($_POST['userStatus']));
            $strRol = strClean($_POST['userRol']);
            $intIdUsuario = intval(strClean($_POST['idUsuario']));
            $strPassword =  hash("SHA256", strClean($_POST['txtDocumento']));

            if (check_file($firma)) {
                $strFirma = save_image('userFirma');
            }else{
                $strFirma = '';
            }

            try {
                if ($intIdUsuario == 0 || $intIdUsuario == "" || $intIdUsuario == "0") {
                    $insert = $this->model->insertUsuario(
                        $strNombre,
                        $strApellido,
                        $intDocumento,
                        $intTelefono,
                        $intGenero,
                        $strEmail,
                        $strCodigo,
                        $strRol,
                        $strPassword,
                        $strFirma
                    );
                    $option = 1;
                } else {
                    if ($intStatus == 0) {
                        $intStatus = 1;
                    }
                    $insert = $this->model->updateUsuario(
                        $intIdUsuario,
                        $strNombre,
                        $strApellido,
                        $intDocumento,
                        $intTelefono,
                        $intGenero,
                        $strEmail,
                        $strCodigo,
                        $strRol,
                        $strFirma,
                        $intStatus
                    );
                    $option = 2;
                }

                if (intval($insert) > 0) {

                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Usuario insertado correctamente');
                    }

                    if ($option == 2) {
                        $arrResponse = array('status' => true, 'msg' => 'Usuario actualizado correctamente');
                    }
                } else if ($insert == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => 'Ya existe un usuario con el mismo documento');
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

    public function editprofile($id){

        $intId = intval(strClean($id));

        $arrPosts = [
            'userName',
            'userApellido',
            'userGenero',
            'userTelefono',
            'userEmail'
        ];

        if (check_post($arrPosts)) {
            $strUserName = strClean($_POST['userName']);
            $strUserApellido = strClean($_POST['userApellido']);
            $strUserGenero = strClean($_POST['userGenero']);
            $strUserTelefono = strClean($_POST['userTelefono']);
            $strUserEmail = strClean($_POST['userEmail']);

            $update = $this->model->editProfile(
                $strUserName,
                $strUserApellido,
                $strUserGenero,
                $strUserTelefono,
                $strUserEmail,
                $intId
            );

            if (intval($update) > 0) {
                $arrResponse = array('status' => true, 'msg' => 'Usuario actualizado correctamente');
            }else{
                $arrResponse = array('status' => false, 'msg' => 'Error al intentar actualizar usuario');
            }
        }else{
            $arrResponse = array('status' => false, 'msg' => 'Campos Vacíos');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function updatepass($id){

        $intId = intval(strClean($id));

        $arrPosts = [
            'currentPassword',
            'newPassword',
            'renewPassword'
        ];

        if (check_post($arrPosts)) {
            $strPassActual = hash("SHA256", strClean($_POST['currentPassword']));
            $strPassNueva = hash("SHA256", strClean($_POST['newPassword']));
            $strPassNuevaRepetir = hash("SHA256", strClean($_POST['renewPassword']));

            $courrentPass = $this->model->getPass($intId);

            if ($strPassActual == $courrentPass['password']) {

                if ($strPassNueva == $strPassNuevaRepetir) {
                    $update = $this->model->updatePass($strPassNueva, $intId);

                    if (intval($update) > 0) {
                        $arrResponse = array('status' => true, 'msg' => 'Contraseña actualizada correctamente');
                    }else{
                        $arrResponse = array('status' => false, 'msg' => 'Error al intentar actualizar la contraseña');
                    }

                }else{
                    $arrResponse = array('status' => false, 'msg' => 'La contraseña nueva no coincide con la repetida');
                }

            }else{
                $arrResponse = array('status' => false, 'msg' => 'La contraseña actual no coincide');
            }

        }else{
            $arrResponse = array('status' => false, 'msg' => 'Todos los campos son obligatorios');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function deleteUsuario()
    {
        if ($_POST) {
            $intIdUsuario = intval($_POST['idUsuario']);
            $requestDelete = $this->model->deleteUsuario($intIdUsuario);

            if ($requestDelete) {
                $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el usuario');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el usuario');
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}

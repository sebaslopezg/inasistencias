<?php

require_once __DIR__ . '/../Models/LoginModel.php';


class Recuperar extends Controllers
{

   
    private $LoginModel;
    public function __construct()
    {
        parent::__construct();
        $this->LoginModel = new LoginModel();
    }

    public function recuperar()
    {
        $data['page_title'] = "Recuperar Contraseña";
        $data['page_name'] = "recuperar";
        $data['script'] = "recuperar";
        $this->views->getView($this, "recuperar", $data);
    }

    public function verificarToken(){
        if (isset($_GET['codigo']) && isset($_GET['correo'])) {
            $codigo = $_GET['codigo'];
            $correo = $_GET['correo'];
    
            $resultado = $this->LoginModel->selectCodigoYCorreo($codigo, $correo);
            if ( intval($resultado) > 0) {
                $arrResponse = array(
                    'status' => true,
                    'action' => '<div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Ingresar</h5>
                                    <p class="text-center small">Ingrese su nueva contraseña</p>
                                 </div>
                                 <form id="frmChangePass" method="POST" class="row g-3" novalidate>
                                    <div class="col-12">
                                        <label for="yourUsername" class="form-label">Contraseña</label>
                                        <div class="input-group has-validation">
                                            <input type="email" name="nueva_contraseña" class="form-control" id="nueva_contraseña" required>
                                             <input type="text" name="txtCorreo" class="form-control" id="txtCorreo" required hidden>
                                             <input type="text" name="txtCodigo" class="form-control" id="txtCodigo" required hidden>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Ingresar</button>
                                    </div>
                                 </form>'
                );
            } else {
                $arrResponse = array('status' => false, 'msg' => 'El token o el correo no existen', 'action' => '
                <title>Token o Correo No Encontrado</title>
                <body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
                <div class="container">
                    <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-4">
                            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 4rem;"></i>
                            </div>
                            <h1 class="card-title">Token o correo no encontrado</h1>
                            <p class="card-text text-muted">
                            El enlace que intentaste usar no es válido o ha caducado. Por favor, verifica la información o contacta a soporte.
                            </p>
                            <a href="http://localhost/inasistencias/login" class="btn btn-primary mt-3">Volver al inicio</a>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </body>
                ');
            }
        } else {
            $arrResponse = array('status' => false, 'msg' => 'El token o el correo no se encuentran en la url','action' => '
                <title>Token o Correo No Encontrado</title>
                <body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
                <div class="container">
                    <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-4">
                            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 4rem;"></i>
                            </div>
                            <h1 class="card-title">Token o correo no encontrado</h1>
                            <p class="card-text text-muted">
                            El enlace que intentaste usar no es válido o ha caducado. Por favor, verifica la información o contacta a soporte.
                            </p>
                            <a href="/" class="btn btn-primary mt-3">Volver al inicio</a>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </body>
                ');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    

    
    public function changePass()
    {
       /*  // Verificar si el código y el correo fueron proporcionados
        if (isset($_GET['codigo']) && isset($_GET['correo'])) {
            $codigo = $_GET['codigo'];
            $correo = $_GET['correo'];

            $resultado = $this->LoginModel->selectCodigoYCorreo($codigo, $correo);

            if ($resultado->num_rows > 0) { */
                // Si el código y el correo existen, mostrar el formulario para cambiar la contraseña
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $codigo = $_POST['txtCodigo'];
                    $correo = $_POST['txtCorreo'];
                    // Recibir y asegurar que la nueva contraseña se haya proporcionado
                    $nueva_contraseña = $_POST['nueva_contraseña'];

                    // Asegurarse de que la nueva contraseña no esté vacía
                    if (!empty($nueva_contraseña)) {

                        // Actualizar la contraseña en la base de datos (esto debe ser una operación segura, usando hash)
                        $nueva_contra = hash("SHA256", $nueva_contraseña);
                        $this->LoginModel->updatePassword($nueva_contra, $correo);

                        // Eliminar el código de recuperación una vez que la contraseña haya sido cambiada
                        $this->LoginModel->deleteCodeRecu($codigo, $correo);

                        // Mostrar un mensaje de éxito y evitar que se muestre el formulario nuevamente
                        $arrResponse = array('status' => true, 'msg' => 'Contraseña cambiada correctamente');
                    } else {
                        // Mostrar un mensaje de error si la nueva contraseña está vacía
                        $arrResponse = array('status' => false, 'msg' => 'La nueva contraseña no puede estar vacía');
                    }
                } 
           /*  } else {
                // Si el código o el correo no existen, mostrar un mensaje de error
                $arrResponse = array('status' => false, 'msg' => 'El código o el correo no existen');
            } */
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }


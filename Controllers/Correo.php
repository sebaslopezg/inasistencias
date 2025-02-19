<?php
require __DIR__ . '/../Assets/vendor/autoload.php';  // Incluye PHPMailer si usas Composer
require_once __DIR__ . '/../Models/LoginModel.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Función para generar un código aleatorio de 20 caracteres
function generarCodigo()
{
    return bin2hex(random_bytes(10));  // Genera un código de 20 caracteres hexadecimales
}


class Correo extends Controllers
{

    private $mail;
    private $LoginModel;
    public function __construct()
    {
        parent::__construct();
        $this->mail = new PHPMailer(true);
        $this->LoginModel = new LoginModel();
    }

    public function correo()
    {
        $data['page_title'] = "Recuperar Contraseña";
        $data['page_name'] = "correo";
        $data['script'] = "correo";
        $this->views->getView($this, "correo", $data);
    }

    // Función para enviar el correo con el enlace de recuperación
    public function enviarCorreo($destinatario, $asunto, $mensaje)
    {
        try {
            // Configuración del servidor SMTP de Gmail
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';  // Dirección SMTP de Gmail
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'pruebaadso2025@gmail.com';  // Tu correo SMTP
            $this->mail->Password = 'gbja zjir mfqu mqqp';  // Contraseña de la aplicación para Gmail
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;

            // Remitente
            $this->mail->setFrom('pruebaadso2025@gmail.com', 'Prueba de correo');
            // Destinatario
            $this->mail->addAddress($destinatario);

            // Contenido del correo
            $this->mail->isHTML(true);
            $this->mail->Subject = $asunto;
            $this->mail->Body    = $mensaje;

            // Enviar el correo
            $this->mail->send();
            $arrResponse = array('status' => true, 'msg' => 'Correo enviado correctamente');
        } catch (Exception $e) {

            $arrResponse = array('status' => false, 'msg' => 'Error al enviar el correo' . $this->mail->ErrorInfo . $this->mail->Host .
                $this->mail->SMTPAuth . $this->mail->Username . $this->mail->Password . $this->mail->SMTPSecure . $this->mail->Port);
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    // Función para manejar la recuperación de contraseña
    public function recuperarContrasena($correo)
    {
        // Generar el código de recuperación
        $codigo = generarCodigo();

        $insert = $this->LoginModel->insertRecuperacion($correo, $codigo);

        // Crear el enlace con el código de recuperación
        $enlace = "http://localhost/Email_ejemplo/recuperar.php?codigo=" . $codigo . "&correo=" . urlencode($correo);

        // Enviar el correo con el enlace de recuperación
        $asunto = 'Recuperación de Contraseña';
        $mensaje = "Haz clic en el siguiente enlace para recuperar tu contraseña: <a href='" . $enlace . "'>Recuperar Contraseña</a>";
        // Llamar a la función de enviarCorreo
        $this->enviarCorreo($correo, $asunto, $mensaje);

        if (intval($insert) > 0) {

            $arrResponse = array('status' => true, 'msg' => 'Codigo de recuperacion generado correctamente');
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Error al generar el codigo de recuperacion');
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function sendEmail()
    {
        // Verificamos si el formulario fue enviado
        $strCorreo = strClean($_POST['correo']);

        $arrPost = ['correo'];
        if (!check_post($arrPost)) {
            $arrResponse = array('status' => false, 'msg' => 'El correo es obligatorio');
        } else {
            // Recibimos los datos del formulario
            $correoDestino = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

            // Verificamos que la dirección de correo sea válida
            if (filter_var($correoDestino, FILTER_VALIDATE_EMAIL)) {
                // Instanciamos la clase Correo
                $correo = new Correo();
                // Llamamos al método para manejar la recuperación de contraseña
                $correo->recuperarContrasena($correoDestino);  // Este método se encarga de todo el flujo
            } else {
                $arrResponse = array('status' => false, 'msg' => 'La dirección de correo no es válida');
            }
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
}

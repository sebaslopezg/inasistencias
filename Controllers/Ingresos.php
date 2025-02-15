<?php 

class Ingresos extends Controllers{
    public function __construct(){
        parent::__construct();
    }
    public function ingresos(){

        $data['page_title'] = "Página de ingresos";
        $data['page_name'] = "ingresos";
        $this->views->getView($this,"ingresos", $data);
    }

    public function manageRegistros() 
    {
        $codigo = intval($_POST['codigo']);
        $aprendiz = $this->registroModel->traerAprendiz($codigo);
        $num = array_pop($aprendiz);
        $registros = $this->registroModel->validarRegistro($num);

        if (empty($registros)){
            $this->registroModel->crearRegistro($num);
            header('Location: index.php?pagina=registro');
            exit();
        }else {
            $id = array_pop($registros)['id_registros'];
            $this->registroModel->editarRegistro($id);
            header('Location: index.php?pagina=registro');
            exit();
        }
    }

}
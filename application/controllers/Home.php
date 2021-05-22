<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Home extends CI_Controller{

        function __construct(){
            parent::__construct();
            $this->load->model('TransaccionesModel');
            $this->load->model('MensajesModel');
        }

        public function index(){
            if($this->session->userdata('login')){
                header('location: ' . base_url());
            }else{
                $datos['tasa'] = $this->TransaccionesModel->getTasa();
                $datos['mensajes'] = $this->getMensajes();
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('home/home',$datos);
                $this->load->view('overall/footer');
            }
        }
        private function getMensajes(){
            $where = ['estado' => ESTADO_MENSAJE_ACTIVO,'publico !='=>PUBLICO_MENSAJES_PERSONAL];
            return $mensajes = $this->MensajesModel->get(['*'],$where);
        }
    }
?>

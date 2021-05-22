<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Mensajes extends CI_Controller{
        
        function __construct(){
            parent::__construct();
            $this->load->model('MensajesModel');
        }

        public function index(){
            if ($this->session->userdata('login')) {
                $where = ['estado' => ESTADO_MENSAJE_ACTIVO];
                $datos['mensajes'] = $this->MensajesModel->get(['*'],$where);
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('mensajes/listado',$datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function actualizar(){
            $id = $this->input->post('id');
            $titulo = $this->input->post('titulo');
            $mensaje = $this->input->post('mensaje');
            $hasta = $this->input->post('hasta');
            $estado = $this->input->post('estado');
            $publico = $this->input->post('publico');
            if(!$hasta){
                $hasta = null;
            }
            if (isset($titulo) && !empty($titulo) && isset($mensaje) && !empty($mensaje)){
                $data = array(
                    'titulo' => $titulo,
                    'mensaje' => $mensaje,
                    'estado' => $estado,
                    'hasta' => $hasta,
                    'publico' => $publico
                );
                $where = ['idMensaje' => $id];
                $this->MensajesModel->update($data,$where);
                header('location: ' . base_url() . "Mensajes");
            }else{
                $this->datos['error'] = 'Los campos Título y Mensaje son obligatorios';
                $this->formulario();
            }
        }
        public function formulario($error = null){
            if ($this->session->userdata('login')) {
                $datos['error'] = null;
                if($error){
                    $datos['error'] = $error;
                }
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('mensajes/formulario',$datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function modificar(){
            if ($this->session->userdata('login')) {
                $mensaje = $this->input->get('mensaje');
                $where = ['idMensaje' => $mensaje];
                $datos['mensaje'] = $this->MensajesModel->get(['*'],$where,true);
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('mensajes/modificar',$datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function set(){
            $titulo = $this->input->post('titulo');
            $mensaje = $this->input->post('mensaje');
            $hasta = $this->input->post('hasta');
            $publico = $this->input->post('publico');
            $estado = ESTADO_MENSAJE_ACTIVO;
            if (isset($titulo) && !empty($titulo) && isset($mensaje) && !empty($mensaje)){
                $data = array(
                    'idMensaje' => null,
                    'titulo' => $titulo,
                    'mensaje' => $mensaje,
                    'estado' => $estado,
                    'hasta' => $hasta,
                    'publico' => $publico
                );
                $this->MensajesModel->set($data);
                header('location: ' . base_url() . "Mensajes");
            }else{
                $this->datos['error'] = 'Los campos Título y Mensaje son obligatorios';
                $this->formulario();
            }
        }
    }
?>
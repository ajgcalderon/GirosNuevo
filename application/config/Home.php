<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Home extends CI_Controller{

        function __construct(){
            parent::__construct();
            $this->load->model('TransaccionesModel');
        }

        public function index(){
            if($this->session->userdata('login')){
                header('location: ' . base_url());
            }else{
                $datos['tasa'] = $this->TransaccionesModel->getTasa();
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('home/home',$datos);
                $this->load->view('overall/footer');
            }
        }
        public function transaccion(){
            $transacciones = $this->input->get('transaccion');
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('home/transaccion');
            $this->load->view('overall/footer');
        }
    }
?>

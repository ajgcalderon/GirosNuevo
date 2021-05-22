<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Cuentas extends CI_Controller{
        private $datos;

        function __construct(){
            parent::__construct();
            $this->load->model('CuentasModel');
        }

        function index(){
            
            $this->cuentasModel->getCuentas();
        }
        public function comisiones(){
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('');
            $this->load->view('overall/footer');
        }
        private function transferencias(){
            $hoy = date('Y-m-d');
        }
        public function getCuentas(){
            $persona = $this->input->get('persona');
            $where = ['girador' => $persona];
            $this->datos['cuentas'] = $this->CuentasModel->getCuentas('numero, banco, tipo',$where);
            $this->load->view('generales/cuentasPrueba',$this->datos);
        }
        
    }
?>
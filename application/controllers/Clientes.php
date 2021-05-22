<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Clientes extends CI_Controller{

        function __construct(){
            parent::__construct();
            $this->load->model('ClientesModel');
            $this->load->model('TransaccionesModel');
            $this->load->model('UsuariosModel');
        }
        public function index(){
            $datos = array();
            if($_GET){
                $datos['tiendaSeleccionada'] = $this->input->get('tienda');
                $datos['boton'] = true;
                if(isset($datos['tiendaSeleccionada']) && $datos['tiendaSeleccionada']){
                    $datos['clientes'] = $this->ClientesPorTienda($datos['tiendaSeleccionada']);
                }else{
                    $datos['clientes'] = $this->ClientesModel->getClientes(['*'],[],[],false);
                }
            }else{
                $datos['clientes'] = $this->ClientesModel->getClientes(['*'],[],[],false);
                $datos['tiendaSeleccionada'] = null;
            }
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE];
                    break;
                case USUARIO_DISTRIBUIDOR:
                    $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.grupo'=>$this->session->userdata('grupo')];
                break;
                case USUARIO_SUBDISTRIBUIDOR:
                    $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];

                break;
            }
            $datos['listaTiendas'] = $this->UsuariosModel->getPersonal(['*'],$where);
            // print_r($datos['clientes']);
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('clientes/list',$datos);
            $this->load->view('overall/footer');
        }
        private function ClientesPorTienda($tienda){
            // Consulta los Clientes registrados por cada Tienda en las Tranascciones
            $transacciones = $this->TransaccionesModel->getTransaccionesGeneral(['*'],['tienda'=>$tienda]);
            $ClientsSearch = [];
            foreach ($transacciones as $transaccion){
                if($transaccion->cliente){
                    $ClientsSearch[] = $transaccion->cliente;
                }
            }
            // Fin de la consulta del Listado de clientes por tienda
            // Ordena y eliminar los Valores repetidos
            sort($ClientsSearch);
            for ($i=0; $i < count($ClientsSearch)-1; $i++) { 
                if ($ClientsSearch[$i] == $ClientsSearch[$i+1]) {
                    $ClientsSearch[$i] = null;
                }
            }
            // Fin de la eliminación de los valores repetidos
            // Reorganiza el listado de clientes a consultar en un nuevo array
            $Clients2 = [];
            foreach ($ClientsSearch as $key) {
                if($key){
                    array_push($Clients2,$key);
                }
            }
            $clientes = [];
            // Fin de la Reorganización
            // Extrae los clientes de la Base de Datos
            foreach ($Clients2 as $key) {
                $clientes[] = $this->ClientesModel->getClientes(['*'],['idClientes'=>$key],[],true);
            }
            // Fin de Extraccion de Clientes
            return $clientes;
        }
    }
?>

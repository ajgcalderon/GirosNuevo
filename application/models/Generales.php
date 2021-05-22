<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Generales extends CI_Controller{

        private $datos = array();

        function __construct(){
            parent::__construct();
            $this->load->model('TransferenciasModel');
            $this->load->model('TransaccionesModel');
            $this->load->model('ReclamosModel');
            $this->load->helper(array('form'));
        }

        public function index(){
            if ($this->session->userdata('login')) {
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->transferenciasDevueltas();
                $this->transaccionesPendientes();
                $this->getReclamos();
                $this->transferidoHoy();
                // $this->transferidoHoy2();
                $this->pendientesBanco();
                if ($this->session->userdata('tipo') != USUARIO_TIENDA) {
                    $this->resumenSaldo();
                }
                // $this->cuentasGiradores();
                $this->load->view('generales/home',$this->datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }

        private function transferenciasRealizadas(){
            switch ($this->session->userdata('tipo')) {
                case 1:
                    $transferencias = $this->TransferenciasModel->getTransferenciasV1(10);
                    break;
                case 2:
                    $grupo = $this->session->userdata('grupo');
                    $transferencias = $this->TransferenciasModel->getTransferencias();
                    
                    break;
                case 3:
                    $girador = $this->session->userdata('id');
                    $transferencias = $this->TransferenciasModel->getTransferenciasV3(1,$girador,10);
                    break;
            }
            return $transferencias;
        }

        private function transferenciasDevueltas(){
            $hoy = date('Y-m-d',time());
            switch ($this->session->userdata('tipo')) {
                case 1 || 4:
                    $where = [];
                    break;
                case 2:
                    $grupo = $this->session->userdata('grupo');
                    $where = ['gvzla_usuarios.grupo' => $grupo];
                    break;
                case 3:
                    $girador = $this->session->userdata('id');
                    $where = ['gvzla_usuarios.id' => $girador];
                    break;
            }
            $devueltas = $this->TransferenciasModel->getTransferenciasDevueltas($hoy,$where);
            $this->datos['transferenciasDevueltas'] = count($devueltas);
        }
        
        private function transferenciasHoy(){
            $hoy = date('Y-m-d',time());
            switch ($this->session->usrdata('tipo')){
                case 1:
                    $where = ['fecha' => $hoy];
                    break;
                case 2:
                    $grupo = $this->session->userdata('grupo');
                    $where = ['fecha' => $hoy, 'gvzla_usuarios.grupo' => $grupo];
                    break;
                case 3:
                    $girador = $this->session->userdata('id');
                    $where = ['fecha' => $hoy, 'gvzla_usuarios.id' => $girador];
                    
                    break;
                case 4:
                    
                    $where = ['fecha' => $hoy];
                    break;
            }
            $transferenciasHoy = $this->TransferenciasModel->getTransferencias(array('fecha' => $hoy));
            return count($transferenciasHoy);
        }

        private function transaccionesPendientes(){
            if($this->session->userdata('tipo') != USUARIO_SUPERVISOR){
                switch ($this->session->userdata('tipo')){
                    case USUARIO_ADMINISTRADOR:
                        $where = ['estado' => ESTADO_TRANSACCION_PENDIENTE];
                        break;
                    case USUARIO_GIRADOR:
                        $girador = $this->session->userdata('id');
                        $where = ['estado' => ESTADO_TRANSACCION_PENDIENTE, 'girador' => $girador];
                        break;
                    case USUARIO_TIENDA:
                        $tienda = $this->session->userdata('id');
                        $where = ['estado' => ESTADO_TRANSACCION_PENDIENTE, 'tienda' => $tienda];
                        break;
                }
                $transaccionesPendientes = $this->TransaccionesModel->getTransaccionesGeneral(array ('*'),$where);
            }else{
                $grupo = $this->session->userdata('grupo');
                $where = ['transacciones.estado' => ESTADO_TRANSACCION_PENDIENTE, 'usuarios.grupo' => $grupo];
                $transaccionesPendientes = $this->TransaccionesModel->getTransaccionesSupervisor(array('*'),$where);
            }
                
            $this->datos['transaccionesCount'] = count($transaccionesPendientes);
        }
        
        private function transferidoHoy(){
            $hoy = date('Y-m-d',time());
            $this->datos['transferidoHoy'] = 0;
            $this->datos['transferenciasCount'] = 0;
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    $select = array(DB_PREFIX."transacciones.monto as 'monto'");
                    $where = ['fecha'=>HOY];
                    break;
                case USUARIO_SUPERVISOR:
                    $select= array(DB_PREFIX."transacciones.monto as 'monto'");
                    $grupo = $this->session->userdata('grupo');
                    $where = ['usuarios.grupo' => $grupo,'fecha'=> HOY];
                    break;
                case USUARIO_GIRADOR:
                    $select= array(DB_PREFIX."transacciones.monto as 'monto'");
                    $girador = $this->session->userdata('id');
                    $where = ['usuarios.id' => $girador,'fecha'=> HOY];
                    break;
                case USUARIO_TIENDA:
                    $select = array(DB_PREFIX."transacciones.pesos as 'monto'");
                    $tienda = $this->session->userdata('id');
                    $where = ['transacciones.tienda' => $tienda,'fecha'=> HOY];
                    break;
            }
            $transferencias = $this->TransferenciasModel->getTransferidoHoy($select,$where);
            foreach ($transferencias as $transferencia) {
                $this->datos['transferidoHoy'] += $transferencia->monto;
                $this->datos['transferenciasCount'] += 1;
            }
        }

        private function transferidoHoy2(){
            $this->datos['transferidoHoy'] = 0;
            $hoy = date('Y-m-d');
            switch ($this->session->userdata('tipo')) {
                case 1:
                    $where = [];
                    break;
                case 2:
                    $grupo = $this->session->userdata('grupo');
                    $where = ['usuarios.grupo' => $grupo];
                    break;
                case 3:
                    $girador = $this->session->userdata('id');
                    $where = ['usuarios.id' => $girador];
                    break;
            }
            $transferencias = $this->TransferenciasModel->getTransferidoHoy2($hoy,$where);
            $this->datos['transferidoHoy'] = $transferencias->monto;
        }

        private function pendientesBanco(){
            if ($this->session->userdata('tipo') == 3) {
                $girador = $this->session->userdata('id');
                $where = ['estado' => ESTADO_TRANSACCION_PENDIENTE, 'girador' => $girador];
            }elseif ($this->session->userdata('tipo') == 4){
                $tienda = $this->session->userdata('id');
                $where = ['estado' => ESTADO_TRANSACCION_PENDIENTE, 'tienda' => $tienda];
            }else{
                $where = ['estado' => ESTADO_TRANSACCION_PENDIENTE];
            }
            $transacciones = $this->TransaccionesModel->pendientesPorBanco($where);
            $this->datos['monto100Banco'] = 0;
            $this->datos['cont100Banco'] = 0;
            $this->datos['montoBicentenario'] = 0;
            $this->datos['contBicentenario'] = 0;
            $this->datos['montoBCV'] = 0;
            $this->datos['contBCV'] = 0;
            $this->datos['montoBancoDeVenezuela'] = 0;
            $this->datos['contBancoDeVenezuela'] = 0;
            $this->datos['montoBancaribe'] = 0;
            $this->datos['contBancaribe'] = 0;
            $this->datos['montoBFC'] = 0;
            $this->datos['contBFC'] = 0;
            $this->datos['montoMercantil'] = 0;
            $this->datos['contMercantil'] = 0;
            $this->datos['montoBNC'] = 0;
            $this->datos['contBNC'] = 0;
            $this->datos['montoBOD'] = 0;
            $this->datos['contBOD'] = 0;
            $this->datos['montoProvincial'] = 0;
            $this->datos['contProvincial'] = 0;
            $this->datos['montoExterior'] = 0;
            $this->datos['contExterior'] = 0;
            $this->datos['montoBancoDelTesoro'] = 0;
            $this->datos['contBancoDelTesoro'] = 0;
            $this->datos['montoBancrecer'] = 0;
            $this->datos['contBancrecer'] = 0;
            $this->datos['montoBanesco'] = 0;
            $this->datos['contBanesco'] = 0;
            $this->datos['montoSofitasa'] = 0;
            $this->datos['contSofitasa'] = 0;
            foreach ($transacciones as $transaccion) {
                switch ($transaccion->banco) {
                    case '100% Banco':
                        $this->datos['monto100Banco'] += $transaccion->monto;
                        $this->datos['cont100Banco'] += 1;
                        break;
                    case 'Banco Bicentenario':
                        $this->datos['montoBicentenario'] += $transaccion->monto;
                        $this->datos['contBicentenario'] += 1;
                        break;
                    case 'Banco Central de Venezuela':
                        $this->datos['montoBCV'] += $transaccion->monto;
                        $this->datos['contBCV'] += 1;
                        break;
                    case 'Banco de Venezuela S.A.I.C.A':
                        $this->datos['montoBancoDeVenezuela'] += $transaccion->monto;
                        $this->datos['contBancoDeVenezuela'] += 1;
                        break;
                    case 'Banco del Caribe C.A.':
                        $this->datos['montoBancaribe'] += $transaccion->monto;
                        $this->datos['contBancaribe'] += 1;
                        break;
                    case 'Banco Fondo Comun':
                        $this->datos['montoBFC'] += $transaccion->monto;
                        $this->datos['contBFC'] += 1;
                        break;
                    case 'Banco Mercantil':
                        $this->datos['montoMercantil'] += $transaccion->monto;
                        $this->datos['contMercantil'] += 1;
                        break;
                    case 'Banco Nacional de Credito':
                        $this->datos['montoBNC'] += $transaccion->monto;
                        $this->datos['contBNC'] += 1;
                        break;
                    case 'Banco Occidental de Descuentos':
                        $this->datos['montoBOD'] += $transaccion->monto;
                        $this->datos['contBOD'] += 1;
                        break;
                    case 'Banco Provincial':
                        $this->datos['montoProvincial'] += $transaccion->monto;
                        $this->datos['contProvincial'] += 1;
                        break;
                    case 'Banco Exterior':
                        $this->datos['montoExterior'] += $transaccion->monto;
                        $this->datos['contExterior'] += 1;
                        break;
                    case 'Banco del Tesoro':
                        $this->datos['montoBancoDelTesoro'] += $transaccion->monto;
                        $this->datos['contBancoDelTesoro'] += 1;
                        break;
                    case 'Bancrecer':
                        $this->datos['montoBancrecer'] += $transaccion->monto;
                        $this->datos['contBancrecer'] += 1;
                        break;
                    case 'Banesco':
                        $this->datos['montoBanesco'] += $transaccion->monto;
                        $this->datos['contBanesco'] += 1;
                        break;
                    case 'Sofitasa':
                        $this->datos['montoSofitasa'] += $transaccion->monto;
                        $this->datos['contSofitasa'] += 1;
                        break;
                }
            }
        }

        private function resumenSaldo(){
            $cuentas = $this->getCuentas();
            $this->datos['cuentasGiradores'] = [];
            if ($this->session->userdata('tipo')!=3){
                foreach ($cuentas as $cuenta) {
                    if (!array_key_exists("$cuenta->nombre",$this->datos['cuentasGiradores'])) {
                        $this->datos['cuentasGiradores']["{$cuenta->nombre}"]['id'] = $cuenta->id;
                        $this->datos['cuentasGiradores']["$cuenta->nombre"]['saldo'] = 0;
                        
                    }
                    $saldoNuevo = $this->datos['cuentasGiradores']["{$cuenta->nombre}"]['saldo'] + $cuenta->saldo;
                    $this->datos['cuentasGiradores']["$cuenta->nombre"]['saldo'] =  $saldoNuevo;
                }
            }else{
                foreach ($cuentas as $cuenta) {
                    if (!array_key_exists("$cuenta->nombre",$this->datos['cuentasGiradores'])) {
                        $this->datos['cuentasGiradores']["$cuenta->nombre"] = 0;
                        
                    }
                    $saldoNuevo = $this->datos['cuentasGiradores']["{$cuenta->nombre}"] + $cuenta->saldo;
                    $this->datos['cuentasGiradores']["$cuenta->nombre"] =  $saldoNuevo;
                }
            }
        }

        private function getCuentas(){
            $this->load->model('CuentasModel');
            switch ($this->session->userdata('tipo')) {
                case 1:
                    $where= ['usuarios.estado' => ESTADO_USUARIO_ENEABLE];
                    $select= "girador, saldo,gvzla_usuarios.nombreUsuario as 'nombre', gvzla_usuarios.id as 'id'";
                    break;
                case 2:
                    $grupo = $this->session->userdata('grupo');
                    $where= ['gvzla_usuarios.grupo' => $grupo,'usuarios.estado' => ESTADO_USUARIO_ENEABLE];
                    $select= "girador, saldo, gvzla_usuarios.nombreUsuario as 'nombre', gvzla_usuarios.id as 'id'";
                    break;
                case 3:
                    $girador = $this->session->userdata('id');
                    $where= ['girador' => $girador];
                    $select= "girador, saldo, banco as 'nombre'";
                    break;
            }
            return $this->CuentasModel->getCuentas($select,$where);
        }

        private function getReclamos(string $select = '*'){
            switch($this->session->userdata('tipo')){
                case 1:
                    $where = ['reclamos.estado' => 1];
                    break;
                case 2:
                    $grupo = $this->session->userdata('grupo');
                    $where = ['reclamos.estado' => 1,'usuarios.grupo' => $grupo];
                    break;
                case 3:
                    $girador = $this->session->userdata('id');
                    $where = ['reclamos.estado' => 1,'usuarios.id' => $girador];
                    break;
                case 4:
                    $tienda = $this->session->userdata('id');
                    $where = ['transacciones.tienda' => $tienda];
            }
            $reclamos = $this->ReclamosModel->getReclamos($select,$where);
            $this->datos['reclamos'] = count($reclamos);
        }

        private function getProcesadoPesos(){
            $select = array('*');
            $where = array('transferencias.fecha'=>"CAST(".date('Y-m-d')." AS DATE)",'transacciones.estado'=>ESTADO_TRANSACCION_PROCESADO);
            $transacciones = $this->TransaccionesModel->getTransaccionesGeneral($select,$where);
            $total = 0;
            foreach($transacciones as $transaccion){
                $total =+ $transaccion->monto;
            }
            return $total;
        }
        
        #pruebas
        public function prueba($error = ''){
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('generales/pruebas', array('error' => $error));
            $this->load->view('overall/footer');
        }
        
        public function subirArchivo(){
            $config['upload_patch'] = './captures/';
            $config['allowed_types'] = '*';
            $config['max_size'] = 0;
            $config['max_width'] = 0;
            $config['max_height'] = 0;
            $config['file_name'] = 'Captura.png';
            $this->load->library('upload',$config);
            print_r($this->upload->data());
            if (!$this->upload->do_upload('capture')){
                $error = $this->upload->display_errors();
                $this->prueba($error);
            }else{
                header('location: '. base_url());
                
            }
        }
        public function subir(){
                $config['upload_path']          = './captures/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 0;
                $config['max_width']            = 0;
                $config['max_height']           = 0;
                $config['file_name']            = $this->session->userdata('nombreUsuario') . date('YmdHis');
                

                $this->upload->initialize($config);

                if ( ! $this->upload->do_upload('userfile'))
                {
                        $error = array('error' => $this->upload->display_errors());

                        $this->prueba($error);
                }
                else
                {
                        $data = array('upload_data' => $this->upload->data());

                        // header('location: ' . base_url());
                        $this->load->view('generales/success', $data);
                }
        }
    }
?>

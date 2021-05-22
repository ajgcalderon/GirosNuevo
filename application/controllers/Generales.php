<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Generales extends CI_Controller{

        private $datos = array();

        function __construct(){
            parent::__construct();
            $this->load->model('CuentasModel');
            $this->load->model('MensajesModel');
            $this->load->model('RecargasModel');
            $this->load->model('ReclamosModel');
            $this->load->model('TransaccionesModel');
            $this->load->model('TransferenciasModel');
            $this->load->helper(array('form'));
        }
        public function index(){
            if ($this->session->userdata('login')) {
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->transferenciasDevueltas();
                $this->transaccionesPendientes();
                $this->getReclamos();
                $this->getMensajes();
                $this->transferidoHoy();
                $this->getRecargas();
                // $this->transferidoHoy2();
                $this->pendienteBanco2();
                if ($this->session->userdata('tipo') != USUARIO_GIRADOR && $this->session->userdata('tipo') != USUARIO_SUPERVISOR) {
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
        public function formatoTransferido($value){
            if($value > 999999.99){
                $monto = $value/1000000;
                $monto = number_format($monto,2) . 'M';
            }elseif ($value > 9999.99) {
                $monto = $value/1000;
                $monto = number_format($monto,2) . 'K';
            }else {
                $monto = $value;
            }
            return $monto;
        }
        private function getCuentas(){
            $this->load->model('CuentasModel');
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    $where= ['usuarios.estado' => ESTADO_USUARIO_ENEABLE];
                    $select= "girador, saldo,gvzla_usuarios.nombreUsuario as 'nombre', gvzla_usuarios.id as 'id'";
                    break;
                case USUARIO_SUPERVISOR:
                    $grupo = $this->session->userdata('grupo');
                    $where= ['gvzla_usuarios.grupo' => $grupo,'usuarios.estado' => ESTADO_USUARIO_ENEABLE];
                    $select= "girador, saldo, gvzla_usuarios.nombreUsuario as 'nombre', gvzla_usuarios.id as 'id'";
                    break;
                case USUARIO_GIRADOR:
                    $girador = $this->session->userdata('id');
                    $where= ['girador' => $girador];
                    $select= "girador, saldo, banco as 'nombre'";
                    break;
            }
            return $this->CuentasModel->getCuentas($select,$where);
        }
        private function getMensajes(){
            $where = ['estado' => ESTADO_MENSAJE_ACTIVO,'publico !='=>PUBLICO_MENSAJES_CLIENTES];
            $this->datos['mensajes'] = $this->MensajesModel->get(['*'],$where);
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
        private function getRecargas(){
            $select = [DB_PREFIX . "personal.nombre as 'nombre'", DB_PREFIX . "personal.apellido AS 'apellido'",'fecha','monto','referencia',DB_PREFIX."recargastiendas.estado as 'estado'",'comprobante'];
            $where = ['recargastiendas.estado'=>ESTADO_RECARGA_ESPERA];
            $this->datos['recargas'] = $this->RecargasModel->getRecargas($select,$where);
        }
        private function getReclamos(array $select = ['*']){
            switch($this->session->userdata('tipo')){
                case USUARIO_ADMINISTRADOR:
                    $where = ['reclamos.estado' => RECLAMO_ESTADO_INICIADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO];
                    break;
                case USUARIO_SUPERVISOR:
                    $grupo = $this->session->userdata('grupo');
                    $where = ['reclamos.estado' => RECLAMO_ESTADO_INICIADO, 'usuarios.grupo' => $grupo, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO];
                    break;
                case USUARIO_GIRADOR:
                    $girador = $this->session->userdata('id');
                    $where = ['transacciones.girador' => $girador, 'reclamos.estado' => RECLAMO_ESTADO_INICIADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO];
                    break;
                case USUARIO_TIENDA:
                    $tienda = $this->session->userdata('id');
                    $where = ['reclamos.estado' => RECLAMO_ESTADO_INICIADO, 'transacciones.tienda' => $tienda, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO];
                    break;
                case USUARIO_DISTRIBUIDOR:
                    $where = ['reclamos.estado' => RECLAMO_ESTADO_INICIADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO,'usuarios.grupo'=>$this->session->userdata('grupo')];
                break;
                case USUARIO_SUBDISTRIBUIDOR:
                    $where = ['reclamos.estado' => RECLAMO_ESTADO_INICIADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                break;
            }
            if ($this->session->userdata('tipo') != USUARIO_ADMINISTRADOR && $this->session->userdata('tipo') != USUARIO_TIENDA && $this->session->userdata('tipo') != USUARIO_DISTRIBUIDOR && $this->session->userdata('tipo') != USUARIO_SUBDISTRIBUIDOR){
                $reclamos = $this->ReclamosModel->getReclamos($select,$where);
            }else{
                $reclamos = $this->ReclamosModel->getReclamosAdministrador($select,$where);
            }
            $this->datos['reclamos'] = count($reclamos);
        }
        private function pendienteBanco2(){
            if ($this->session->userdata('tipo') == USUARIO_GIRADOR) {
                $girador = $this->session->userdata('id');
                $where = ['estado' => ESTADO_TRANSACCION_PENDIENTE, 'girador' => $girador];
            }elseif ($this->session->userdata('tipo') == USUARIO_TIENDA){
                $tienda = $this->session->userdata('id');
                $where = ['estado' => ESTADO_TRANSACCION_PENDIENTE, 'tienda' => $tienda];
            }else{
                $where = ['estado' => ESTADO_TRANSACCION_PENDIENTE];
            }
            $transacciones = $this->datos['transaccionesPendientes'];
            $this->datos['bancos'] = [];
            $this->datos['totalPesosPendientes'] = 0;
            $this->datos['totalBsPendientes'] = 0;
            $this->datos['totalConteo'] = 0;
            foreach($transacciones as $transaccion){
                if(!array_key_exists($transaccion->banco,$this->datos['bancos'])){
                    $this->datos['bancos'][$transaccion->banco]['monto'] = 0;
                    $this->datos['bancos'][$transaccion->banco]['pesos'] = 0;
                    $this->datos['bancos'][$transaccion->banco]['conteo'] = 0;
                }
                $this->datos['bancos'][$transaccion->banco]['monto'] += $transaccion->monto;
                $this->datos['bancos'][$transaccion->banco]['pesos'] += $transaccion->pesos;
                $this->datos['bancos'][$transaccion->banco]['conteo'] += 1;
                $this->datos['totalPesosPendientes'] += $transaccion->pesos;
                $this->datos['totalBsPendientes'] += $transaccion->monto;
                $this->datos['totalConteo'] ++;
            }
        }
        private function resumenSaldo(){
            switch($this->session->userdata('tipo')){
                case USUARIO_ADMINISTRADOR:
                $tiendas = $this->CuentasModel->getCuentasTiendas(['*'],['usuarios.estado'=>ESTADO_CUENTA_ENEABLE]);
                break;
                case USUARIO_TIENDA:
                $tienda = $this->CuentasModel->getCuentasTiendas(['*'],['usuarios.estado'=>ESTADO_CUENTA_ENEABLE,'usuarios.id'=>$this->session->userdata('id')],true);
                break;
                case USUARIO_DISTRIBUIDOR:
                    $tiendas = $this->CuentasModel->getCuentasTiendas(['*'],['usuarios.estado'=>ESTADO_CUENTA_ENEABLE,'usuarios.grupo'=>$this->session->userdata('grupo')]);
                break;
                case USUARIO_SUBDISTRIBUIDOR:
                    $tiendas = $this->CuentasModel->getCuentasTiendas(['*'],['usuarios.estado'=>ESTADO_CUENTA_ENEABLE,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')]);

                break;
            }
            $this->datos['saldoTiendas'] = [];
            if ($this->session->userdata('tipo')==USUARIO_ADMINISTRADOR || $this->session->userdata('tipo')==USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo')==USUARIO_SUBDISTRIBUIDOR){
                foreach ($tiendas as $tienda) {
                    if (!array_key_exists($tienda->nombre . $tienda->id,$this->datos['saldoTiendas'])) {
                        $this->datos['saldoTiendas'][$tienda->nombre . $tienda->id]['id'] = $tienda->id;
                        $this->datos['saldoTiendas'][$tienda->nombre . $tienda->id]['nombre'] = $tienda->nombre . ' ' . $tienda->apellido;
                        $this->datos['saldoTiendas'][$tienda->nombre . $tienda->id]['saldo'] = $tienda->pesos;
                    }
                }
            }elseif ($this->session->userdata('tipo') == USUARIO_TIENDA && $this->session->userdata('grupo') != GRUPO_EMPRESA){
                if (!array_key_exists($tienda->nombre,$this->datos['saldoTiendas'])) {
                    $this->datos['saldoTiendas'][$tienda->nombre]['nombre'] = $tienda->nombre . ' ' . $tienda->apellido;
                    $this->datos['saldoTiendas'][$tienda->nombre]['saldo'] =  $tienda->pesos;
                }
            }
        }
        private function transaccionesPendientes(){
            $select = ["referenciaTransaccion", "monto", "cuentaBeneficiario", DB_PREFIX."cuentasbeneficiarios.banco", DB_PREFIX."beneficiarios.nombre as 'nombre'", "pesos"];
            if($this->session->userdata('tipo') != USUARIO_SUPERVISOR){
                switch ($this->session->userdata('tipo')){
                    case USUARIO_ADMINISTRADOR:
                        $where = ['transacciones.estado !='=> ESTADO_TRANSACCION_PROCESADO,'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO];
                        break;
                    case USUARIO_GIRADOR:
                        $girador = $this->session->userdata('id');
                        $where = ['transacciones.estado !='=> ESTADO_TRANSACCION_PROCESADO,'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO, 'girador' => $girador];
                        break;
                    case USUARIO_TIENDA:
                        $tienda = $this->session->userdata('id');
                        $where = ['transacciones.estado !='=> ESTADO_TRANSACCION_PROCESADO,'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO, 'transacciones.tienda' => $tienda];
                        break;
                    case USUARIO_DISTRIBUIDOR:
                        $where = ['transacciones.estado !='=> ESTADO_TRANSACCION_PROCESADO,'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO,'usuarios.grupo'=>$this->session->userdata('grupo')];
                        break;
                    case USUARIO_SUBDISTRIBUIDOR:
                        $where = ['transacciones.estado !='=> ESTADO_TRANSACCION_PROCESADO,'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                        break;
                }
                $this->datos['transaccionesPendientes'] = $this->TransaccionesModel->getTransaccionesGeneral($select,$where);
            }else{
                $grupo = $this->session->userdata('grupo');
                $where = ['transacciones.estado !=' => ESTADO_TRANSACCION_CANCELADO,'transacciones.estado !=' => ESTADO_TRANSACCION_PROCESADO, 'usuarios.grupo' => $grupo];
                $this->datos['transaccionesPendientes'] = $this->TransaccionesModel->getTransaccionesSupervisor($select,$where);
            }
                
            $this->datos['transaccionesCount'] = count($this->datos['transaccionesPendientes']);
        }
        private function transferenciasDevueltas(){
            $hoy = date('Y-m-d',time());
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    $where = ['estadoTransferencia'=>2];
                    break;
                case USUARIO_TIENDA:
                    $where = ['transacciones.tienda'=>$this->session->userdata('id'),'estadoTransferencia'=>2];
                    break;
                case USUARIO_SUPERVISOR:
                    $grupo = $this->session->userdata('grupo');
                    $where = ['gvzla_usuarios.grupo' => $grupo,'estadoTransferencia'=>2];
                    break;
                case USUARIO_GIRADOR:
                    $girador = $this->session->userdata('id');
                    $where = ['gvzla_usuarios.id' => $girador,'estadoTransferencia'=>2];
                    break;
                case USUARIO_DISTRIBUIDOR:
                    $where = ['usuarios.grupo'=>$this->session->userdata('grupo'),'estadoTransferencia'=>2];
                break;
                case USUARIO_SUBDISTRIBUIDOR:
                    $where = ['usuarios.subgrupo'=>$this->session->userdata('subgrupo'),'estadoTransferencia'=>2];
                break;
                    
            }
            if ($this->session->userdata('tipo') != USUARIO_DISTRIBUIDOR && $this->session->userdata('tipo') != USUARIO_SUBDISTRIBUIDOR) {
                $devueltas = $this->TransferenciasModel->getTransferenciasDevueltas($hoy,$where);
            } else {
                $devueltas = $this->TransferenciasModel->getTransferenciasDVDist($where);
            }
            
            $this->datos['transferenciasDevueltas'] = count($devueltas);
        }
        private function transferenciasRealizadas(){
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    $transferencias = $this->TransferenciasModel->getTransferenciasV1(10);
                    break;
                case USUARIO_SUPERVISOR:
                    $grupo = $this->session->userdata('grupo');
                    $transferencias = $this->TransferenciasModel->getTransferencias();
                    
                    break;
                case USUARIO_GIRADOR:
                    $girador = $this->session->userdata('id');
                    $transferencias = $this->TransferenciasModel->getTransferenciasV3(1,$girador,10);
                    break;
            }
            return $transferencias;
        }
        private function transferidoHoy(){
            $hoy = date('Y-m-d');
            $this->datos['totalTransferidoHoy'] = 0;
            $this->datos['transferenciasCount'] = 0;
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    $select = array(DB_PREFIX."transacciones.monto as 'monto'");
                    $where = ['fecha'=>$hoy,'estadoTransferencia' => ESTADO_TRANFERENCIAS_EJECUTADA];
                    break;
                case USUARIO_SUPERVISOR:
                    $select= array(DB_PREFIX."transacciones.monto as 'monto'");
                    $grupo = $this->session->userdata('grupo');
                    $where = ['usuarios.grupo' => $grupo,'fecha'=> $hoy,'estadoTransferencia' => ESTADO_TRANFERENCIAS_EJECUTADA];
                    break;
                case USUARIO_GIRADOR:
                    $select= array(DB_PREFIX."transacciones.monto as 'monto'");
                    $girador = $this->session->userdata('id');
                    $where = ['usuarios.id' => $girador,'fecha'=> $hoy,'estadoTransferencia' => ESTADO_TRANFERENCIAS_EJECUTADA];
                    break;
                case USUARIO_TIENDA:
                    $select = array(DB_PREFIX."transacciones.pesos as 'monto'");
                    $tienda = $this->session->userdata('id');
                    $where = ['transacciones.tienda' => $tienda,'fecha'=> $hoy,'estadoTransferencia' => ESTADO_TRANFERENCIAS_EJECUTADA];
                    break;
                case USUARIO_DISTRIBUIDOR:
                    $select = array(DB_PREFIX."transacciones.pesos as 'monto'");
                    $where = ['usuarios.grupo'=>$this->session->userdata('grupo'),'fecha'=> $hoy,'estadoTransferencia' => ESTADO_TRANFERENCIAS_EJECUTADA];
                break;
                case USUARIO_SUBDISTRIBUIDOR:
                    $select = array(DB_PREFIX."transacciones.pesos as 'monto'");
                    $where = ['usuarios.subgrupo'=>$this->session->userdata('subgrupo'),'fecha'=> $hoy,'estadoTransferencia' => ESTADO_TRANFERENCIAS_EJECUTADA];
                break;
            }
            if($this->session->userdata('tipo') != USUARIO_DISTRIBUIDOR && $this->session->userdata('tipo') != USUARIO_SUBDISTRIBUIDOR){
                $transferencias = $this->TransferenciasModel->getTransferidoHoy($select,$where);
            }else{
                $transferencias = $this->TransferenciasModel->getTransferidoHoyDist($select,$where);
            }
            foreach ($transferencias as $transferencia) {
                $this->datos['totalTransferidoHoy'] += $transferencia->monto;
                $this->datos['transferenciasCount'] += 1;
            }
            $this->datos['transferidoHoy'] = $this->formatoTransferido($this->datos['totalTransferidoHoy']);
        }
        private function transferidoHoy2(){
            $this->datos['transferidoHoy'] = 0;
            $hoy = date('Y-m-d');
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    $where = [];
                    break;
                case USUARIO_SUPERVISOR:
                    $grupo = $this->session->userdata('grupo');
                    $where = ['usuarios.grupo' => $grupo];
                    break;
                case USUARIO_GIRADOR:
                    $girador = $this->session->userdata('id');
                    $where = ['usuarios.id' => $girador];
                    break;
            }
            $transferencias = $this->TransferenciasModel->getTransferidoHoy2($hoy,$where);
            $this->datos['transferidoHoy'] = $transferencias->monto;
        }
    }
?>

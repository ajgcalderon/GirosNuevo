<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Reclamos extends CI_Controller{

        private $datos;

        function __construct(){
            parent::__construct();
            $this->load->model('TransaccionesModel');
            $this->load->model('TransferenciasModel');
            $this->load->model('ReclamosModel');
            $this->load->model('UsuariosModel');
            $this->load->model('CuentasModel');
        }
        # Cargar vistas
        public function index(){
            if($this->session->userdata('login')){
                // $this->getReclamos();
                if($_GET){
                    $this->datos['estado'] = $this->input->get('estado');
                    $this->datos['tiendaSeleccionada'] = $this->input->get('tienda');
                    if(empty($this->datos['estado'])){
                        $this->datos['estado'] = RECLAMO_ESTADO_INICIADO;
                    }
                    if(empty($this->datos['tiendaSeleccionada'])){
                        $this->datos['tiendaSeleccionada'] = null;
                    }
                    $this->datos['boton'] = true;
                }else{
                    $this->datos['estado'] = RECLAMO_ESTADO_INICIADO;
                    $this->datos['tiendaSeleccionada'] = null;
                }
                $this->datos['reclamos'] = $this->getReclamos($this->datos['estado'],$this->datos['tiendaSeleccionada']);
                if($this->session->userdata('tipo') != USUARIO_TIENDA){
                    switch ($this->session->userdata('tipo')) {
                        case USUARIO_ADMINISTRADOR:
                            $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.tipoUsuario !='=>USUARIO_ADMINISTRADOR,'usuarios.tipoUsuario !='=>USUARIO_GIRADOR];
                        break;
                        case USUARIO_DISTRIBUIDOR:
                            $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.grupo'=>$this->session->userdata('grupo')];
                        break;
                        case USUARIO_SUBDISTRIBUIDOR:
                            $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                        break;
                        case USUARIO_GIRADOR:
                            $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.tipoUsuario !='=>USUARIO_ADMINISTRADOR,'usuarios.tipoUsuario !='=>USUARIO_GIRADOR];
                        break;
                        default:
                            $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.tipoUsuario !='=>USUARIO_ADMINISTRADOR,'usuarios.tipoUsuario !='=>USUARIO_GIRADOR];

                        break;
                    }
                    $this->datos['listaTiendas'] = $this->getPersonal(['*'],$where);
                }
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('reclamos/listado',$this->datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function buscarTransaccion(){
            $transaccion = $this->input->get('transaccion');
            $this->busquedaTransacciones($transaccion,true);
            $this->getTransferencias($transaccion);
            $this->load->view('reclamos/mostrarTransaccion',$this->datos);
        }
        public function detalles($transaccion = null){
            if($_GET){
                $transaccion = $this->input->get('transaccion');
                $this->datos['estado'] = $this->input->get('estado');
                $this->datos['tienda'] = $this->input->get('tienda');
            }
            $this->datos['transaccion'] = $this->getTransaccion($transaccion);
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('reclamos/detalles',$this->datos);
            $this->load->view('overall/footer');
        }
        public function registrar(){
            $transaccion = $this->input->post('referencia');
            $observacion = $this->input->post('observacion');
            $set = ['id' => null, 'transaccion' => $transaccion, 'estado' => RECLAMO_ESTADO_INICIADO,'fecha' => null, 'respuesta' => null, 'observacion' => $observacion, 'motivo' => null];
            $this->ReclamosModel->setReclamo($set);
            header('location: ' . base_url());
        }
        public function registro(){
            if($this->session->userdata('login')){
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('reclamos/registro');
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function respuesta(){
            $error = $this->input->get('error');
            $motivo = $this->input->get('motivo');
            $transaccion = $this->input->get('transaccion');
            if(isset($error) && !empty($error)){
                $set['reclamo'] = ['respuesta' => $error, 'estado' => RECLAMO_ESTADO_RESUELTO, 'motivo' => $motivo];
                $where['reclamo'] = ['transaccion' => $transaccion, 'estado' => RECLAMO_ESTADO_INICIADO];
                $this->update($set['reclamo'],$where['reclamo']);

                $infoTransaccion = $this->TransaccionesModel->getTransaccionesGeneral(['tienda','pesos',DB_PREFIX."usuarios.tipoUsuario AS 'tipoUsuario'"],['referenciaTransaccion'=>$transaccion],[],true);
                $set['transaccion'] = ['estado' => $error];
                $where['transaccion'] = ['referenciaTransaccion' => $transaccion];
                $this->updateTransaccion($set['transaccion'],$where['transaccion']);
                
                if($infoTransaccion->tipoUsuario != USUARIO_ADMINISTRADOR && $error == ESTADO_TRANSACCION_CANCELADO){
                    $cuenta = $this->CuentasModel->getCuentasTiendas(['*'],['cuentastiendas.id'=>$infoTransaccion->tienda],true);
                    $saldoNuevo = $cuenta->pesos + $infoTransaccion->pesos;
                    $set['cuenta'] = ['pesos'=>$saldoNuevo];
                    $where['cuenta'] = ['id'=>$infoTransaccion->tienda];
                    $this->CuentasModel->updateCuentaTienda($set['cuenta'],$where['cuenta']);
                }
                header('location: ' . base_url() . 'Reclamos');
            }else{
                $this->datos['error'] = 'Por favor seleccione una respuesta';
                $this->datos['transaccion'] = $this->getReclamo($transaccion);
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('reclamos/detalles',$this->datos);
                $this->load->view('overall/footer');
            }
        }

        private function busquedaTransacciones($transaccion,bool $row = false){
            $where = ['referenciaTransaccion' => $transaccion];
            $this->datos['transaccion'] = $this->TransaccionesModel->getBusqueda($where,$row);
        }
        private function busquedaReclamos(){
            $busqueda = $this->input->get('busqueda');
            $where = ['referenciaTransaccion' => $busqueda, 'beneficiarios.nombre' => $busqueda, 'fechaInicio' => $busqueda, 'beneficiarios.cedula' => $busqueda, 'cuentasbeneficiarios.banco' => $busqueda, 'cuentaBeneficiario' => $busqueda];
            $this->datos['transacciones'] = $this->TransaccionesModel->getBusqueda($where);
        }
        private function getPersonal(array $select = array('*'),array $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE],bool $row = false){
            return $this->UsuariosModel->getPersonal($select,$where,$row);
        }
        private function getReclamo($reclamo){
            $select = ["fechaInicio AS 'fecha'", "referenciaTransaccion AS 'referencia'", DB_PREFIX."beneficiarios.cedula AS 'cedula'", DB_PREFIX."beneficiarios.nombre AS 'beneficiario'", DB_PREFIX."cuentasbeneficiarios.banco AS 'banco'", "cuentaBeneficiario AS 'cuenta'", "monto", "tasa", "pesos", DB_PREFIX."transacciones.estado AS 'estado'","tienda"];
            $where = ['referenciaTransaccion' => $reclamo,'transacciones.estado != '.ESTADO_TRANSACCION_PROCESADO,'transacciones.estado != '.ESTADO_TRANSACCION_CANCELADO];
            return $this->TransaccionesModel->getTransaccionesGeneral($select,$where,[],true);
        }
        private function getReclamos($estado,$tienda){
            if($estado == RECLAMO_ESTADO_INICIADO){
                $select = array(
                    DB_PREFIX . "transacciones.referenciaTransaccion as 'referencia'",
                    DB_PREFIX . "transacciones.fechaInicio as 'fecha'",
                    DB_PREFIX . "beneficiarios.cedula as 'cedula'",
                    DB_PREFIX . "beneficiarios.nombre as 'beneficiario'",
                    DB_PREFIX . "cuentasbeneficiarios.banco as 'banco'",
                    DB_PREFIX . "transacciones.cuentaBeneficiario as 'cuenta'",
                    DB_PREFIX . "transacciones.monto as 'monto'",
                    DB_PREFIX . "transacciones.tasa as 'tasa'",
                    DB_PREFIX . "transacciones.pesos as 'pesos'"
                );
                switch($this->session->userdata('tipo')){
                    case USUARIO_ADMINISTRADOR:
                        if($tienda){
                            $where = ['reclamos.estado' => $estado, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO, 'transacciones.tienda' => $tienda];
                        }else{
                            $where = ['reclamos.estado' => $estado, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO];
                        }
                        
                        break;
                    case USUARIO_SUPERVISOR:
                        $grupo = $this->session->userdata('grupo');
                        if($tienda){
                            $where = ['reclamos.estado' => $estado, 'usuarios.grupo' => $grupo, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO, 'transacciones.tienda' => $tienda];
                        }else{
                            $where = ['reclamos.estado' => $estado, 'usuarios.grupo' => $grupo, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO];
                        }
                        break;
                    case USUARIO_GIRADOR:
                        $girador = $this->session->userdata('id');
                        if($tienda){
                            $where = ['transacciones.girador' => $girador, 'reclamos.estado' => $estado, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO, 'transacciones.tienda' => $tienda];
                        }else{
                            $where = ['transacciones.girador' => $girador, 'reclamos.estado' => $estado, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO];
                        }
                        break;
                    case USUARIO_TIENDA:
                        $tienda = $this->session->userdata('id');
                        $where = ['reclamos.estado' => $estado, 'transacciones.tienda' => $tienda, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO];
                        break;
                    case USUARIO_DISTRIBUIDOR:
                        if($tienda){
                            $where = ['reclamos.estado' => $estado, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO, 'transacciones.tienda' => $tienda];
                        }else{
                            $where = ['reclamos.estado' => $estado, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO,'usuarios.grupo' => $this->session->userdata('grupo')];
                        }
                    break;
                    case USUARIO_SUBDISTRIBUIDOR:
                        if($tienda){
                            $where = ['reclamos.estado' => $estado, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO, 'transacciones.tienda' => $tienda];
                        }else{
                            $where = ['reclamos.estado' => $estado, 'transacciones.estado != ' => ESTADO_TRANSACCION_PROCESADO, 'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO,'usuarios.subgrupo' => $this->session->userdata('subgrupo')];
                        }
                    break;
                }
            }else{
                $select = array(
                    DB_PREFIX . "transacciones.referenciaTransaccion as 'referencia'",
                    DB_PREFIX . "transacciones.fechaInicio as 'fecha'",
                    DB_PREFIX . "beneficiarios.cedula as 'cedula'",
                    DB_PREFIX . "beneficiarios.nombre as 'beneficiario'",
                    DB_PREFIX . "cuentasbeneficiarios.banco as 'banco'",
                    DB_PREFIX . "transacciones.cuentaBeneficiario as 'cuenta'",
                    DB_PREFIX . "transacciones.monto as 'monto'",
                    DB_PREFIX . "transacciones.tasa as 'tasa'",
                    DB_PREFIX . "transacciones.pesos as 'pesos'",
                    "observacion AS 'reclamo'",
                    "motivo AS 'respuesta'"
                );
                switch($this->session->userdata('tipo')){
                    case USUARIO_ADMINISTRADOR:
                        if($tienda){
                            $where = ['reclamos.estado' => $estado, 'transacciones.tienda' => $tienda];
                        }else{
                            $where = ['reclamos.estado' => $estado];
                        }
                        break;
                    case USUARIO_SUPERVISOR:
                        $grupo = $this->session->userdata('grupo');
                        if($tienda){
                            $where = ['reclamos.estado' => $estado, 'usuarios.grupo' => $grupo, 'transacciones.tienda' => $tienda];
                        }else{
                            $where = ['reclamos.estado' => $estado, 'usuarios.grupo' => $grupo];
                        }
                        break;
                    case USUARIO_GIRADOR:
                        $girador = $this->session->userdata('id');
                        if($tienda){
                            $where = ['transacciones.girador' => $girador, 'reclamos.estado' => $estado, 'transacciones.tienda' => $tienda];
                        }else{
                            $where = ['transacciones.girador' => $girador, 'reclamos.estado' => $estado];
                            }
                        break;
                    case USUARIO_TIENDA:
                        $tienda = $this->session->userdata('id');
                        $where = ['reclamos.estado' => $estado, 'transacciones.tienda' => $tienda];
                        break;
                    case USUARIO_DISTRIBUIDOR:
                        if($tienda){
                            $where = ['reclamos.estado' => $estado, 'transacciones.tienda' => $tienda];
                        }else{
                            $where = ['reclamos.estado' => $estado, 'usuarios.grupo'=>$this->session->userdata('grupo')];
                        }
                    break;
                    case USUARIO_SUBDISTRIBUIDOR:
                        if($tienda){
                            $where = ['reclamos.estado' => $estado, 'transacciones.tienda' => $tienda];
                        }else{
                            $where = ['reclamos.estado' => $estado, 'usuarios.grupo'=>$this->session->userdata('grupo')];
                        }
                    break;
                }

            }
            if ($this->session->userdata('tipo') == USUARIO_GIRADOR || $this->session->userdata('tipo') == USUARIO_SUPERVISOR){
                return $this->ReclamosModel->getReclamos($select,$where);
            }else{
                return $this->ReclamosModel->getReclamosAdministrador($select,$where);
            }
        }
        private function getTransaccion($transaccion){
            $select = array(
                "observacion AS 'reclamo'",
                "motivo AS 'respuesta'",
                DB_PREFIX."reclamos.estado AS 'estadoReclamo'",
                DB_PREFIX."transacciones.monto",
                DB_PREFIX."transacciones.tasa", 
                DB_PREFIX."transacciones.pesos",
                DB_PREFIX."transacciones.tienda",  
                DB_PREFIX."transacciones.fechaInicio AS 'fecha'",
                DB_PREFIX."transacciones.cuentaBeneficiario AS 'cuenta'",
                DB_PREFIX."transacciones.referenciaTransaccion AS 'referencia'",
                DB_PREFIX."beneficiarios.cedula AS 'cedula'",
                DB_PREFIX."beneficiarios.nombre AS 'beneficiario'",
                DB_PREFIX . "cuentasbeneficiarios.banco AS 'banco'",
                DB_PREFIX."transacciones.estado AS 'estado'"
            );
            $where = ['referenciaTransaccion' => $transaccion];
            return $this->ReclamosModel->getReclamosAdministrador($select,$where,true);
            // DB_PREFIX."clientes.nombre AS 'nombreCliente'",
            // DB_PREFIX."clientes.telefono AS 'telefono'",
        }
        private function getTransferencias($transaccion,bool $row = true){
            $where = ['transacciones.referenciaTransaccion' => $transaccion,'estadoTransferencia'=>ESTADO_TRANFERENCIAS_EJECUTADA];
            $select = "captura";
            $this->datos['transferencia'] = $this->TransferenciasModel->getTransferencias($where,$select,$row);
        }
        private function update($set,$where){
            $this->ReclamosModel->update($set,$where);
        }
        private function updateTransaccion(array $set,array $where){
            $this->TransaccionesModel->updateEstado($set,$where);
        }
    }
?>
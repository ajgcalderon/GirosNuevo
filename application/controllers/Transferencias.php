<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Transferencias extends CI_Controller{

        private $datos = array();

        function __construct(){
            parent::__construct();
            $this->load->model('TransferenciasModel');
            $this->load->model('CuentasModel');
            $this->load->model('UsuariosModel');
            $this->load->model('TransaccionesModel');
        }

        public function index(){
            if ($this->session->userdata('login')) {
                if($_GET){
                    $this->datos['desde'] = $this->input->get('desde');
                    $this->datos['hasta'] = $this->input->get('hasta');
                    $this->datos['estado'] = $this->input->get('estado');
                    $this->datos['tiendaSeleccionada'] = $this->input->get('tienda');
                    if(empty($this->datos['desde'])){
                        $this->datos['desde'] = date('Y-m-d');
                        $this->datos['hasta'] = date('Y-m-d');
                    }elseif(empty($this->datos['hasta'])){
                        $this->datos['hasta'] = date('Y-m-d');
                    }
                    if(empty($this->datos['tiendaSeleccionada'])){
                        $this->datos['tiendaSeleccionada'] = null;
                    }
                    if(empty($this->datos['estado'])){
                        $this->datos['estado'] = null;
                    }
                    $this->datos['boton'] = true;
                }else{
                    $this->datos['desde'] = date('Y-m-d');
                    $this->datos['hasta'] = date('Y-m-d');
                    $this->datos['tiendaSeleccionada'] = null;
                    $this->datos['estado'] = null;
                }
                $this->datos['tienda'] = $this->datos['tiendaSeleccionada'];
                $this->getTransferencias($this->datos['tiendaSeleccionada'],$this->datos['desde'],$this->datos['hasta'],$this->datos['estado']);
                if($this->session->userdata('tipo') != USUARIO_TIENDA){
                    $this->datos['tiendas'] = $this->getTiendas();
                }
                foreach($this->datos['transferencias'] as $transferencia){
                    $select = '*';
                    $where = ['personal.id' => $transferencia->tienda];
                    $tienda = $this->UsuariosModel->getPersonal($select,$where,true);
                    $transferencia->nombreTienda = $tienda->nombre . ' ' . $tienda->apellido;
                }
                // Transacciones Canceladas
                if($this->datos['estado'] != ESTADO_TRANFERENCIAS_EJECUTADA){

                    $selectTransaccion = ["fechaInicio as 'fecha'",'referenciaTransaccion','girador','monto','pesos',DB_PREFIX."beneficiarios.nombre as 'beneficiario'",'tasa',DB_PREFIX."personal.nombre as 'nombreTienda'",DB_PREFIX."personal.apellido as 'apellidoTienda'",DB_PREFIX."transacciones.estado as 'estado'"];
                switch ($this->session->userdata('tipo')) {
                    case USUARIO_DISTRIBUIDOR:
                        if ($this->datos['tiendaSeleccionada']) {
                            $whereTransaccion = ['tienda'=>$this->datos['tiendaSeleccionada'],'fechaInicio >='=>$this->datos['desde'],'fechaInicio <='=> $this->datos['hasta'],'transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO];
                        } else {
                            $whereTransaccion = ['usuarios.grupo'=>$this->session->userdata('grupo'),'fechaInicio >='=>$this->datos['desde'],'fechaInicio <='=> $this->datos['hasta'],'transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO];
                        }
                    break;
                    case USUARIO_SUBDISTRIBUIDOR:
                        if ($this->datos['tiendaSeleccionada']) {
                            $whereTransaccion = ['tienda'=>$this->datos['tiendaSeleccionada'],'fechaInicio >='=>$this->datos['desde'],'fechaInicio <='=> $this->datos['hasta'],'transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO];
                        } else {
                            $whereTransaccion = ['usuarios.subgrupo'=>$this->session->userdata('subgrupo'),'fechaInicio >='=>$this->datos['desde'],'fechaInicio <='=> $this->datos['hasta'],'transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO];
                        }
                    break;
                    case USUARIO_TIENDA:
                        $whereTransaccion = ['tienda'=>$this->session->userdata('id'),'fechaInicio >='=>$this->datos['desde'],'fechaInicio <='=> $this->datos['hasta'],'transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO];
                        
                    break;
                    
                    default:
                    if ($this->datos['tiendaSeleccionada']) {
                        $whereTransaccion = ['tienda'=>$this->datos['tiendaSeleccionada'],'fechaInicio >='=>$this->datos['desde'],'fechaInicio <='=> $this->datos['hasta'],'transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO];
                    } else {
                        $whereTransaccion = ['fechaInicio >='=>$this->datos['desde'],'fechaInicio <='=> $this->datos['hasta'],'transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO];
                    }
                break;
            }
                // Falta establecer el select para extraer la informacion pertinente que presentar en la vista
                $this->datos['transacciones'] = $this->TransaccionesModel->getTransaccionesGeneral($selectTransaccion,$whereTransaccion);
            }else{
                $this->datos['transacciones'] = null;
            }
                $this->datos['total'] = $this->total($this->datos['transferencias']);
                // $this->datos['total'] = 100;
                $this->datos['totales'] = $this->totales($this->datos['transferencias']);
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('transferencias/index',$this->datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function busqueda(){
            if ($this->session->userdata('login')) {
                $busqueda = $this->input->get('transferencia');
                $this->busquedaTransferencia($busqueda);
                $this->load->view('transferencias/devolucionResult',$this->datos);
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function devoluciones(){
            if ($this->session->userdata('login')) {
                $hoy = date('Y-m-d');
                if($_GET){
                    // $this->datos['desde'] = $this->input->get('desde');
                    $this->datos['desde'] = date('Y-m-d 00:00:00',strtotime($this->input->get('desde')));
                    $this->datos['hasta'] = date('Y-m-d 23:59:59',strtotime($this->input->get('hasta')));
                    $this->datos['tiendaSeleccionada'] = $this->input->get('tienda');
                    if($this->datos['desde'] < date('2000-01-01')){
                        $this->datos['desde'] = date('Y-m-d 00:00:00');
                        $this->datos['hasta'] = date('Y-m-d 23:59:59');
                    }elseif($this->datos['hasta'] < date('2000-01-01')){
                        $this->datos['hasta'] = date('Y-m-d 23:59:59');
                    }
                    if(empty($this->datos['tiendaSeleccionada'])){
                        $this->datos['tiendaSeleccionada'] = null;
                    }
                    $this->datos['boton'] = true;
                }else{
                    $this->datos['desde'] = date('Y-m-d 00:00:00');
                    $this->datos['hasta'] = date('Y-m-d 23:59:59');
                    $this->datos['tiendaSeleccionada'] = null;
                }
                
                switch ($this->session->userdata('tipo')) {
                    case USUARIO_ADMINISTRADOR:
                        if($this->datos['tiendaSeleccionada']){
                            $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_DEVUELTA,'timestamp >=' => $this->datos['desde'], 'timestamp <=' => $this->datos['hasta'],'transacciones.tienda'=>$this->datos['tiendaSeleccionada']];
                        }else{
                            $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_DEVUELTA,'timestamp >=' => $this->datos['desde'], 'timestamp <=' => $this->datos['hasta']];
                        }
                        break;
                    case USUARIO_SUPERVISOR:
                        $grupo = $this->session->userdata('grupo');
                        if($this->datos['tiendaSeleccionada']){
                            $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_DEVUELTA,'timestamp >=' => $this->datos['desde'], 'timestamp <=' => $this->datos['hasta'],'usuarios.grupo' => $grupo,'transacciones.tienda'=>$this->datos['tiendaSeleccionada']];
                        }else{
                            $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_DEVUELTA,'timestamp >=' => $this->datos['desde'], 'timestamp <=' => $this->datos['hasta'],'usuarios.grupo' => $grupo];
                        }
                        break;
                    case USUARIO_GIRADOR:
                        $girador = $this->session->userdata('id');
                        if($this->datos['tiendaSeleccionada']){
                            $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_DEVUELTA,'timestamp >=' => $this->datos['desde'], 'timestamp <=' => $this->datos['hasta'], 'usuarios.id' => $girador,'transacciones.tienda'=>$this->datos['tiendaSeleccionada']];
                        }else{
                            $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_DEVUELTA,'timestamp >=' => $this->datos['desde'], 'timestamp <=' => $this->datos['hasta'], 'usuarios.id' => $girador];
                        }
                        
                        break;
                    case USUARIO_TIENDA:
                        $tienda = $this->session->userdata('id');
                        $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_DEVUELTA,'timestamp >=' => $this->datos['desde'], 'timestamp <=' => $this->datos['hasta'], 'transacciones.tienda' => $tienda];
                        
                        break;
                    case USUARIO_DISTRIBUIDOR:
                        $grupo = $this->session->userdata('grupo');
                        if($this->datos['tiendaSeleccionada']){
                            $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_DEVUELTA,'timestamp >=' => $this->datos['desde'], 'timestamp <=' => $this->datos['hasta'],'usuarios.grupo' => $grupo,'transacciones.tienda'=>$this->datos['tiendaSeleccionada']];
                        }else{
                            $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_DEVUELTA,'timestamp >=' => $this->datos['desde'], 'timestamp <=' => $this->datos['hasta'],'usuarios.grupo' => $grupo];
                        }
                    break;
                    case USUARIO_SUBDISTRIBUIDOR:
                        $subgrupo = $this->session->userdata('subgrupo');
                        if($this->datos['tiendaSeleccionada']){
                            $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_DEVUELTA,'timestamp >=' => $this->datos['desde'], 'timestamp <=' => $this->datos['hasta'],'usuarios.subgrupo' => $grupo,'transacciones.tienda'=>$this->datos['tiendaSeleccionada']];
                        }else{
                            $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_DEVUELTA,'timestamp >=' => $this->datos['desde'], 'timestamp <=' => $this->datos['hasta'],'usuarios.subgrupo' => $grupo];
                        }
                }
                if($this->session->userdata('tipo') != USUARIO_TIENDA){
                    $this->datos['tiendas'] = $this->getTiendas();
                }
                if($this->session->userdata('tipo') != USUARIO_DISTRIBUIDOR && $this->session->userdata('tipo') != USUARIO_SUBDISTRIBUIDOR){
                    $this->datos['transferencias'] = $this->TransferenciasModel->getTransferenciasDV($where,false);
                }else {
                    $this->datos['transferencias'] = $this->TransferenciasModel->getTransferenciasDVDist($where,false);
                }
                foreach ($this->datos['transferencias'] as $transferencia ) {
                    $tienda = $this->UsuariosModel->getPersonal(['*'],['personal.id'=>$transferencia->tienda],true);
                    $transferencia->nombreTienda = $tienda->nombre . ' ' . $tienda->apellido;
                }
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('transferencias/devoluciones',$this->datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function estadoTransaccionBoton(){
            $transaccion = $this->input->get('transaccion');
            $estado = $this->input->get('estado');
            $set['transaccion'] = ['estado' => $estado];
            $where['transaccion'] = ['referenciaTransaccion' => $transaccion];
            $giro = $this->getTransaccion($transaccion);

            if($giro->tipoUsuario != USUARIO_ADMINISTRADOR  && $estado == ESTADO_TRANSACCION_CANCELADO){
                $tienda = $this->getCuentasTiendas($giro->tienda,true);
                $saldoNuevo = $tienda->pesos + $giro->pesos;
                $set['cuenta'] = ['pesos' => $saldoNuevo];
                $where['cuenta'] = ['id' => $giro->tienda];
                $this->CuentasModel->updateCuentaTienda($set['cuenta'],$where['cuenta']);
            }
            $this->TransaccionesModel->updateEstado($set['transaccion'],$where['transaccion']);
            header('location: ' . base_url() . 'Transacciones/pendientes');
        }
        public function export(){
            if($_GET){
                $this->datos['desde'] = $this->input->get('desde');
                $this->datos['hasta'] = $this->input->get('hasta');
                $this->datos['estado'] = $this->input->get('estado');
                $this->datos['tiendaSeleccionada'] = $this->input->get('tienda');
                if(empty($this->datos['desde'])){
                    $this->datos['desde'] = date('Y-m-d');
                    $this->datos['hasta'] = date('Y-m-d');
                }elseif(empty($this->datos['hasta'])){
                    $this->datos['hasta'] = date('Y-m-d');
                }
                if(empty($this->datos['tiendaSeleccionada'])){
                    $this->datos['tiendaSeleccionada'] = null;
                }
                if(empty($this->datos['estado'])){
                    $this->datos['estado'] = null;
                }
                $this->datos['boton'] = true;
            }else{
                $this->datos['desde'] = date('Y-m-d');
                $this->datos['hasta'] = date('Y-m-d');
                $this->datos['tiendaSeleccionada'] = null;
                $this->datos['estado'] = null;
            }
            
            $this->getTransferencias($this->datos['tiendaSeleccionada'],$this->datos['desde'],$this->datos['hasta'],$this->datos['estado']);
            foreach($this->datos['transferencias'] as $transferencia){
                $select = '*';
                $where = ['personal.id' => $transferencia->tienda];
                $tienda = $this->UsuariosModel->getPersonal($select,$where,true);
                $transferencia->nombreTienda = $tienda->nombre . ' ' . $tienda->apellido;
            }
            $this->load->view('transferencias/export',$this->datos);
        }
        public function filtrarFecha(){
            $desde = $this->input->post('desde');
            $hasta = $this->input->post('hasta');
            $datos['transferencias'] = $this->TransferenciasModel->filtrarFecha($desde,$hasta);
            foreach ($datos['transferencias'] as $transferencia) {
                $datos['total'] = $transferencia->monto;
            }
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('transferencias/index',$datos);
            $this->load->view('overall/footer');

        }
        public function infoCompleta(){
            $transferencia = $this->input->get('transferencia');
            $this->datos['desde'] = $this->input->get('desde');
            $this->datos['hasta'] = $this->input->get('hasta');
            $this->datos['tienda'] = $this->input->get('tienda');
            $where = ['idTransferencia' => $transferencia];
            $whereTransaccion = ['transferencias.idTransferencia' => $transferencia];

            $selectTransaccion = "gvzla_transacciones.fechaInicio as 'fecha', referenciaTransaccion as 'referencia', cuentaBeneficiario as 'cuenta', gvzla_cuentasbeneficiarios.banco as 'banco', gvzla_beneficiarios.nombre as 'beneficiario', gvzla_beneficiarios.cedula as 'cedula', tasa, gvzla_transacciones.estado as 'estado', gvzla_transacciones.pesos as 'pesos', monto, gvzla_clientes.nombre as 'nombreCliente', gvzla_clientes.telefono as 'telefono',gvzla_transferencias.observacion as 'motivo'";

            $selectTransferencia = ($this->session->userdata('tipo') != USUARIO_GIRADOR) ? "referencia, gvzla_personal.nombre as 'nombreGirador', gvzla_personal.apellido as 'apellidoGirador', fecha , captura" : "referencia, fecha, captura, observacion";
            
            $this->datos['transferencia'] = $this->TransferenciasModel->getTransferencias($where,$selectTransferencia,true);
            $this->datos['transaccion'] = $this->getTransaccion($whereTransaccion,$selectTransaccion);
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('transferencias/infoCompleta',$this->datos);
            $this->load->view('overall/footer');
        }
        public function monto(){
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('pruebas/monto');
            $this->load->view('overall/footer');
        }
        public function numberFloat(){
            $monto = $_POST['monto'];
            floatval($monto);
            echo $montoNuevo;
        }
        public function registrar(){
            $this->load->model('TransaccionesModel');
            
            $referencia = $this->input->post('referencia');
            $fecha = date('Y-m-d');
            $transaccion = $this->input->post('transaccion');
            $directorio = './captures/'.date('Ymd').'/'.$this->session->userdata('nombreUsuario');
            $filename = $referencia . '_' . $transaccion;
            $file = 'capture';
            try{
                $this->db->trans_begin();

                $data = $this->uploadCapture($directorio,$filename,$file);

                $datos = ["idTransferencia" => null, "referencia" => $referencia, "captura" => $directorio.'/'.$data['upload_data']['file_name'], "fecha" => $fecha, "transaccion" => $transaccion, "estadoTransferencia" => ESTADO_TRANFERENCIAS_EJECUTADA];
                $this->TransferenciasModel->setTransferencias($datos);
    
                $where = ['referenciaTransaccion' => $transaccion];
                        
                $monto = $this->TransaccionesModel->getTransaccionesGeneral(array('monto',"gvzla_cuentasbeneficiarios.numero as 'cuenta'"),$where,[],true);
    
                $set['transaccion'] = ['estado' => ESTADO_TRANSACCION_PROCESADO];
                $where['transaccion'] = ['referenciaTransaccion' => $transaccion];
                $this->TransaccionesModel->updateEstado($set['transaccion'],$where['transaccion']);
               
                $this->db->trans_commit();
                
            }catch(PDOException $ex){
                $this->db->trans_rollback();
                header('location: ' . base_url() . 'transferencias/registro');
            }
            header('location: ' . base_url());
        }
        public function registrarDevolucion(){
            $this->load->model('TransaccionesModel');
            
            $directorio = './captures/'.date('Ymd').'/'.$this->session->userdata('nombreUsuario');
            $filename = 'Dev_' . $transaccion;
            $file = 'capture';

            $data = $this->uploadCapture($directorio,$filename,$file);
            
            /* Nueva */
            // $set['transaccion'] = ['estado' => ESTADO_TRANSACCION_CANCELADO];
            // $where['transaccion'] = ['referenciaTransaccion' => $this->input->post('referencia')];
            
            // $this->TransaccionesModel->updateEstado($set['transaccion'],$where['transaccion']);
            
            // $select = array(DB_PREFIX."transferencias.referencia as 'transferencia'", "monto");
            
            $transferencia = $this->TransferenciasModel->getTransferencias(['transaccion'=>$this->input->post('referencia'),'estadoTransferencia'=>ESTADO_TRANFERENCIAS_EJECUTADA],'*',true);
            
            
            $set['transferencia'] = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_DEVUELTA,"captura" => $directorio.'/'.$data['upload_data']['file_name'],"observacion"=>$this->input->post('motivo')];

            $this->TransferenciasModel->updateTrasferencia($set['transferencia'],$transferencia->referencia);
            /*Fin */
            // $this->recarga($transaccion->cuenta,$transaccion->monto);
            

            header('location: ' . base_url() . 'Transacciones/canceladoPorDevolucion?transaccion='.$this->input->post('referencia').'&estado='.ESTADO_TRANSACCION_CANCELADO);

        }
        # Carga la vista del regitro de las devoluciones
        public function registroDevoluciones(){
            if ($this->session->userdata('login')) {
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('transferencias/registroDevoluciones');
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function registro(){
            #llamar a al modelo de los usuarios para traer la informacion de las cuentas, agregarla en un array que luego sea enviado a la vista para que sea presentado en el formulario de registro de transferencias
            $girador = $this->session->userdata('id');
            $where = ['girador' => $girador];
            $this->datos['cuentas'] = $this->CuentasModel->getCuentas('*',$where);
            $this->transacciones();
            $this->getGiradores();
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('transferencias/registro',$this->datos);
            // $this->load->view('transferencias/transferirGiradores',$this->datos);
            $this->load->view('overall/footer');
        }
        public function registro2(){
            $this->load->library('form_validation');
            #llamar a al modelo de los usuarios para traer la informacion de las cuentas, agregarla en un array que luego sea enviado a la vista para que sea presentado en el formulario de registro de transferencias
            $girador = $this->session->userdata('id');
            $where = ['girador' => $girador];
            $this->datos['cuentas'] = $this->CuentasModel->getCuentas('*',$where);
            $this->transacciones();
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('transferencias/registro2',$this->datos);
            $this->load->view('overall/footer');
        }
        public function transferirGirador(){
            
            $referencia = $this->input->post('referencia');
            $debitada =  $this->input->post('cuentaDebitada');
            $acreditada = $this->input->post('cuentaAcreditada');
            $monto = $this->input->post('monto');
            $fecha = $this->input->post('fecha');

            $directorio = './captures/'.date('Ymd').'/'.$this->session->userdata('nombreUsuario');
            $filename = 'Saldo_' . $referencia . '_' . substr($debitada,16,4);
            $file = 'capture';

            $data = $this->uploadCapture($directorio,$filename,$file);

            $datos = ["referencia" => $referencia, "captura" => $directorio.'/'.$data['upload_data']['file_name'], "fecha" => $fecha, "transaccion" => null, "estadoTransferencia" => ESTADO_TRANFERENCIAS_EJECUTADA];
            $this->TransferenciasModel->setTransferencias($datos);

            $this->debitarCuenta($debitada,$monto,$acreditada);
            $this->recarga($acreditada,$monto);
            header('location: '. base_url());
        }
        public function validar(){
            $this->load->library('form_validation');
            $config = array(
                array(
                    'columna' => 'referencia',
                    'label' => 'Numero de Referencia',
                    'rules' => 'required'
                ),
                array(
                    'columna' => 'fecha',
                    'label' => 'Fecha',
                    'rules' => 'required'
                )
            );
            $this->form_validation->set_rules($config);
            if ($this->form_validation->run() == false) {
                $this->form_validation->set_message();
                $this->registro2();
            }else {
                header('location: ' . base_url());
            }
        }
        
        private function busquedaTransferencia($busqueda){
            $where = ['referencia' => $busqueda];
            $this->datos['transferencias'] = $this->TransferenciasModel->getBusqueda($where,true);
        }
        private function debitarCuenta($cuenta,$monto,$cuentaBeneficiario){
            $comision = $this->getComision();
            $whereCuenta = ['numero' => $cuenta];
            $cuentaGirador = $this->CuentasModel->getCuentas('banco, saldo',$whereCuenta,true);
            if(substr($cuentaBeneficiario,0,4) == substr($cuenta,0,4)){
                $tarifa = 0;
            }else{
                if($monto>VALOR_MONTOS_ALTOS){
                    $tarifa = ($monto*TARIFA_BANCARIA)+TARIFA_MONTOS_ALTOS;
                }else{
                    $tarifa = $monto*TARIFA_BANCARIA;
                }
            }
            $comisionGirador = $monto * $comision/100;
            $totalDebitar = $monto + $tarifa;
            $saldoNuevo = $cuentaGirador->saldo - $totalDebitar - $comisionGirador;
            $set = ['saldo' => $saldoNuevo];
            $this->CuentasModel->updateCuenta($set,$whereCuenta);
        }
        private function getComision(){
            $this->load->model('UsuariosModel');
            $select = 'gvzla_usuarios.comision';
            $where = ['usuarios.id' => $this->session->userdata('id')];
            return $this->UsuariosModel->getPersonal($select,$where,true);
        }
        private function getGiradores(){
            $this->load->model('UsuariosModel');
            $where = ['tipoUsuario' => USUARIO_GIRADOR];
            $this->datos['giradores'] = $this->UsuariosModel->getPersonal('*',$where);
        }
        private function getTiendas(){
            $select = array('personal.id','nombre','apellido');
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    $where = array('usuarios.tipoUsuario !=' => USUARIO_ADMINISTRADOR, 'usuarios.tipoUsuario !=' => USUARIO_GIRADOR,'usuarios.estado' => ESTADO_USUARIO_ENEABLE);
                break;
                case USUARIO_GIRADOR:
                    $where = array('usuarios.tipoUsuario !=' => USUARIO_ADMINISTRADOR, 'usuarios.tipoUsuario !=' => USUARIO_GIRADOR,'usuarios.estado' => ESTADO_USUARIO_ENEABLE);
                break;
                case USUARIO_DISTRIBUIDOR:
                    $where = array('usuarios.tipoUsuario !=' => USUARIO_ADMINISTRADOR, 'usuarios.tipoUsuario !=' => USUARIO_GIRADOR,'usuarios.grupo' => $this->session->userdata('grupo'),'usuarios.estado' => ESTADO_USUARIO_ENEABLE);
                break;
                case USUARIO_SUBDISTRIBUIDOR:
                    $where = array('usuarios.tipoUsuario !=' => USUARIO_ADMINISTRADOR, 'usuarios.tipoUsuario !=' => USUARIO_GIRADOR,'usuarios.subgrupo' => $this->session->userdata('subgrupo'),'usuarios.estado' => ESTADO_USUARIO_ENEABLE);
                break;
                
            }
            return $this->UsuariosModel->getPersonal($select,$where);
        }
        private function getTransaccion($transferencia, string $select = '*'){
            $this->load->model('TransaccionesModel');
            return $this->TransaccionesModel->getTransacciondeTransferencia($transferencia,$select);
        }
        private function getTransferencias($tienda,$desde,$hasta,$estado){
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    if($estado){
                        if($tienda){
                            $where = ['transferencias.estadoTransferencia'=>$estado,'fecha >=' => $desde,'fecha <= '=> $hasta,'transacciones.tienda'=>$tienda];
                        }else{
                            $where = ['transferencias.estadoTransferencia'=>$estado,'fecha >=' => $desde,'fecha <= '=> $hasta];
                        }
                    }else{
                        if($tienda){
                            $where = ['fecha >=' => $desde,'fecha <= '=> $hasta,'transacciones.tienda'=>$tienda];
                        }else{
                            $where = ['fecha >=' => $desde,'fecha <= '=> $hasta];
                        }
                    }
                    break;
                case USUARIO_SUPERVISOR:
                    $grupo = $this->session->userdata('grupo');
                    if($estado){
                        if($tienda){
                            $where = ['transferencias.estadoTransferencia'=>$estado,'usuarios.grupo' => $grupo,'fecha >=' => $desde,'fecha <= '=> $hasta,'transacciones.tienda'=>$tienda];
                        }else{
                            $where = ['transferencias.estadoTransferencia'=>$estado,'usuarios.grupo' => $grupo,'fecha >=' => $desde,'fecha <= '=> $hasta];
                        }
                    }else{
                        if($tienda){
                            $where = ['usuarios.grupo' => $grupo,'fecha >=' => $desde,'fecha <= '=> $hasta,'transacciones.tienda'=>$tienda];
                        }else{
                            $where = ['usuarios.grupo' => $grupo,'fecha >=' => $desde,'fecha <= '=> $hasta];
                        }
                    }
                    break;
                case USUARIO_GIRADOR:
                    $girador = $this->session->userdata('id');
                    if ($estado) {
                        if($tienda){
                            $where = ['transferencias.estadoTransferencia'=>$estado,'usuarios.id' => $girador,'fecha >=' => $desde,'fecha <= '=> $hasta,'transacciones.tienda'=>$tienda];
                        }else{
                            $where = ['transferencias.estadoTransferencia'=>$estado,'usuarios.id' => $girador,'fecha >=' => $desde,'fecha <= '=> $hasta];
                        }
                    } else {
                        if($tienda){
                            $where = ['usuarios.id' => $girador,'fecha >=' => $desde,'fecha <= '=> $hasta,'transacciones.tienda'=>$tienda];
                        }else{
                            $where = ['usuarios.id' => $girador,'fecha >=' => $desde,'fecha <= '=> $hasta];
                        }
                    }
                    
                    break;
                case USUARIO_TIENDA:
                    $tienda = $this->session->userdata('id');
                    if ($estado) {
                        # code...
                        $where = ['transferencias.estadoTransferencia'=>$estado,'fecha >=' => $desde,'fecha <= '=> $hasta,'transacciones.tienda'=>$tienda];
                    } else {
                        # code...
                        $where = ['fecha >=' => $desde,'fecha <= '=> $hasta,'transacciones.tienda'=>$tienda];
                    }
                    break;
                case USUARIO_DISTRIBUIDOR:
                    $grupo = $this->session->userdata('grupo');
                    if ($estado) {
                        $where = ['transferencias.estadoTransferencia'=>$estado,'fecha >=' => $desde,'fecha <= '=> $hasta,'usuarios.grupo'=>$grupo];
                    } else {
                        $where = ['fecha >=' => $desde,'fecha <= '=> $hasta,'usuarios.grupo'=>$grupo];
                    }
                break;
                case USUARIO_SUBDISTRIBUIDOR:
                    $subgrupo = $this->session->userdata('subgrupo');
                    if ($estado) {
                        $where = ['transferencias.estadoTransferencia'=>$estado,'fecha >=' => $desde,'fecha <= '=> $hasta,'usuarios.subgrupo'=>$subgrupo];
                    } else {
                        $where = ['fecha >=' => $desde,'fecha <= '=> $hasta,'usuarios.subgrupo'=>$subgrupo];
                    }
                    break;
            }
            if($this->session->userdata('tipo') != USUARIO_DISTRIBUIDOR && $this->session->userdata('tipo') != USUARIO_SUBDISTRIBUIDOR){
                $this->datos['transferencias'] = $this->TransferenciasModel->getRealizadas($where);
            }else{
                $this->datos['transferencias'] = $this->TransferenciasModel->getRealizadasDist($where);
            }
        }
        private function recarga($cuenta,$monto){
                $where = ['numero' => $cuenta];
                $data = $this->CuentasModel->getCuentas('*',$where,true);
                $saldoNuevo = $data->saldo + $monto;
                $set = ['saldo' => $saldoNuevo];
                $this->CuentasModel->updateCuenta($set,$where);
        }
        private function total(array $arreglo){
            $total['bs'] = 0;
            $total['pesos'] = 0;
            foreach ($arreglo as $key) {
                $total['bs'] += $key->monto;
                $total['pesos'] += $key->pesos;
                // $total =+ $key->monto;
            }
            return $total;
        }
        private function totales(array $transferencias){
            $totales = array();
            // if($transferencias != array()){
                foreach($transferencias as $transferencia){
                    if (!array_key_exists("{$transferencia->fecha}-{$transferencia->girador}",$totales)){

                        $totales["{$transferencia->fecha}-{$transferencia->girador}"]['fecha'] = $transferencia->fecha;
                        $totales["{$transferencia->fecha}-{$transferencia->girador}"]['girador'] = $transferencia->girador . ' ' . $transferencia->apellidoGirador;
                        $totales["{$transferencia->fecha}-{$transferencia->girador}"]['totalTransferido'] = 0;
                    }
                    $totales["{$transferencia->fecha}-{$transferencia->girador}"]['totalTransferido'] += $transferencia->monto;
                }
            // }
            return $totales;
        }
        private function transacciones($where = []){
            $this->load->model('TransaccionesModel');
            $girador = $this->session->userdata('id');
            $where = ['estado' => ESTADO_TRANSACCION_PENDIENTE, 'girador' => $girador]; 
            $this->datos['transacciones'] = $this->TransaccionesModel->pendientesPorBanco($where);
        }
        private function transferencias($where = []){
            if (!$where) {
                switch ($this->session->userdata('tipo')) {
                    case USUARIO_ADMINISTRADOR:
                        $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_EJECUTADA,'fecha' => date('Y-m-d')];
                        break;
                    case USUARIO_SUPERVISOR:
                        $grupo = $this->session->userdata('grupo');
                        $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_EJECUTADA, 'usuarios.grupo' => $grupo,'fecha' => date('Y-m-d')];
                        break;
                    case USUARIO_GIRADOR:
                        $girador = $this->session->userdata('id');
                        $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_EJECUTADA, 'usuarios.id' => $girador,'fecha' => date('Y-m-d')];
                        break;
                    case USUARIO_TIENDA:
                        $tienda = $this->session->userdata('id');
                        $where = ['estadoTransferencia' => ESTADO_TRANFERENCIAS_EJECUTADA,'fecha' => date('Y-m-d'),'transacciones.tienda'=>$tienda];
                        break;
                }
            }
            $this->datos['transferencias'] = $this->TransferenciasModel->getRealizadas($where);
        }
        private function uploadCapture(string $directorio, string $file_name, $file){
            if(!is_dir(realpath($directorio))){
                mkdir($directorio,0777,true);
            }
            # Subir Captures
            $config['upload_path']          = $directorio;
            $config['allowed_types']        = 'gif|jpg|png|jpeg';
            $config['max_size']             = 0;
            $config['max_width']            = 0;
            $config['max_height']           = 0;
            $config['file_name']            = $file_name;
            
            $this->upload->initialize($config);

            if ( ! $this->upload->do_upload($file))
            {
                $this->datos['error'] = $this->upload->display_errors();
                return $this->registro();
            }
            else
            {
                return $data = array('upload_data' => $this->upload->data());
            }
            
        }

    }
?>
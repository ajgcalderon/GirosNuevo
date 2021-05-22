<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Recargas extends CI_Controller{

        private $datos;

        function __construct(){
            parent::__construct();
            $this->load->model('RecargasModel');
            $this->load->model('UsuariosModel');
            $this->load->model('CuentasModel');
            $this->load->model('TransaccionesModel');
            $this->load->model('TransferenciasModel');
            // $this->load->library('export_excel');
        }

        public function index(){
            if($this->session->userdata('login')){
                if($_GET){
                    $datos['tienda'] = $this->input->get('tienda');
                    $datos['desde'] = $this->input->get('desde');
                    $datos['hasta'] = $this->input->get('hasta');
                    $datos['estado'] = $this->input->get('estado');
                    if(empty($datos['desde'])){
                        $datos['desde'] = date('Y-m-d');
                        $datos['hasta'] = date('Y-m-d');
                    }
                    if(empty($datos['hasta'])){
                        $datos['hasta'] = date('Y-m-d');
                    }
                    if(empty($datos['tienda'])){
                        $datos['tienda'] = '';
                    }
                    if(empty($datos['estado'])){
                        $datos['estado'] = ESTADO_RECARGA_ESPERA;
                    }
                }else{
                    $datos['desde'] = date('Y-m-d');
                    $datos['hasta'] = date('Y-m-d');
                    $datos['tienda'] = '';
                    $datos['estado'] = ESTADO_RECARGA_ESPERA;
                }
                $datos['tiendaSeleccionada'] = $datos['tienda'];
                $select['recargas'] = [DB_PREFIX . "personal.nombre as 'nombre'", DB_PREFIX . "personal.apellido AS 'apellido'",'fecha','monto','referencia',DB_PREFIX."recargastiendas.estado as 'estado'",'comprobante',"idRecargas as 'id'",DB_PREFIX ."usuarios.comision as 'comision'",'administrador'];
                $select['transacciones'] = [];
                // Where para consultar las recargas
                
                switch ($this->session->userdata('tipo')) {
                    case USUARIO_ADMINISTRADOR:
                        if($datos['tienda']){
                            $where['recargas'] = ['usuarios.id' => $datos['tienda'], 'fecha >= ' => $datos['desde'], 'fecha <= ' => $datos['hasta']];
                            $where_or = [];
                            $where['transacciones'] = ['transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO,'transacciones.tienda'=>$datos['tienda'], 'fechaInicio >= ' => $datos['desde'], 'fechaInicio <= ' => $datos['hasta']];
                        }else{
                            $where['transacciones'] = ['transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO, 'fechaInicio >= ' => $datos['desde'], 'fechaInicio <= ' => $datos['hasta']];
                            $where['recargas'] = "fecha >= '". $datos['desde'] ."' AND fecha <= '" .$datos['hasta'] . "' OR recargastiendas.estado = '" . $datos['estado']. "'" ;
                            $where_or = [];
                        }
                        $where['personal'] = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.tipoUsuario !='=>USUARIO_ADMINISTRADOR,'usuarios.tipoUsuario !='=>USUARIO_GIRADOR,'usuarios.tipoUsuario !='=>USUARIO_SUPERVISOR];
                        break;
                    case USUARIO_DISTRIBUIDOR:
                        if($datos['tienda']){
                            $where['recargas'] = ['usuarios.id' => $datos['tienda'], 'fecha >= ' => $datos['desde'], 'fecha <= ' => $datos['hasta']];
                            $where_or = [];
                            $where['transacciones'] = ['transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO,'usuarios.grupo'=>$this->session->userdata('grupo'),'transacciones.tienda'=>$datos['tienda'], 'fechaInicio >= ' => $datos['desde'], 'fechaInicio <= ' => $datos['hasta']];
                        }else{
                            $where['transacciones'] = ['transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO,'usuarios.grupo'=>$this->session->userdata('grupo'), 'fechaInicio >= ' => $datos['desde'], 'fechaInicio <= ' => $datos['hasta']];
                            $where['recargas'] = "fecha >= '". $datos['desde'] ."' AND fecha <= '" .$datos['hasta'] ."' AND usuarios.grupo = '" . $this->session->userdata('grupo') . "' OR recargastiendas.estado = '" . $datos['estado']. "' AND usuarios.grupo = '" . $this->session->userdata('grupo') ."'" ;
                            $where_or = [];
                        }
                        $where['personal'] = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.tipoUsuario !='=>USUARIO_ADMINISTRADOR,'usuarios.tipoUsuario !='=>USUARIO_GIRADOR,'usuarios.tipoUsuario !='=>USUARIO_SUPERVISOR,'usuarios.tipoUsuario !=' => USUARIO_DISTRIBUIDOR,'usuarios.grupo'=>$this->session->userdata('grupo')];
                        break;
                    case USUARIO_SUBDISTRIBUIDOR:
                        if($datos['tienda']){
                            $where['recargas'] = ['usuarios.id' => $datos['tienda'], 'fecha >= ' => $datos['desde'], 'fecha <= ' => $datos['hasta'],'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                            $where_or = [];
                            $where['transacciones'] = ['transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO,'usuarios.subgrupo'=>$this->session->userdata('subgrupo'),'transacciones.tienda'=>$datos['tienda'], 'fechaInicio >= ' => $datos['desde'], 'fechaInicio <= ' => $datos['hasta']];
                        }else{
                            $where['transacciones'] = ['transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO,'usuarios.subgrupo'=>$this->session->userdata('subgrupo'), 'fechaInicio >= ' => $datos['desde'], 'fechaInicio <= ' => $datos['hasta']];
                            $where['recargas'] = "fecha >= '". $datos['desde'] ."' AND fecha <= '" .$datos['hasta'] ."' AND usuarios.subgrupo = '" . $this->session->userdata('subgrupo') . "' OR recargastiendas.estado = '" . $datos['estado']. "' AND usuarios.subgrupo = '" . $this->session->userdata('subgrupo') ."'" ;
                            $where_or = [];
                        }
                        $where['personal'] = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.tipoUsuario'=>USUARIO_TIENDA,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                    break;
                    case USUARIO_TIENDA:
                        $where['recargas'] = "fecha >= '". $datos['desde'] ."' AND fecha <= '" .$datos['hasta'] ."' AND usuarios.id = '" . $this->session->userdata('id') . "' OR recargastiendas.estado = '" . $datos['estado']. "' AND usuarios.id = '" . $this->session->userdata('id') ."'" ;
                        $where_or = [];
                        $tienda = $this->session->userdata('id');
                        $where['transacciones'] = ['transacciones.estado'=> ESTADO_TRANSACCION_CANCELADO,'tienda' => $tienda, 'fechaInicio >= ' => $datos['desde'], 'fechaInicio <= ' => $datos['hasta']];
                        break;
                }
                $datos['recargas'] = $this->RecargasModel->getRecargasOr($select['recargas'],$where['recargas'],$where_or);
                $select['transacciones'] = ["fechaInicio AS 'fecha'", "referenciaTransaccion AS 'referencia'", "girador", DB_PREFIX."beneficiarios.cedula AS 'cedula'", DB_PREFIX."beneficiarios.nombre AS 'beneficiario'", DB_PREFIX."cuentasbeneficiarios.banco AS 'banco'", "cuentaBeneficiario AS 'cuenta'", "monto", "tasa", "pesos", DB_PREFIX."transacciones.estado AS 'estado'","tienda",DB_PREFIX . "personal.nombre AS 'nombreTienda'", DB_PREFIX . "personal.apellido AS 'apellidoTienda'"];
                $datos['transacciones'] = $this->TransaccionesModel->getTransaccionesGeneral($select['transacciones'],$where['transacciones'],[]);
                if($this->session->userdata('tipo') != USUARIO_TIENDA){
                    $datos['listaTiendas'] = $this->UsuariosModel->getPersonal(['*'],$where['personal']);
                }
                $datos['total'] = $this->getTotal($datos['recargas']);
                $datos['total']['pesos'] += $this->total($datos['transacciones']);
                foreach ($datos['recargas'] as $recarga) {
                    if($recarga->administrador){
                        $administrador = $this->UsuariosModel->getPersonal(['*'],['personal.id'=>$recarga->administrador],true);
                        $recarga->nombreAdministrador = $administrador->nombre . ' ' . $administrador->apellido;
                    }else{
                        $recarga->nombreAdministrador = '';
                    }
                }
                foreach ($datos['transacciones'] as $transaccion) {
                    $where['transferencia'] = ['transaccion'=>$transaccion->referencia,'estadoTransferencia'=>ESTADO_TRANFERENCIAS_DEVUELTA];
                    $transferencia = $this->TransferenciasModel->getTransferenciasDV($where['transferencia'],true);
                    if (!empty($transferencia)){
                        $transaccion->estado = 'Devolucion';
                        $transaccion->transferencia = $transferencia->id;
                    }
                }
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('recargas/recargasTiendas',$datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }            
        }
        public function procesar(){
            $id = $this->input->get('id');
            $estado = $this->input->get('estado');
            $recarga = $this->RecargasModel->getRecargas(['*'],['idRecargas'=>$id],true);
            if($estado == ESTADO_RECARGA_ACEPTADA){
                $usuario['recarga'] = $this->UsuariosModel->getUsuario(['*'],['usuarios.id'=>$recarga->tienda],true);
                $cuenta['recarga'] = $this->CuentasModel->getCuentasTiendas(['*'],['cuentastiendas.id'=>$usuario['recarga']->id],true);

                $saldoNuevo['recarga'] = $cuenta['recarga']->pesos + $recarga->monto + ($recarga->monto*$usuario['recarga']->comision/100);
                
                $where['cuenta'] = ['cuentastiendas.id'=>$usuario['recarga']->id];
                $set['cuenta'] = ['pesos'=>$saldoNuevo['recarga']];
                $this->CuentasModel->updateCuentaTienda($set['cuenta'],$where['cuenta']);
                
                switch($usuario['recarga']->tipoUsuario){
                    case USUARIO_SUBDISTRIBUIDOR:
                        $usuario['distribuidor'] = $this->UsuariosModel->getUsuario(['*'],['usuarios.nombreUsuario'=>$usuario['recarga']->grupo],true);
                        $cuenta['distribuidor'] = $this->CuentasModel->getCuentasTiendas(['*'],['cuentastiendas.id'=>$usuario['distribuidor']->id],true);
                        $saldoNuevo['distribuidor'] = $cuenta['distribuidor']->pesos + ($recarga->monto * ($usuario['distribuidor']->comision - $usuario['recarga']->comision)/100);
                        $where['distribuidor'] = ['cuentastiendas.id'=>$usuario['distribuidor']->id];
                        $set['distribuidor'] = ['pesos'=>$saldoNuevo['distribuidor']];
                        $this->CuentasModel->updateCuentaTienda($set['distribuidor'],$where['distribuidor']);
                    break;
                    case USUARIO_TIENDA:
                        if($usuario['recarga']->subgrupo){
                            $usuario['subdistribuidor'] = $this->UsuariosModel->getUsuario(['*'],['usuarios.nombreUsuario'=>$usuario['recarga']->subgrupo],true);
                            $cuenta['subdistribuidor'] = $this->CuentasModel->getCuentasTiendas(['*'],['cuentastiendas.id'=>$usuario['subdistribuidor']->id],true);
                            $saldoNuevo['subdistribuidor'] = $cuenta['subdistribuidor']->pesos + ($recarga->monto * ($usuario['subdistribuidor']->comision-$usuario['recarga']->comision)/100);
                            $where['subdistribuidor'] = ['cuentastiendas.id'=>$usuario['subdistribuidor']->id];
                            $set['subdistribuidor'] = ['pesos'=>$saldoNuevo['subdistribuidor']];
                            $this->CuentasModel->updateCuentaTienda($set['subdistribuidor'],$where['subdistribuidor']);
                            
                            $usuario['distribuidor'] = $this->UsuariosModel->getUsuario(['*'],['usuarios.nombreUsuario'=>$usuario['recarga']->grupo],true);
                            $cuenta['distribuidor'] = $this->CuentasModel->getCuentasTiendas(['*'],['cuentastiendas.id'=>$usuario['distribuidor']->id],true);
                            $saldoNuevo['distribuidor'] = $cuenta['distribuidor']->pesos + ($recarga->monto * ($usuario['distribuidor']->comision-$usuario['subdistribuidor']->comision)/100);
                            $where['distribuidor'] = ['cuentastiendas.id'=>$usuario['distribuidor']->id];
                            $set['distribuidor'] = ['pesos'=>$saldoNuevo['distribuidor']];
                            $this->CuentasModel->updateCuentaTienda($set['distribuidor'],$where['distribuidor']);
                        }elseif ($usuario['recarga']->grupo != GRUPO_CONSIGNACION) {
                            $usuario['distribuidor'] = $this->UsuariosModel->getUsuario(['*'],['usuarios.nombreUsuario'=>$usuario['recarga']->grupo],true);
                            $cuenta['distribuidor'] = $this->CuentasModel->getCuentasTiendas(['*'],['cuentastiendas.id'=>$usuario['distribuidor']->id],true);
                            $saldoNuevo['distribuidor'] = $cuenta['distribuidor']->pesos + ($recarga->monto * ($usuario['distribuidor']->comision-$usuario['recarga']->comision)/100);
                            $where['distribuidor'] = ['cuentastiendas.id'=>$usuario['distribuidor']->id];
                            $set['distribuidor'] = ['pesos'=>$saldoNuevo['distribuidor']];
                            $this->CuentasModel->updateCuentaTienda($set['distribuidor'],$where['distribuidor']);
                        }
                    break;
                    default:
    
                    break;
    
                }
            }
            $where['recarga'] = ['idRecargas'=>$id];
            $set['recarga'] = ['estado'=>$estado,'administrador'=>$this->session->userdata('id')];
            $this->RecargasModel->updateRecarga($set['recarga'],$where['recarga']);

            header('location: '.base_url().'Recargas');

        }
        public function delete(){
            $datos['id'] = $this->input->get('id');
            $datos['monto'] = $this->input->get('monto');
            $datos['referencia'] = $this->input->get('referencia');
            $datos['tiendaRecarga'] = $this->input->get('tiendaRecarga');
            if($_GET){
                $datos['tienda'] = $this->input->get('tienda');
                $datos['desde'] = $this->input->get('desde');
                $datos['hasta'] = $this->input->get('hasta');
                if(empty($datos['desde']) || empty($datos['hasta'])){
                    $datos['desde'] = date('Y-m-d');
                    $datos['hasta'] = date('Y-m-d');
                }
                if(empty($datos['tienda'])){
                    $datos['tienda'] = null;
                }
            }else{
                $datos['desde'] = date('Y-m-d');
                $datos['hasta'] = date('Y-m-d');
                $datos['tienda'] = null;
            }
            $where = ['cuentastiendas.id' => $datos['tiendaRecarga']];
            $data = $this->CuentasModel->getCuentasTiendas(['pesos',DB_PREFIX."usuarios.comision AS 'comision'"],$where,true);
            $montoNuevo = $datos['monto'] + ($datos['monto'] * $data->comision/100);
            $saldoNuevo = $data->pesos - $montoNuevo;
            $set = ['pesos' => $saldoNuevo];
            $this->CuentasModel->updateCuentaTienda($set,$where);
            
            $where = ['idRecargas'=>$datos['id']];
            $this->RecargasModel->deleteRecarga($where);
            header('location: ' . base_url() . 'Recargas?&tienda='.$datos['tienda'].'&desde'.$datos['desde'].'$hasta='.$datos['hasta']);
        }
        public function detalles(){
            $datos['id'] = $this->input->get('id');
            if($_GET){
                $datos['tienda'] = $this->input->get('tienda');
                $datos['desde'] = $this->input->get('desde');
                $datos['hasta'] = $this->input->get('hasta');
                if(empty($datos['desde']) || empty($datos['hasta'])){
                    $datos['desde'] = date('Y-m-d');
                    $datos['hasta'] = date('Y-m-d');
                }
                if(empty($datos['tienda'])){
                    $datos['tienda'] = null;
                }
            }else{
                $datos['desde'] = date('Y-m-d');
                $datos['hasta'] = date('Y-m-d');
                $datos['tienda'] = null;
            }
            $select = [DB_PREFIX . "personal.nombre as 'nombre'", DB_PREFIX . "personal.apellido AS 'apellido'",DB_PREFIX."personal.id AS 'tienda'",'fecha','monto','referencia',"idRecargas AS'id'",DB_PREFIX."recargastiendas.estado as 'estado'",'comprobante'];
            $where = ['recargastiendas.idRecargas'=>$datos['id']];
            $datos['recarga'] = $this->RecargasModel->getRecargas($select,$where,true);
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('recargas/detalles',$datos);
            $this->load->view('overall/footer');
        }
        public function diferirSaldo(){
            if($this->session->userdata('login')){
                $where = ['usuarios.estado'=>ESTADO_CUENTA_ENEABLE];
                $select = [DB_PREFIX."cuentastiendas.id AS 'id'", DB_PREFIX."personal.nombre AS 'nombre'", DB_PREFIX."personal.apellido AS 'apellido'", 'pesos'];
                $datos['tiendas'] = $this->CuentasModel->getCuentasTiendas($select,$where);
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('recargas/diferirSaldo',$datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function diferir(){
            $pesos = $this->input->post('pesos');
            $tienda = $this->input->post('tienda');
            $fecha = date('Y-m-d');
            $referencia = 'Diferido';

            $recarga = ['idRecargas' => null,'referencia' => $referencia,'monto' => -1*$pesos,'fecha' => $fecha,'tienda' => $tienda,'estado'=>ESTADO_RECARGA_ACEPTADA,'comprobante'=>'N/A','administrador'=>$this->session->userdata('id')];
            $this->RecargasModel->setRecarga($recarga);

            $datos['tienda'] = $this->UsuariosModel->getUsuario(['*'],['usuarios.id'=>$tienda],true);
            $cuenta['debitar'] = $this->CuentasModel->getCuentasTiendas(['*'],['cuentastiendas.id'=>$tienda],true);
            $saldoNuevo['debitar'] = $cuenta['debitar']->pesos - ($pesos + ($pesos * $datos['tienda']->comision / 100));
            $set['debitar'] = ['pesos'=>$saldoNuevo['debitar']];
            $where['debitar'] = ['cuentastiendas.id'=>$tienda];
            $this->CuentasModel->updateCuentaTienda($set['debitar'],$where['debitar']);
            header('location: '.base_url().'Recargas/diferirSaldo');
        }
        public function eliminar(){
            $datos['id'] = $this->input->get('id');
            $datos['referencia'] = $this->input->get('referencia');
            if($_GET){
                $datos['tiendaSelect'] = $this->input->get('tienda');
                $datos['desde'] = $this->input->get('desde');
                $datos['hasta'] = $this->input->get('hasta');
                if(empty($datos['desde']) || empty($datos['hasta'])){
                    $datos['desde'] = date('Y-m-d');
                    $datos['hasta'] = date('Y-m-d');
                }
                if(empty($datos['tienda'])){
                    $datos['tienda'] = null;
                }
            }else{
                $datos['desde'] = date('Y-m-d');
                $datos['hasta'] = date('Y-m-d');
                $datos['tienda'] = null;
            }
            $select = [DB_PREFIX . "personal.nombre as 'nombre'", DB_PREFIX . "personal.apellido AS 'apellido'",'fecha','monto','referencia',"idRecargas AS'id'",DB_PREFIX."personal.id AS 'tienda'"];
            $where = ['referencia'=>$datos['referencia']];
            $datos['recarga'] = $this->RecargasModel->getRecargas($select,$where,true);
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('recargas/eliminar',$datos);
            $this->load->view('overall/footer');
        }
        public function modificar(){
            $datos['referencia'] = $this->input->get('referencia');
            if($_GET){
                $datos['tiendaSelect'] = $this->input->get('tienda');
                $datos['desde'] = $this->input->get('desde');
                $datos['hasta'] = $this->input->get('hasta');
                if(empty($datos['desde']) || empty($datos['hasta'])){
                    $datos['desde'] = date('Y-m-d');
                    $datos['hasta'] = date('Y-m-d');
                }
                if(empty($datos['tienda'])){
                    $datos['tienda'] = null;
                }
            }else{
                $datos['desde'] = date('Y-m-d');
                $datos['hasta'] = date('Y-m-d');
                $datos['tienda'] = null;
            }
            $select = [DB_PREFIX . "personal.nombre as 'nombre'", DB_PREFIX . "personal.id as 'tienda'", DB_PREFIX . "personal.apellido AS 'apellido'",'fecha','monto','referencia',DB_PREFIX."recargastiendas.idRecargas AS 'id'"];
            $where = ['referencia'=>$datos['referencia']];
            $datos['recarga'] = $this->RecargasModel->getRecargas($select,$where,true);
            $datos['listaTiendas'] = $this->UsuariosModel->getPersonal(['*'],['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.tipoUsuario'=>USUARIO_TIENDA]);
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('recargas/modificar',$datos);
            $this->load->view('overall/footer');
        }
        public function recargar(){
            $tienda = $this->input->post('tienda');
            $monto = $this->input->post('pesos');
            $fecha = $this->input->post('fecha');
            $referencia = $this->input->post('referencia');
            $usuario = $this->UsuariosModel->getUsuario(['*'],['usuarios.id'=>$tienda],true);
            $montoNuevo = $monto + ($monto*$usuario->comision/100);
            if(($this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR && $this->session->userdata('grupo') == 'Recaudo') || $this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){
                if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){
                    $recarga = ['idRecargas' => null,'referencia' => $referencia,'monto' => $monto,'fecha' => $fecha,'tienda' => $tienda,'estado'=>ESTADO_RECARGA_ACEPTADA,'comprobante'=>'','administrador'=>$this->session->userdata('id')];
                }else{
                    $recarga = ['idRecargas' => null,'referencia' => $referencia,'monto' => $monto,'fecha' => $fecha,'tienda' => $tienda,'estado'=>ESTADO_RECARGA_ACEPTADA,'comprobante'=>''];
                }
            }else{
                $directorio = './recargas/'.date('Ymd').'/'.$this->session->userdata('nombreUsuario');
                $filename = $referencia;
                $file = 'comprobante';
                $data = $this->uploadCapture($directorio,$filename,$file);
                $recarga = ['idRecargas' => null,'referencia' => $referencia,'monto' => $monto,'fecha' => $fecha,'tienda' => $tienda,'estado'=>ESTADO_RECARGA_ACEPTADA,'comprobante'=>$directorio.'/'.$data['upload_data']['file_name']];
            }

            $this->RecargasModel->setRecarga($recarga);


            $where = ['cuentastiendas.id' => $tienda];
            $data = $this->CuentasModel->getCuentasTiendas(['*'],$where,true);
            $saldoNuevo = $data->pesos + $montoNuevo;
            $set = ['pesos' => $saldoNuevo];
            $this->CuentasModel->updateCuentaTienda($set,$where);

            if($this->session->userdata('tipo')==USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo')==USUARIO_SUBDISTRIBUIDOR){
                $cuenta = $this->CuentasModel->getCuentasTiendas(['*'],['cuentastiendas.id'=>$this->session->userdata('id')],true);
                $session = $this->UsuariosModel->getUsuario(['*'],['usuarios.id'=>$this->session->userdata('id')],true);
                $saldoNuevoRecarga = $cuenta->pesos - $montoNuevo;
                $set['cuenta'] = ['pesos'=>$saldoNuevoRecarga];
                $where['cuenta'] = ['id'=>$this->session->userdata('id')];
                $this->CuentasModel->updateCuentaTienda($set['cuenta'],$where['cuenta']);
            }

            header('location: ' . base_url() . 'Usuarios/recargarTienda?tiendaRecargada='.true);
        }
        public function registroConsignacion($error=null){
            $datos['error'] = $error;
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('personal/registroConsignacion',$datos);
            $this->load->view('overall/footer');
        }
        public function registrarConsignacion(){
            $tienda = $this->session->userdata('id');
            $monto = $this->input->post('pesos');
            $fecha = $this->input->post('fecha');
            $referencia = $this->input->post('referencia');
            $directorio = './recargas/'.date('Ymd').'/'.$this->session->userdata('nombreUsuario');
            $filename = $referencia;
            $file = 'comprobante';
            $data = $this->uploadCapture($directorio,$filename,$file);

            $recarga = ['idRecargas' => null,'referencia' => $referencia,'monto' => $monto,'fecha' => $fecha,'tienda' => $tienda,'estado'=>ESTADO_RECARGA_ESPERA,'comprobante'=>$directorio.'/'.$data['upload_data']['file_name']];
            $this->RecargasModel->setRecarga($recarga);
            header('location: ' . base_url() . 'Recargas');

        }
        public function export(){
            if($_GET){
                $datos['tienda'] = $this->input->get('tienda');
                $datos['desde'] = $this->input->get('desde');
                $datos['hasta'] = $this->input->get('hasta');
                $datos['estado'] = $this->input->get('estado');
                if(empty($datos['desde']) || empty($datos['hasta'])){
                    $datos['desde'] = date('Y-m-d');
                    $datos['hasta'] = date('Y-m-d');
                }
                if(empty($datos['tienda'])){
                    $datos['tienda'] = '';
                }
                if(empty($datos['estado'])){
                    $datos['estado'] = ESTADO_RECARGA_ESPERA;
                }
            }else{
                $datos['desde'] = date('Y-m-d');
                $datos['hasta'] = date('Y-m-d');
                $datos['tienda'] = '';
                $datos['estado'] = ESTADO_RECARGA_ESPERA;
            }
        
            $select = [DB_PREFIX . "personal.nombre as 'nombre'", DB_PREFIX . "personal.apellido AS 'apellido'",'fecha','monto','referencia',DB_PREFIX."recargastiendas.estado as 'estado'",'comprobante',"idRecargas as 'id'",DB_PREFIX ."usuarios.comision as 'comision'"];
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    if($datos['tienda']){
                        $where['recargas'] = ['usuarios.id' => $datos['tienda'], 'fecha >= ' => $datos['desde'], 'fecha <= ' => $datos['hasta']];
                        $where_or = [];
                    }else{
                        $where['recargas'] = "fecha >= '". $datos['desde'] ."' AND fecha <= '" .$datos['hasta'] . "' OR recargastiendas.estado = '" . $datos['estado']. "'" ;
                        $where_or = ['recargastiendas.estado'=>$datos['estado']];
                    }
                    break;
                case USUARIO_DISTRIBUIDOR:
                    if($datos['tienda']){
                        $where['recargas'] = ['usuarios.id' => $datos['tienda'], 'fecha >= ' => $datos['desde'], 'fecha <= ' => $datos['hasta']];
                        $where_or = [];
                    }else{
                        // $where['recargas'] = ['fecha >= ' => $datos['desde'], 'fecha <= ' => $datos['hasta'],'usuarios.grupo'=>$this->session->userdata('grupo'),'OR recargastiendas.estado' => $datos['estado']];
                        $where['recargas'] = "fecha >= '". $datos['desde'] ."' AND fecha <= '" .$datos['hasta'] ."' AND usuarios.grupo = '" . $this->session->userdata('grupo') . "' OR recargastiendas.estado = '" . $datos['estado']. "' AND usuarios.grupo = '" . $this->session->userdata('grupo') ."'" ;
                        $where_or = [];
                        // $where_or = ['recargastiendas.estado'=>$datos['estado']];
                    }
                    // }
                    break;
                case USUARIO_SUBDISTRIBUIDOR:
                    if($datos['tienda']){
                        $where['recargas'] = ['usuarios.id' => $datos['tienda'], 'fecha >= ' => $datos['desde'], 'fecha <= ' => $datos['hasta'],'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                        $where_or = [];
                    }else{
                        // $where = ['fecha >= ' => $datos['desde'], 'fecha <= ' => $datos['hasta'],'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                        $where['recargas'] = "fecha >= '". $datos['desde'] ."' AND fecha <= '" .$datos['hasta'] ."' AND usuarios.subgrupo = '" . $this->session->userdata('subgrupo') . "' OR recargastiendas.estado = '" . $datos['estado']. "' AND usuarios.subgrupo = '" . $this->session->userdata('subgrupo') ."'" ;
                        $where_or = [];
                    }
                    break;
                case USUARIO_TIENDA:
                    if($datos['estado']){
                        $where['recargas'] = ['usuarios.id' => $this->session->userdata('id'), 'fecha >= ' => $datos['desde'], 'fecha <= ' => $datos['hasta']];
                        $where_or = ['recargasTiendas.estado'=>$datos['estado']];
                    }else{
                        $where['recargas'] = ['usuarios.id' => $this->session->userdata('id'), 'fecha >= ' => $datos['desde'], 'fecha <= ' => $datos['hasta']];
                        $where_or = [];
                    }
                    break;
            }
            
            $datos['recargas'] = $this->RecargasModel->getRecargasOr($select,$where['recargas'],$where_or);
            $this->load->view('recargas/export',$datos);
        }
        public function saldoDisponible(){
            $tienda = $this->input->get('tienda');
            $where = ['cuentastiendas.id'=>$tienda];
            $datos['tienda'] = $this->CuentasModel->getCuentasTiendas(['*'],$where,true);
            $this->load->view('recargas/saldoDisponible',$datos);
        }
        public function update(){
            $datos['id'] = $this->input->get('id');
            $datos['montoAnterior'] = $this->input->get('montoAnterior');
            $tienda = $this->input->post('tienda');
            $monto = $this->input->post('monto');
            $fecha = $this->input->post('fecha');
            $referencia = $this->input->post('comprobante');
            $set = ['referencia' => $referencia,'monto' => $monto,'fecha' => $fecha,'tienda' => $tienda];
            $where = ['idRecargas' => $datos['id']];
            $this->RecargasModel->updateRecarga($set,$where);

            
            $where = ['cuentastiendas.id' => $tienda];
            $data = $this->CuentasModel->getCuentasTiendas(['pesos',DB_PREFIX."usuarios.comision AS 'comision'"],$where,true);
            
            if($datos['montoAnterior'] <= $monto){
                $diferencia = $monto - $datos['montoAnterior'] ;
                $diferenciaNueva = $diferencia + ($diferencia * $data->comision/100);
                $saldoNuevo = $data->pesos + $diferenciaNueva;
            }else{
                $diferencia = $datos['montoAnterior'] - $monto;
                $diferenciaNueva = $diferencia + ($diferencia * $data->comision/100);
                $saldoNuevo = $data->pesos - $diferenciaNueva;
            }

            $set = ['pesos' => $saldoNuevo];
            $this->CuentasModel->updateCuentaTienda($set,$where);
            header('location: ' . base_url() . 'Usuarios/recargarTienda?tiendaRecargada='.true);
        }
        private function getTotal($recargas){
            $total['pesos'] = 0;
            $total['comision'] = 0;
            foreach ($recargas as $recarga) {
                $total['pesos'] += $recarga->monto;
            }
            return $total;
        }
        private function total(array $arreglo){
            $total = 0;
            foreach ($arreglo as $key) {
                $total += $key->pesos;
                // $total =+ $key->monto;
            }
            return $total;
        }
        private function uploadCapture(string $directorio, string $file_name, $file){
            if(!is_dir(realpath($directorio))){
                mkdir($directorio,0777,true);
            }
            # Subir Captures
            $config['upload_path']          = $directorio;
            $config['allowed_types']        = '*';
            $config['max_size']             = 0;
            $config['max_width']            = 0;
            $config['max_height']           = 0;
            $config['file_name']            = $file_name;
            
            $this->upload->initialize($config);

            if ( ! $this->upload->do_upload($file))
            {
                $this->datos['error'] = $this->upload->display_errors();
                return $this->registroConsignacion();
            }
            else
            {
                return $data = array('upload_data' => $this->upload->data());
            }
            
        }
    }
?>
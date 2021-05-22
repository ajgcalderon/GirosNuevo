<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Usuarios extends CI_Controller{
        
        private $datos;

        function __construct(){
            parent::__construct();
            $this->load->model('UsuariosModel');
            $this->load->model('CuentasModel');
            $this->load->model('ClientesModel');
            $this->load->model('BeneficiariosModel');
            $this->load->model('RecargasModel');
            $this->load->model('TransferenciasModel');
            $this->load->model('TransaccionesModel');
            $this->load->library('form_validation');
            // $this->load->library('export_excel');
        }
        public function index(){
            if ($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR) {
                $this->registroPersonal();
            }else {
                header('location: ' . base_url() . 'home');
            }
        }
        public function actualizarPersonal(){
            $id = $this->input->post('id');
            $nombre = $this->input->post('nombre');
            $apellido = $this->input->post('apellido');
            $nacionalidad = $this->input->post('nacionalidad');
            $numeroCedula = $this->input->post('numeroCedula');
            $cedula = $nacionalidad . '-' . $numeroCedula;
            $grupo = $this->input->post('grupo');
            $nombreUsuario = $this->input->post('nombreUsuario');
            $password = $this->encryption->encrypt($this->input->post('password'));
            $comision = $this->input->post('comision');
            
            $datos['personal'] = array(
                'nombre' => $nombre,
                'apellido' => $apellido,
                'cedula' => $cedula
            );
            if(isset($password) && $password){
                $datos['usuario'] = array(
                    'clave' => $password,
                    'grupo' => $grupo,
                    'comision'=>$comision
                );
            }else{
                $datos['usuario'] = array(
                    'grupo' => $grupo,
                    'comision'=>$comision
                );
            }
            $where = array('id' => $id);
            $this->UsuariosModel->updatePersonal($datos['personal'],$where);
            $this->UsuariosModel->updateUsuarios($datos['usuario'],$where);
            header('location: ' . base_url() . 'Usuarios/detalles?usuario=' . $id);
        }
        public function actualizarPass(){
            $mensaje = array(
                'required' => 'el campo {field} es Obligatorio',
                'min_length' => 'La contraseña debe tener una longitud mínima de 6 caracteres'
            );
            $this->form_validation->set_rules('password','Contraseña','required|min_length[6]',$mensaje);

            if($this->input->post('password') != $this->input->post('passwordConfirm')){
                echo json_encode(array('error' => true, 'mensaje' => 'Los campos no coinciden'));
                exit();
            }
            if(!$this->form_validation->run()){
                echo json_encode(array('error' => true, 'mensaje' => validation_errors()));
                exit();
            }
            $set = ['clave'=> $this->encryption->encrypt($this->input->post('password'))];
            $where = ['id'=>$this->session->userdata('id')];
            $this->UsuariosModel->updateUsuarios($set,$where);
            echo json_encode(array('error' => false, 'mensaje' => 'La Contraseña fue cambiada exitosamente'));
            exit();
        }
        public function cargarFormulario(){
            $tipoUsuario = $this->input->get('tipoUsuario');
            switch($tipoUsuario){
                case USUARIO_ADMINISTRADOR:
                    $this->load->view('personal/formularioPersonal');
                break;
                case USUARIO_GIRADOR:
                    $this->load->view('personal/formularioPersonal');
                break;
                case USUARIO_TIENDA:
                    // $datos['usuario'] = $this->UsuariosModel->getUsuario(['*'],['usuarios.id'=>$this->session->userdata('id')],true);
                    $select['distribuidores'] = ['grupo','subgrupo',DB_PREFIX."personal.nombre as 'nombre'",DB_PREFIX."personal.apellido as 'apellido'"];
                    $select['subdistribuidores'] = ['grupo','subgrupo',DB_PREFIX."personal.nombre as 'nombre'",DB_PREFIX."personal.apellido as 'apellido'"];
                    switch ($this->session->userdata('tipo')) {
                        case USUARIO_ADMINISTRADOR:
                            $where['distribuidores'] = ['tipoUsuario' => USUARIO_DISTRIBUIDOR];
                            $where['subdistribuidores'] = ['tipoUsuario' => USUARIO_SUBDISTRIBUIDOR];
                        break;
                        case USUARIO_DISTRIBUIDOR:
                            $where['distribuidores'] = ['tipoUsuario' => USUARIO_DISTRIBUIDOR,'usuarios.grupo'=>$this->session->userdata('grupo')];
                            $where['subdistribuidores'] = ['tipoUsuario' => USUARIO_SUBDISTRIBUIDOR,'usuarios.grupo'=>$this->session->userdata('grupo')];
                        break;
                        case USUARIO_SUBDISTRIBUIDOR:
                            $where['distribuidores'] = ['tipoUsuario' => USUARIO_DISTRIBUIDOR,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                            $where['subdistribuidores'] = ['tipoUsuario' => USUARIO_SUBDISTRIBUIDOR,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];

                        break;
                    }
                    $datos['distribuidores'] = $this->UsuariosModel->getUsuario($select['distribuidores'],$where['distribuidores']);
                    $datos['subdistribuidores'] = $this->UsuariosModel->getUsuario($select['subdistribuidores'],$where['subdistribuidores']);
                    $this->load->view('personal/formularioTienda',$datos);
                    break;
                case USUARIO_DISTRIBUIDOR:
                    // $datos['usuario'] = $this->UsuariosModel->getUsuario(['*'],['usuarios.id'=>$this->session->userdata('id')],true);
                    $this->load->view('personal/formularioPersonal');
                    break;
                case USUARIO_SUBDISTRIBUIDOR:
                    // $datos['usuario'] = $this->UsuariosModel->getUsuario(['*'],['usuarios.id'=>$this->session->userdata('id')],true);
                    $select['distribuidor'] = ['grupo','subgrupo',DB_PREFIX."personal.nombre as 'nombre'",DB_PREFIX."personal.apellido as 'apellido'"];
                    $where['distribuidor'] = ['tipoUsuario' => USUARIO_DISTRIBUIDOR];
                    $datos['distribuidores'] = $this->UsuariosModel->getUsuario($select['distribuidor'],$where['distribuidor']);
                    $this->load->view('personal/formularioSubdistribuidor',$datos);
                    break;
            }
        }
        public function changePass(){
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('personal/changePass');
            $this->load->view('overall/footer');
        }
        public function comisiones(){
            if($this->session->userdata('login')){
                if($_GET){
                    $desde = $this->input->get('desde');
                    $hasta = $this->input->get('hasta');
                    if(empty($desde) || empty($hasta)){
                        $desde = date('Y-m-d');
                        $hasta = date('Y-m-d');
                    }
                    $this->datos['boton'] = true;
                }else{
                    $desde = date('Y-m-d');
                    $hasta = date('Y-m-d');
                }
                $this->getComisionesRango($desde,$hasta);
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('personal/comisiones',$this->datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function comisionesTiendas(){
            if($this->session->userdata('login')){
                if($_GET){
                    $this->datos['desde'] = $this->input->get('desde');
                    $this->datos['hasta'] = $this->input->get('hasta');
                    $this->datos['tiendaSeleccionada'] = $this->input->get('tienda');
                    if(empty($this->datos['desde']) || empty($this->datos['hasta'])){
                        $this->datos['desde'] = date('Y-m-d');
                        $this->datos['hasta'] = date('Y-m-d');
                    }
                    if(empty($this->datos['tiendaSeleccionada'])){
                        $this->datos['tiendaSeleccionada'] = null;
                    }
                    $this->datos['boton'] = true;
                }else{
                    $this->datos['desde'] = date('Y-m-d');
                    $this->datos['hasta'] = date('Y-m-d');
                    $this->datos['tiendaSeleccionada'] = null;
                }
                if($this->session->userdata('tipo') != USUARIO_TIENDA){
                    switch ($this->session->userdata('tipo')) {
                        case USUARIO_ADMINISTRADOR:
                            $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.tipoUsuario !='=>USUARIO_GIRADOR,'usuarios.tipoUsuario !='=>USUARIO_SUPERVISOR];
                            break;
                        case USUARIO_DISTRIBUIDOR:
                            $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.grupo'=>$this->session->userdata('grupo')];
                        break;
                        case USUARIO_SUBDISTRIBUIDOR:
                            $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                            break;
                    }
                    $this->datos['listaTiendas'] = $this->getPersonal(['*'],$where);
                }
                $this->getComisionesTiendasRango($this->datos['desde'],$this->datos['hasta'],$this->datos['tiendaSeleccionada']);

                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('personal/comisionesTienda',$this->datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function delete(){
            $usuario = $this->input->get('usuario');
            $set = ['estado' => ESTADO_USUARIO_DISABLE];
            $where = ['id' => $usuario];
            $this->UsuariosModel->updateUsuarios($set,$where);
            header('location: ' . base_url() . 'Usuarios/listado');
        }
        public function deleteCuenta(){
            $cuenta = $this->input->get('cuenta');
            $set = ['estadoCuenta' => ESTADO_CUENTA_DISABLE];
            $where = ['numero' => $cuenta];
            $this->CuentasModel->updateCuenta($set,$where);
            header('location: ' . base_url() . 'Usuarios/listado');
        }
        public function detalles(){
            $select = array(DB_PREFIX."usuarios.id AS 'id'", "nombre", "apellido", DB_PREFIX."usuarios.tipoUsuario as 'tipo'", DB_PREFIX."usuarios.grupo as 'grupo'", DB_PREFIX."usuarios.comision as 'comision'");
            $usuario = $this->input->get('usuario');
            $where = ['usuarios.id' => $usuario];
            $datos['usuario'] = $this->getPersonal($select,$where,true);
            $datos['cuentas'] = $this->cuentas($usuario);
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('personal/detalles',$datos);
            $this->load->view('overall/footer');
        }
        public function eliminar(){
            $usuario = $this->input->get('usuario');
            $select = array(DB_PREFIX."usuarios.id AS 'id'", "nombre", "apellido","cedula",DB_PREFIX . "usuarios.nombreUsuario AS 'nombreUsuario'",DB_PREFIX . "usuarios.clave AS 'password'", DB_PREFIX."usuarios.tipoUsuario as 'tipo'", DB_PREFIX."usuarios.grupo as 'grupo'", DB_PREFIX."usuarios.comision as 'comision'");
            $usuario = $this->input->get('usuario');
            $where = ['usuarios.id' => $usuario];
            $datos['usuario'] = $this->getPersonal($select,$where,true);
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('personal/eliminar',$datos);
            $this->load->view('overall/footer');
        }
        public function eliminarCuenta(){
            $select = array("numero","banco","tipo","nombreTitular AS 'nombre'","cedulaTitular AS 'cedula'","girador",DB_PREFIX ."personal.nombre AS 'nombreGirador'",DB_PREFIX ."personal.apellido AS 'apellidoGirador'");
            $cuenta = $this->input->get('cuenta');
            $where = ['numero' => $cuenta];
            $datos['cuenta'] = $this->CuentasModel->getCuentas($select,$where,true);
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('personal/eliminarCuenta',$datos);
            $this->load->view('overall/footer');
        }
        public function export(){
            if($_GET){
                $this->datos['tienda'] = $this->input->get('tienda');
                $this->datos['desde'] = $this->input->get('desde');
                $this->datos['hasta'] = $this->input->get('hasta');
                $this->datos['grupo'] = $this->input->get('grupo');
                $this->datos['subgrupo'] = $this->input->get('subgrupo');
                if(empty($this->datos['desde'])){
                    $this->datos['desde'] = date('Y-m-d');
                    $this->datos['hasta'] = date('Y-m-d');
                }
                if(empty($this->datos['hasta'])){
                    $this->datos['hasta'] = date('Y-m-d');
                }
                if(empty($this->datos['tienda'])){
                    $this->datos['tienda'] = null;
                }
                if(empty($this->datos['grupo'])){
                    $this->datos['grupo'] = null;
                }
                if(empty($this->datos['subgrupo'])){
                    $this->datos['subgrupo'] = null;
                }
            }else{
                $this->datos['desde'] = date('Y-m-d');
                $this->datos['hasta'] = date('Y-m-d');
                $this->datos['tienda'] = null;
                $this->datos['grupo'] = null;
                $this->datos['subgrupo'] = null;
            }
            $select['recargas'] = ['fecha',DB_PREFIX."personal.nombre AS 'nombreTienda'",DB_PREFIX."usuarios.nombreUsuario AS 'nombreUsuario'",DB_PREFIX."personal.apellido AS 'apellidoTienda'",'monto',DB_PREFIX."usuarios.comision AS 'comision'",DB_PREFIX."usuarios.tipoUsuario AS 'tipoUsuario'","tienda as 'id'",DB_PREFIX."usuarios.grupo AS 'grupo'",DB_PREFIX."usuarios.subgrupo AS 'subgrupo'"];
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    if ($this->datos['grupo']) {
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.grupo'=>$this->datos['grupo'],'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];
                    }elseif($this->datos['subgrupo']){
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.subgrupo'=>$this->datos['subgrupo'],'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];                    
                    }elseif($this->datos['tienda']){
                            $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.id'=>$this->datos['tienda'],'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];
                        }else{
                            $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];
                        }
                        
                    $where['tiendas'] = ['usuarios.estado'=>ESTADO_USUARIO_ENEABLE,'usuarios.tipoUsuario !='=>USUARIO_GIRADOR,'usuarios.tipoUsuario !='=>USUARIO_SUPERVISOR,'usuarios.tipoUsuario !='=>USUARIO_ADMINISTRADOR];
                    break;
                case USUARIO_DISTRIBUIDOR:
                    if($this->datos['subgrupo']){
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.subgrupo'=>$this->datos['subgrupo'],'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];                    
                    }elseif($this->datos['tienda']){
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.id'=>$this->datos['tienda'],'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];
                    }else{
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta'],'usuarios.grupo'=>$this->session->userdata('grupo')];
                    }
                    $where['tiendas'] = ['usuarios.estado'=>ESTADO_USUARIO_ENEABLE,'usuarios.grupo'=>$this->session->userdata('grupo')];
                    break;
                case USUARIO_SUBDISTRIBUIDOR:
                    if($this->datos['tienda']){
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.id'=>$this->datos['tienda'],'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];
                    }else{
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta'],'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                    }
                    $where['tiendas'] = ['usuarios.estado'=>ESTADO_USUARIO_ENEABLE,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                    break;
                case USUARIO_TIENDA:
                    $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.id'=>$this->session->userdata('id'),'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];
                    break;
            }
            $this->datos['recargas'] = $this->RecargasModel->getRecargas($select['recargas'],$where['recargas']);
            $this->datos['cuentas'] = $this->calculoComisiones($this->datos['recargas']);
            $this->datos['tiendaSeleccionada'] = $this->datos['tienda'];
            $this->load->view('personal/export',$this->datos);
        }
        public function getComisiones($fecha = null){
            if(!$fecha){
                $fecha = date('Y-m-d');
            }
            if($this->session->userdata('tipo') != USUARIO_TIENDA){
                $transferencias = $this->getTransferencias($fecha);
                if ($this->session->userdata('tipo') != USUARIO_GIRADOR){
                    $this->datos['giradores'] = [];
                    foreach ($transferencias as $transferencia) {
                        if(!array_key_exists($transferencia->nombre . $transferencia->id,$this->datos['giradores'])){
                            $this->datos['giradores'][$transferencia->nombre . $transferencia->id]['total'] = 0;
                            $this->datos['giradores'][$transferencia->nombre . $transferencia->id]['girador'] = $transferencia->nombre . ' ' . $transferencia->id;
                            $this->datos['giradores'][$transferencia->nombre . $transferencia->id]['fecha'] = $transferencia->fecha;
                        }
                        $this->datos['giradores'][$transferencia->nombre . $transferencia->id]['total'] += $transferencia->monto;
                        $this->datos['giradores'][$transferencia->nombre . $transferencia->id]['comision'] = $this->datos['giradores'][$transferencia->nombre . $transferencia->id]['total'] * $transferencia->comision/100;
                    }
                }else{
                    $this->datos['cuentas'] = [];
                    foreach ($transferencias as $transferencia) {
                        if(!array_key_exists($transferencia->titular . $transferencia->cuenta,$this->datos['cuentas'])){
                            $this->datos['cuentas'][$transferencia->titular . $transferencia->cuenta]['total'] = 0;
                            $this->datos['cuentas'][$transferencia->titular . $transferencia->cuenta]['titular'] = $transferencia->titular;
                            $this->datos['cuentas'][$transferencia->titular . $transferencia->cuenta]['fecha'] = $transferencia->fecha;
                            $this->datos['cuentas'][$transferencia->titular . $transferencia->cuenta]['cuenta'] = $transferencia->cuenta;
                        }
                        $this->datos['cuentas'][$transferencia->titular . $transferencia->cuenta]['total'] += $transferencia->monto;
                        $this->datos['cuentas'][$transferencia->titular . $transferencia->cuenta]['comision'] = $this->datos['cuentas'][$transferencia->titular . $transferencia->cuenta]['total'] * $transferencia->comision/100;
                    }
                }
            }else{
                $transacciones = $this->getTransacciones($fecha);
                $this->datos['transacciones']['count'] = 0;
                $this->datos['transacciones']['total'] = 0;
                $this->datos['transacciones']['comision'] = 0;

                foreach($transacciones as $transaccion){
                    $this->datos['transacciones']['fecha'] = $fecha;
                    $this->datos['transacciones']['count']++;
                    $this->datos['transacciones']['total'] += $transaccion->pesos;
                    $this->datos['transacciones']['comision'] += $transaccion->pesos * $transaccion->comision/100;
                }
            }
        }
        public function getComisionesRango($desde, $hasta){
            switch($this->session->userdata('tipo')){
                case USUARIO_ADMINISTRADOR:
                    $transferencias = $this->getTransferenciasRango($desde,$hasta);
                    $this->datos['giradores'] = [];
                    $this->datos['giradores']['totalMonto'] = 0;
                    $this->datos['giradores']['totalComisiones'] = 0;
                    foreach ($transferencias as $transferencia) {
                        if(!array_key_exists($transferencia->fecha . $transferencia->nombre . $transferencia->id,$this->datos['giradores'])){
                            $this->datos['giradores'][$transferencia->fecha . $transferencia->nombre . $transferencia->id]['total'] = 0;
                            $this->datos['giradores'][$transferencia->fecha . $transferencia->nombre . $transferencia->id]['girador'] = $transferencia->nombre . ' ' . $transferencia->apellido;
                            $this->datos['giradores'][$transferencia->fecha . $transferencia->nombre . $transferencia->id]['fecha'] = $transferencia->fecha;
                        }
                        $this->datos['giradores'][$transferencia->fecha . $transferencia->nombre . $transferencia->id]['total'] += $transferencia->monto;
                        $this->datos['giradores'][$transferencia->fecha . $transferencia->nombre . $transferencia->id]['comision'] = $this->datos['giradores'][$transferencia->fecha . $transferencia->nombre . $transferencia->id]['total'] * $transferencia->comision/100;
                        $this->datos['giradores']['totalMonto'] += $transferencia->monto;
                        $this->datos['giradores']['totalComisiones'] += $transferencia->monto * $transferencia->comision/100;
                    }
                    break;
                case USUARIO_SUPERVISOR:
                    $transferencias = $this->getTransferenciasRango($desde,$hasta);
                    $this->datos['giradores'] = [];
                    $this->datos['giradores']['totalMonto'] = 0;
                    $this->datos['giradores']['totalComisiones'] = 0;
                    foreach ($transferencias as $transferencia) {
                        if(!array_key_exists($transferencia->fecha . $transferencia->nombre . $transferencia->id,$this->datos['giradores'])){
                            $this->datos['giradores'][$transferencia->fecha . $transferencia->nombre . $transferencia->id]['total'] = 0;
                            $this->datos['giradores'][$transferencia->fecha . $transferencia->nombre . $transferencia->id]['girador'] = $transferencia->nombre . ' ' . $transferencia->apellido;
                            $this->datos['giradores'][$transferencia->fecha . $transferencia->nombre . $transferencia->id]['fecha'] = $transferencia->fecha;
                        }
                        $this->datos['giradores'][$transferencia->fecha . $transferencia->nombre . $transferencia->id]['total'] += $transferencia->monto;
                        $this->datos['giradores'][$transferencia->fecha . $transferencia->nombre . $transferencia->id]['comision'] = $this->datos['giradores'][$transferencia->fecha . $transferencia->nombre . $transferencia->id]['total'] * $transferencia->comision/100;
                        $this->datos['giradores']['totalMonto'] += $transferencia->monto;
                        $this->datos['giradores']['totalComisiones'] += $transferencia->monto * $transferencia->comision/100;
                    }
                    break;
                case USUARIO_GIRADOR:
                    $transferencias = $this->getTransferenciasRango($desde,$hasta);
                    $this->datos['cuentas'] = [];
                    $this->datos['cuentas']['totalMonto'] = 0;
                    $this->datos['cuentas']['totalComisiones'] = 0;
                    foreach ($transferencias as $transferencia) {
                        if(!array_key_exists($transferencia->fecha . $transferencia->titular . $transferencia->cuenta,$this->datos['cuentas'])){
                            $this->datos['cuentas'][$transferencia->fecha . $transferencia->titular . $transferencia->cuenta]['total'] = 0;
                            $this->datos['cuentas'][$transferencia->fecha . $transferencia->titular . $transferencia->cuenta]['titular'] = $transferencia->titular;
                            $this->datos['cuentas'][$transferencia->fecha . $transferencia->titular . $transferencia->cuenta]['fecha'] = $transferencia->fecha;
                            $this->datos['cuentas'][$transferencia->fecha . $transferencia->titular . $transferencia->cuenta]['cuenta'] = $transferencia->cuenta;
                        }
                        $this->datos['cuentas'][$transferencia->fecha . $transferencia->titular . $transferencia->cuenta]['total'] += $transferencia->monto;
                        $this->datos['cuentas'][$transferencia->fecha . $transferencia->titular . $transferencia->cuenta]['comision'] = $this->datos['cuentas'][$transferencia->fecha . $transferencia->titular . $transferencia->cuenta]['total'] * $transferencia->comision/100;
                        $this->datos['cuentas']['totalMonto'] += $transferencia->monto;
                        $this->datos['cuentas']['totalComisiones'] += $transferencia->monto * $transferencia->comision/100;
                    }
                    break;
                case USUARIO_TIENDA:
                    $transacciones = $this->getTransaccionesRango($desde,$hasta);
                    $this->datos['transacciones'] = [];
                    $this->datos['transacciones']['resumenCount'] = 0;
                    $this->datos['transacciones']['resumenTotal'] = 0;
                    $this->datos['transacciones']['resumenComisiones'] = 0;
                    foreach($transacciones as $transaccion){
                        if($transaccion->pesos){
                            if(!array_key_exists($transaccion->fechaInicio,$this->datos['transacciones'])){
                                $this->datos['transacciones'][$transaccion->fechaInicio]['fecha'] = $transaccion->fechaInicio;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['count'] = 0;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['total'] = 0;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['comision'] = 0;
    
                                $this->datos['transacciones'][$transaccion->fechaInicio]['count']++;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['total'] += $transaccion->pesos;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['comision'] += $transaccion->pesos * $transaccion->comision/100;
                            }else{
                                $this->datos['transacciones'][$transaccion->fechaInicio]['count']++;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['total'] += $transaccion->pesos;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['comision'] += $transaccion->pesos * $transaccion->comision/100;
                            }
                        }
                        $this->datos['transacciones']['resumenCount']++;
                        $this->datos['transacciones']['resumenTotal'] += $transaccion->pesos;
                        $this->datos['transacciones']['resumenComisiones'] += $transaccion->pesos * $transaccion->comision/100;
                    }
                    break;
            }
        }
        public function getComisionesTiendasRango($desde, $hasta, $tienda){
            switch($this->session->userdata('tipo')){
                case USUARIO_ADMINISTRADOR:
                    $transacciones = $this->getTransaccionesTiendasRango($desde,$hasta,$tienda);
                    $this->datos['tiendas'] = [];
                    $this->datos['tiendas']['totalMonto'] = 0;
                    $this->datos['tiendas']['totalComisiones'] = 0;
                    foreach ($transacciones as $transaccion) {
                        if(!array_key_exists($transaccion->nombre . $transaccion->id,$this->datos['tiendas'])){
                            $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['total'] = 0;
                            $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['id'] = $transaccion->id;
                            $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['tienda'] = $transaccion->nombre . ' ' . $transaccion->apellido;
                        }
                        $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['total'] += $transaccion->pesos;
                        $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['comision'] = $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['total'] * $transaccion->comision/100;
                        $this->datos['tiendas']['totalMonto'] += $transaccion->pesos;
                        $this->datos['tiendas']['totalComisiones'] += $transaccion->pesos * $transaccion->comision/100;
                    }
                    break;
                case USUARIO_TIENDA:
                    $transacciones = $this->getTransaccionesRango($desde,$hasta);
                    $this->datos['transacciones'] = [];
                    $this->datos['transacciones']['resumenCount'] = 0;
                    $this->datos['transacciones']['resumenTotal'] = 0;
                    $this->datos['transacciones']['resumenComisiones'] = 0;
                    foreach($transacciones as $transaccion){
                        if($transaccion->pesos){
                            if(!array_key_exists($transaccion->fechaInicio,$this->datos['transacciones'])){
                                $this->datos['transacciones'][$transaccion->fechaInicio]['fecha'] = $transaccion->fechaInicio;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['count'] = 0;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['total'] = 0;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['comision'] = 0;
    
                                $this->datos['transacciones'][$transaccion->fechaInicio]['count']++;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['total'] += $transaccion->pesos;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['comision'] += $transaccion->pesos * $transaccion->comision/100;
                            }else{
                                $this->datos['transacciones'][$transaccion->fechaInicio]['count']++;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['total'] += $transaccion->pesos;
                                $this->datos['transacciones'][$transaccion->fechaInicio]['comision'] += $transaccion->pesos * $transaccion->comision/100;
                            }
                        }
                        $this->datos['transacciones']['resumenCount']++;
                        $this->datos['transacciones']['resumenTotal'] += $transaccion->pesos;
                        $this->datos['transacciones']['resumenComisiones'] += $transaccion->pesos * $transaccion->comision/100;
                    }
                    break;
                case USUARIO_DISTRIBUIDOR:
                    $transacciones = $this->getTransaccionesTiendasRango($desde,$hasta,$tienda);
                    $this->datos['tiendas'] = [];
                    $this->datos['tiendas']['totalMonto'] = 0;
                    $this->datos['tiendas']['totalComisiones'] = 0;
                    foreach ($transacciones as $transaccion) {
                        if(!array_key_exists($transaccion->nombre . $transaccion->id,$this->datos['tiendas'])){
                            $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['total'] = 0;
                            $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['id'] = $transaccion->id;
                            $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['tienda'] = $transaccion->nombre . ' ' . $transaccion->apellido;
                        }
                        $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['total'] += $transaccion->pesos;
                        $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['comision'] = $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['total'] * $transaccion->comision/100;
                        $this->datos['tiendas']['totalMonto'] += $transaccion->pesos;
                        $this->datos['tiendas']['totalComisiones'] += $transaccion->pesos * $transaccion->comision/100;
                    }
                    break;
                case USUARIO_SUBDISTRIBUIDOR:
                    $transacciones = $this->getTransaccionesTiendasRango($desde,$hasta,$tienda);
                    $this->datos['tiendas'] = [];
                    $this->datos['tiendas']['totalMonto'] = 0;
                    $this->datos['tiendas']['totalComisiones'] = 0;
                    foreach ($transacciones as $transaccion) {
                        if(!array_key_exists($transaccion->nombre . $transaccion->id,$this->datos['tiendas'])){
                            $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['total'] = 0;
                            $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['id'] = $transaccion->id;
                            $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['tienda'] = $transaccion->nombre . ' ' . $transaccion->apellido;
                        }
                        $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['total'] += $transaccion->pesos;
                        $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['comision'] = $this->datos['tiendas'][$transaccion->nombre . $transaccion->id]['total'] * $transaccion->comision/100;
                        $this->datos['tiendas']['totalMonto'] += $transaccion->pesos;
                        $this->datos['tiendas']['totalComisiones'] += $transaccion->pesos * $transaccion->comision/100;
                    }
                    break;
            }
        }
        public function getCuentas(){
            $girador = $this->input->get('girador');
            $where = ['girador' => $girador];
            $this->datos['cuentas'] = $this->CuentasModel->getCuentas('numero, banco, tipo',$where);
            $this->load->view('transferencias/cuentasGiradores',$this->datos);
        }
        public function getInfoBeneficiario(){
            $cedula = $this->input->get('cedulaBeneficiario');
            $select = ['nombre',DB_PREFIX."cuentasbeneficiarios.numero as 'cuenta'",DB_PREFIX."cuentasbeneficiarios.tipo AS 'tipo'"];
            $where = ['cedula' => $cedula];
            $row = true;
            $datos['Beneficiario'] = $this->BeneficiariosModel->obtenerBeneficiarios($select,$where,$row);
            if($datos['Beneficiario']){
                $datos['Beneficiario']->banco = $this->determinarBanco($datos['Beneficiario']->cuenta);
            }
            $this->load->view('personal/beneficiario',$datos);
        }
        public function getInfoCliente(){
            $cedula = $this->input->get('cedulaCliente');
            $select = ['nombre','telefono'];
            $where = ['cedula' => $cedula];
            $row = true;
            $datos['cliente'] = $this->ClientesModel->getClientes($select,$where,[],$row);
            $this->load->view('personal/cliente',$datos);
        }
        public function listado(){
            if($this->session->userdata('login')){
                $select = array(DB_PREFIX."usuarios.id AS 'id'", "nombre", "apellido", DB_PREFIX."usuarios.tipoUsuario as 'tipo'", DB_PREFIX."usuarios.grupo as 'grupo'", DB_PREFIX."usuarios.comision as 'comision'",DB_PREFIX."usuarios.subgrupo as 'subgrupo'");
                switch ($this->session->userdata('tipo')) {
                    case USUARIO_ADMINISTRADOR:
                        $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE];
                        break;
                    case USUARIO_DISTRIBUIDOR:
                        $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.grupo' => $this->session->userdata('grupo'),'usuarios.tipoUsuario !='=>USUARIO_ADMINISTRADOR,'usuarios.tipoUsuario !='=>USUARIO_GIRADOR];
                        break;
                    case USUARIO_SUBDISTRIBUIDOR:
                        $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE,'usuarios.subgrupo' => $this->session->userdata('subgrupo')];
                        break;
                }
                $datos['usuarios'] = $this->getPersonal($select,$where);
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('personal/listado',$datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function listadoDeCuentas(){
            if($this->session->userdata('login')){
                if ($_GET) {
                    $girador = $this->input->get('girador');
                    $this->cuentas($girador);
                }else{
                    $this->cuentas();
                }
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('personal/cuentas',$this->datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function listadoDeCuentasTiendas(){
            if($this->session->userdata('login')){
                if($_GET){
                    $tienda = $this->input->get('tienda');
                    $datos['tiendas'] = $this->getCuentasTiendas($tienda);
                }else{
                    $datos['tiendas'] = $this->getCuentasTiendas();
                }
                $datos['tasa'] = $this->TransaccionesModel->getTasa();
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('personal/saldoTiendas',$datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function modificar(){
            $select = array(DB_PREFIX."usuarios.id AS 'id'", "nombre", "apellido","cedula",DB_PREFIX . "usuarios.nombreUsuario AS 'nombreUsuario'",DB_PREFIX . "usuarios.clave AS 'password'", DB_PREFIX."usuarios.tipoUsuario as 'tipo'", DB_PREFIX."usuarios.grupo as 'grupo'", DB_PREFIX."usuarios.comision as 'comision'");
            $usuario = $this->input->get('usuario');
            $where = ['usuarios.id' => $usuario];
            $datos['usuario'] = $this->getPersonal($select,$where,true);
            if($datos['usuario']->tipo == USUARIO_GIRADOR){
                $datos['supervisores'] = $this->getSupervisores();
            }
            $datos['distribuidores'] = $this->UsuariosModel->getUsuario(['*'],['usuarios.tipoUsuario'=>USUARIO_DISTRIBUIDOR]);
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('personal/modificar',$datos);
            $this->load->view('overall/footer');
        }
        public function modificarCuenta(){
            $select = array("numero","banco","tipo","nombreTitular AS 'nombre'","cedulaTitular AS 'cedula'","girador");
            $cuenta = $this->input->get('cuenta');
            $where = ['numero' => $cuenta];
            $datos['cuenta'] = $this->CuentasModel->getCuentas($select,$where,true);
            $datos['giradores'] = $this->giradores();
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('personal/modificarCuenta',$datos);
            $this->load->view('overall/footer');
        }
        public function recarga(){
            $cuentas = $this->input->post('cuentas');
            $monto = $this->input->post('monto');
            foreach ($cuentas as $cuenta) {
                $where = ['numero' => $cuenta];
                $data = $this->CuentasModel->getCuentas('*',$where,true);
                $saldoNuevo = $data->saldo + $monto;
                $set = ['saldo' => $saldoNuevo];
                $this->CuentasModel->updateCuenta($set,$where);
            }
            header('location: ' . base_url());
        }
        public function recargarCuentas(){
            $this->cuentas();
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('personal/recarga',$this->datos);
            $this->load->view('overall/footer');
        }
        public function recargarTienda(){
            if($this->session->userdata('login')){
                $datos['tiendas'] = $this->getCuentasTiendas();
                if($this->session->userdata('tipo')==USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo')==USUARIO_SUBDISTRIBUIDOR){
                    $datos['cuenta'] = $this->CuentasModel->getCuentasTiendas(['*'],['cuentastiendas.id'=>$this->session->userdata('id')],true);
                    $datos['usuario'] = $this->UsuariosModel->getUsuario(['*'],['usuarios.id'=>$this->session->userdata('id')],true);
                }
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('personal/recargaTiendas',$datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }

        public function registrarPersonal(){
            $nombre = $this->input->post('nombre');
            $apellido = $this->input->post('apellido');
            $nacionalidad = $this->input->post('nacionalidad');
            $numeroCedula = $this->input->post('numeroCedula');
            $cedula = $nacionalidad . '-' . $numeroCedula;
            $nombreUsuario =  $this->input->post('nombreUsuario');
            $password = $this->encryption->encrypt($this->input->post('password'));
            $tipoUsuario = $this->input->post('tipoUsuario');
            $comision = $this->input->post('comision');
            $mensaje = array(
                'required' => 'El campo %s es obligatorio',
                'is_unique' => 'El %s no está disponible'
            );
            $this->form_validation->set_rules('nombreUsuario','nombreusuario','required|is_unique[usuarios.nombreUsuario]',$mensaje);
            if($this->form_validation->run() == FALSE){
                echo json_encode(array('error'=>true,'mensaje'=>validation_errors()));
                exit();
            }
            switch ($tipoUsuario) {
                case USUARIO_TIENDA:
                    $tipoTienda = $this->input->post('tipoTienda');
                    switch ($tipoTienda) {
                        case GRUPO_EMPRESA:
                            $grupo = GRUPO_EMPRESA;
                            $subgrupo = null;
                            break;
                        case GRUPO_CONSIGNACION:
                            if(isset($_POST['grupo']) && !empty($this->input->post('grupo'))){
                                $grupo = $this->input->post('grupo');
                                $subgrupo = $this->input->post('subdistribuidor');
                            }else{
                                $grupo = GRUPO_CONSIGNACION;
                                $subgrupo = null;
                            }
                            break;
                        default:
                            $grupo = $this->input->post('grupo');
                            break;
                    }
                    break;
                case USUARIO_DISTRIBUIDOR:
                    $grupo = $nombreUsuario;
                    break;
                case USUARIO_SUBDISTRIBUIDOR:
                    $grupo = $this->input->post('distribuidor');
                    $subgrupo = $nombreUsuario;
                    break;
                default:
                    $grupo = GRUPO_EMPRESA;
                    $subgrupo = null;
                    break;
            }
            
            $datos = array(
                'id' => null,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'cedula' => $cedula
            );

            $idPersonal = $this->UsuariosModel->setPersonal($datos);
            $datosUsuario = array(
                'id' => $idPersonal,
                'nombreUsuario' => $nombreUsuario,
                'clave' => $password,
                'tipoUsuario' => $tipoUsuario,
                'grupo' => $grupo,
                'subgrupo' => $subgrupo,
                'comision'=>$comision,
                'estado' => ESTADO_USUARIO_ENEABLE
            );
            // Tiendas saldo inicial
            $this->UsuariosModel->setUsuarios($datosUsuario);
            if(($tipoUsuario == USUARIO_TIENDA && $grupo != GRUPO_EMPRESA) || $tipoUsuario == USUARIO_DISTRIBUIDOR || $tipoUsuario == USUARIO_SUBDISTRIBUIDOR){
                $datosCuentaTienda = array(
                    'id' => $idPersonal
                );
                $this->CuentasModel->setCuentaTienda($datosCuentaTienda);
            }
            // Fin Tiendas Saldo inicial
            
            echo json_encode(array('error'=>false,'mensaje'=>'Usuario Creado Satisfactoriamente'));
            exit();
            
            header('location: ' . base_url());
        }
        public function registroCuentas(){
            if ($this->session->userdata('login')){
                if ($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR){
                    $this->load->view('overall/header');
                    $this->load->view('overall/nav');
                    $this->giradores();
                    $this->load->view('personal/registroCuentas',$this->datos);
                    $this->load->view('overall/footer');
                }else {
                    header('location: ' . base_url() . 'home');
                }
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function registroPersonal(){
            if($this->session->userdata('login')){
                $datos['supervisores'] = $this->getSupervisores();
                $select['distribuidores'] = ['grupo','subgrupo',DB_PREFIX."personal.nombre as 'nombre'",DB_PREFIX."personal.apellido as 'apellido'"];
                $where['distribuidores'] = ['tipoUsuario' => USUARIO_DISTRIBUIDOR];
                $datos['distribuidores'] = $this->UsuariosModel->getUsuario($select['distribuidores'],$where['distribuidores']);
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                // $this->load->view('personal/registroSupervisor');
                // $this->load->view('personal/registroGirador',$datos);
                // $this->load->view('personal/registroTiendas');
                $this->load->view('personal/RegistroPersonal',$datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function reporteDeComisiones(){
            if($_GET){
                $this->datos['tienda'] = $this->input->get('tienda');
                $this->datos['desde'] = $this->input->get('desde');
                $this->datos['hasta'] = $this->input->get('hasta');
                $this->datos['grupo'] = $this->input->get('grupo');
                $this->datos['subgrupo'] = $this->input->get('subgrupo');
                if(empty($this->datos['desde'])){
                    $this->datos['desde'] = date('Y-m-d');
                    $this->datos['hasta'] = date('Y-m-d');
                }
                if(empty($this->datos['hasta'])){
                    $this->datos['hasta'] = date('Y-m-d');
                }
                if(empty($this->datos['tienda'])){
                    $this->datos['tienda'] = null;
                }
                if(empty($this->datos['grupo'])){
                    $this->datos['grupo'] = null;
                }
                if(empty($this->datos['subgrupo'])){
                    $this->datos['subgrupo'] = null;
                }
            }else{
                $this->datos['desde'] = date('Y-m-d');
                $this->datos['hasta'] = date('Y-m-d');
                $this->datos['tienda'] = null;
                $this->datos['grupo'] = null;
                $this->datos['subgrupo'] = null;
            }
            $select['recargas'] = ['fecha',DB_PREFIX."personal.nombre AS 'nombreTienda'",DB_PREFIX."usuarios.nombreUsuario AS 'nombreUsuario'",DB_PREFIX."personal.apellido AS 'apellidoTienda'",'monto',DB_PREFIX."usuarios.comision AS 'comision'",DB_PREFIX."usuarios.tipoUsuario AS 'tipoUsuario'","tienda as 'id'",DB_PREFIX."usuarios.grupo AS 'grupo'",DB_PREFIX."usuarios.subgrupo AS 'subgrupo'"];
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    if ($this->datos['grupo']) {
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.grupo'=>$this->datos['grupo'],'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];
                    }elseif($this->datos['subgrupo']){
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.subgrupo'=>$this->datos['subgrupo'],'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];                    
                    }elseif($this->datos['tienda']){
                            $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.id'=>$this->datos['tienda'],'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];
                        }else{
                            $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];
                        }
                    $where['tiendas'] = ['usuarios.estado'=>ESTADO_USUARIO_ENEABLE,'usuarios.tipoUsuario !='=>USUARIO_GIRADOR,'usuarios.tipoUsuario !='=>USUARIO_SUPERVISOR,'usuarios.tipoUsuario !='=>USUARIO_ADMINISTRADOR];
                    break;
                case USUARIO_DISTRIBUIDOR:
                    if($this->datos['subgrupo']){
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.subgrupo'=>$this->datos['subgrupo'],'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];                    
                    }elseif($this->datos['tienda']){
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.id'=>$this->datos['tienda'],'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];
                    }else{
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta'],'usuarios.grupo'=>$this->session->userdata('grupo')];
                    }
                    $where['tiendas'] = ['usuarios.estado'=>ESTADO_USUARIO_ENEABLE,'usuarios.grupo'=>$this->session->userdata('grupo')];
                    break;
                case USUARIO_SUBDISTRIBUIDOR:
                    if($this->datos['tienda']){
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.id'=>$this->datos['tienda'],'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];
                    }else{
                        $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta'],'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                    }
                    $where['tiendas'] = ['usuarios.estado'=>ESTADO_USUARIO_ENEABLE,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                    break;
                case USUARIO_TIENDA:
                    $where['recargas'] = ['recargastiendas.estado'=>ESTADO_RECARGA_ACEPTADA,'usuarios.id'=>$this->session->userdata('id'),'fecha >='=>$this->datos['desde'],'fecha <='=>$this->datos['hasta']];
                    break;
            }
            $this->datos['recargas'] = $this->RecargasModel->getRecargas($select['recargas'],$where['recargas']);
            $this->datos['cuentas'] = $this->calculoComisiones($this->datos['recargas']);
            if($this->session->userdata('tipo')!=USUARIO_TIENDA){
                $this->datos['listaTiendas'] = $this->getPersonal(['*'],$where['tiendas']);
            }
            $this->datos['tiendaSeleccionada'] = $this->datos['tienda'];

            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('personal/reporteComisiones',$this->datos);
            $this->load->view('overall/footer');
        }
        public function updateCuenta(){
            $numeroCuentaAnterior = $this->input->post('numeroCuentaAnterior');
            $numero = $this->input->post('numero');
            $tipoCuenta = $this->input->post('tipoCuenta');
            $nombre = $this->input->post('nombre');
            $nacionalidad = $this->input->post('nacionalidad');
            $numeroCedula = $this->input->post('numeroCedula');
            $cedula = $nacionalidad . '-' . $numeroCedula;
            $girador = $this->input->post('girador');
            $banco = $this->determinarBanco($numero);

            $set = array(
                "numero" => $numero,
                "banco" => $banco,
                "tipo" => $tipoCuenta,
                "nombreTitular" => $nombre,
                "cedulaTitular" => $cedula,
                'girador' => $girador
            );
            $where = ['numero' => $numeroCuentaAnterior];
            $this->CuentasModel->updateCuenta($set,$where);
            header('location: ' . base_url() . 'Usuarios/detalles?usuario=' . $girador);
        }
        public function validar(){
            $this->load->helper(array('form','url'));

            if (!$this->form_validation->run()) {
                $this->registrarCuentas();
            }else {
                $this->datos['mensaje'] = true;
                $this->registrar();
            }
        }
        
        private function calculoComisiones($recargas){
            $cuentas = [];

            foreach ($recargas as $recarga) {
                switch ($recarga->tipoUsuario) {
                    case USUARIO_DISTRIBUIDOR:
                        if (!array_key_exists($recarga->nombreUsuario,$cuentas)) {
                            $cuentas[$recarga->nombreUsuario]['id'] = $recarga->id;
                            $cuentas[$recarga->nombreUsuario]['fecha'] = $recarga->fecha;
                            $cuentas[$recarga->nombreUsuario]['nombre'] = $recarga->nombreTienda . ' ' . $recarga->apellidoTienda;
                            $cuentas[$recarga->nombreUsuario]['tipoUsuario'] = $recarga->tipoUsuario;
                            $cuentas[$recarga->nombreUsuario]['pesos'] = 0;
                            $cuentas[$recarga->nombreUsuario]['comision'] = 0;
                            $cuentas[$recarga->nombreUsuario]['grupo'] = $recarga->grupo;
                            $cuentas[$recarga->nombreUsuario]['subgrupo'] = $recarga->subgrupo;
                        }
                        $cuentas[$recarga->nombreUsuario]['pesos'] += $recarga->monto;
                        $cuentas[$recarga->nombreUsuario]['comision'] +=  $recarga->monto * $recarga->comision / 100;                        
                        break;
                    case USUARIO_SUBDISTRIBUIDOR:
                        if (!array_key_exists($recarga->nombreUsuario,$cuentas)) {
                            $cuentas[$recarga->nombreUsuario]['id'] = $recarga->id;
                            $cuentas[$recarga->nombreUsuario]['fecha'] = $recarga->fecha;
                            $cuentas[$recarga->nombreUsuario]['nombre'] = $recarga->nombreTienda . ' ' . $recarga->apellidoTienda;
                            $cuentas[$recarga->nombreUsuario]['tipoUsuario'] = $recarga->tipoUsuario;
                            $cuentas[$recarga->nombreUsuario]['pesos'] = 0;
                            $cuentas[$recarga->nombreUsuario]['comision'] = 0;
                            $cuentas[$recarga->nombreUsuario]['grupo'] = $recarga->grupo;
                            $cuentas[$recarga->nombreUsuario]['subgrupo'] = $recarga->subgrupo;
                        }
                        $cuentas[$recarga->nombreUsuario]['pesos'] += $recarga->monto;
                        $cuentas[$recarga->nombreUsuario]['comision'] += $recarga->monto * $recarga->comision / 100;                        
                        
                        // Si el usuario es Subdistribuidor no calcular ni consultar el distribuidor
                        if($this->session->userdata('tipo') != USUARIO_SUBDISTRIBUIDOR){

                            // Suma las comisiones de al Distribuidor
                            $select['distribuidor'] = ['tipoUsuario','nombreUsuario','grupo','subgrupo',DB_PREFIX."usuarios.id as 'id'",DB_PREFIX."personal.nombre as 'nombreTienda'",DB_PREFIX."personal.apellido AS 'apellidoTienda'",'comision'];
                            $distribuidor = $this->UsuariosModel->getUsuario($select['distribuidor'],['usuarios.nombreUsuario'=>$recarga->grupo],true);
                            if(!array_key_exists($distribuidor->nombreUsuario,$cuentas)){
                                $cuentas[$distribuidor->nombreUsuario]['id'] = $distribuidor->id;
                                $cuentas[$distribuidor->nombreUsuario]['fecha'] = $recarga->fecha;
                                $cuentas[$distribuidor->nombreUsuario]['nombre'] = $distribuidor->nombreTienda . ' ' . $distribuidor->apellidoTienda;
                                $cuentas[$distribuidor->nombreUsuario]['tipoUsuario'] = $distribuidor->tipoUsuario;
                                $cuentas[$distribuidor->nombreUsuario]['pesos'] = 0;
                                $cuentas[$distribuidor->nombreUsuario]['comision'] = 0;
                                $cuentas[$distribuidor->nombreUsuario]['grupo'] = $distribuidor->grupo;
                                $cuentas[$distribuidor->nombreUsuario]['subgrupo'] = $distribuidor->subgrupo;
                            }
                            $cuentas[$distribuidor->nombreUsuario]['comision'] += $recarga->monto * ($distribuidor->comision - $recarga->comision) / 100;
                        }
                        break;
                    case USUARIO_TIENDA:

                        if (!array_key_exists($recarga->nombreUsuario,$cuentas)) {

                            $cuentas[$recarga->nombreUsuario]['id'] = $recarga->id;
                            $cuentas[$recarga->nombreUsuario]['fecha'] = $recarga->fecha;
                            $cuentas[$recarga->nombreUsuario]['nombre'] = $recarga->nombreTienda . ' ' . $recarga->apellidoTienda;
                            $cuentas[$recarga->nombreUsuario]['tipoUsuario'] = $recarga->tipoUsuario;
                            $cuentas[$recarga->nombreUsuario]['pesos'] = 0;
                            $cuentas[$recarga->nombreUsuario]['comision'] = 0;
                            $cuentas[$recarga->nombreUsuario]['grupo'] = $recarga->grupo;
                            $cuentas[$recarga->nombreUsuario]['subgrupo'] = $recarga->subgrupo;
                        }
                        $cuentas[$recarga->nombreUsuario]['pesos'] += $recarga->monto;
                        $cuentas[$recarga->nombreUsuario]['comision'] += $recarga->monto * $recarga->comision / 100;                        
                        
                        // Si el usuario es una tienda no relizará las consultas de los distribuidores y subdistribuidores
                        if($this->session->userdata('tipo') != USUARIO_TIENDA){    
                            // En caso de que la tienda sea de un distribuidor y no directamente de la empresa
                            if($recarga->grupo != GRUPO_EMPRESA){
                                if($recarga->subgrupo){
                                    // Suma las comisiones al Subdistribuidor
                                    
                                    $select['subdistribuidor'] = ['tipoUsuario','nombreUsuario','grupo','subgrupo',DB_PREFIX."usuarios.id as 'id'",DB_PREFIX."personal.nombre as 'nombreTienda'",DB_PREFIX."personal.apellido AS 'apellidoTienda'",'comision'];
                                    $subdistribuidor = $this->UsuariosModel->getUsuario($select['subdistribuidor'],['usuarios.nombreUsuario'=>$recarga->subgrupo],true);
                                    if(!array_key_exists($subdistribuidor->nombreUsuario,$cuentas)){
                                        $cuentas[$subdistribuidor->nombreUsuario]['id'] = $subdistribuidor->id;
                                        $cuentas[$subdistribuidor->nombreUsuario]['fecha'] = $recarga->fecha;
                                        $cuentas[$subdistribuidor->nombreUsuario]['nombre'] = $subdistribuidor->nombreTienda . ' ' . $subdistribuidor->apellidoTienda;
                                        $cuentas[$subdistribuidor->nombreUsuario]['tipoUsuario'] = $subdistribuidor->tipoUsuario;
                                        $cuentas[$subdistribuidor->nombreUsuario]['pesos'] = 0;
                                        $cuentas[$subdistribuidor->nombreUsuario]['comision'] = 0;
                                        $cuentas[$subdistribuidor->nombreUsuario]['grupo'] = $subdistribuidor->grupo;
                                        $cuentas[$subdistribuidor->nombreUsuario]['subgrupo'] = $subdistribuidor->subgrupo;
                                    }
                                    $cuentas[$subdistribuidor->nombreUsuario]['comision'] += $recarga->monto * ($subdistribuidor->comision - $recarga->comision) / 100;
                                    if($this->session->userdata('tipo') != USUARIO_SUBDISTRIBUIDOR){
                                        
                                        $select['distribuidor'] = ['tipoUsuario','nombreUsuario','grupo','subgrupo',DB_PREFIX."usuarios.id as 'id'",DB_PREFIX."personal.nombre as 'nombreTienda'",DB_PREFIX."personal.apellido AS 'apellidoTienda'",'comision'];
                                        $distribuidor = $this->UsuariosModel->getUsuario($select['distribuidor'],['usuarios.nombreUsuario'=>$recarga->grupo],true);
                                        if(!array_key_exists($distribuidor->nombreUsuario,$cuentas)){
                                            $cuentas[$distribuidor->nombreUsuario]['id'] = $distribuidor->id;
                                            $cuentas[$distribuidor->nombreUsuario]['fecha'] = $recarga->fecha;
                                            $cuentas[$distribuidor->nombreUsuario]['nombre'] = $distribuidor->nombreTienda . ' ' . $distribuidor->apellidoTienda;
                                            $cuentas[$distribuidor->nombreUsuario]['tipoUsuario'] = $distribuidor->tipoUsuario;
                                            $cuentas[$distribuidor->nombreUsuario]['pesos'] = 0;
                                            $cuentas[$distribuidor->nombreUsuario]['comision'] = 0;
                                            $cuentas[$distribuidor->nombreUsuario]['grupo'] = $distribuidor->grupo;
                                            $cuentas[$distribuidor->nombreUsuario]['subgrupo'] = $distribuidor->subgrupo;
                                        }
                                        $cuentas[$distribuidor->nombreUsuario]['comision'] += $recarga->monto * ($distribuidor->comision - $subdistribuidor->comision) / 100;
                                    }
                                }elseif($recarga->grupo != GRUPO_CONSIGNACION){
                                
                                // Si no pertenece a un Subdistribuidor sino directamente al Distribuidor
                                // Suma las comisiones del Distribuidor
                                    
                                    $select['distribuidor'] = ['tipoUsuario','nombreUsuario','grupo','subgrupo',DB_PREFIX."usuarios.id as 'id'",DB_PREFIX."personal.nombre as 'nombreTienda'",DB_PREFIX."personal.apellido AS 'apellidoTienda'",'comision'];
                                    $distribuidor = $this->UsuariosModel->getUsuario($select['distribuidor'],['usuarios.nombreUsuario'=>$recarga->grupo],true);
                                    if(!array_key_exists($distribuidor->nombreUsuario,$cuentas)){
                                        $cuentas[$distribuidor->nombreUsuario]['id'] = $distribuidor->id;
                                        $cuentas[$distribuidor->nombreUsuario]['fecha'] = $recarga->fecha;
                                        $cuentas[$distribuidor->nombreUsuario]['nombre'] = $distribuidor->nombreTienda . ' ' . $distribuidor->apellidoTienda;
                                        $cuentas[$distribuidor->nombreUsuario]['tipoUsuario'] = $distribuidor->tipoUsuario;
                                        $cuentas[$distribuidor->nombreUsuario]['pesos'] = 0;
                                        $cuentas[$distribuidor->nombreUsuario]['comision'] = 0;
                                        $cuentas[$distribuidor->nombreUsuario]['grupo'] = $distribuidor->grupo;
                                        $cuentas[$distribuidor->nombreUsuario]['subgrupo'] = $distribuidor->subgrupo;
                                    }
                                    $cuentas[$distribuidor->nombreUsuario]['comision'] += $recarga->monto * ($distribuidor->comision - $recarga->comision) / 100;
                                }
                            }
                        }
                    break;
                }
            }
            return $cuentas;
        }
        private function determinarBanco($cuenta){
            $inicial = substr($cuenta,0,4);
            switch ($inicial) {
                case '0156':
                    $banco = '100% Banco';
                    break;
                case '0114':
                    $banco = 'Bancaribe';
                    break;
                case '0175':
                    $banco = 'Banco Bicentenario';
                    break;
                case '0102':
                    $banco = 'Banco de Venezuela';
                    break;
                case '0163':
                    $banco = 'Banco del Tesoro';
                    break;
                case '0115':
                    $banco = 'Banco Exterior';
                    break;
                case '0151':
                    $banco = 'Banco Fondo Comun';
                    break;
                case '0105':
                    $banco = 'Banco Mercantil';
                    break;
                case '0191':
                    $banco = 'Banco Nacional de Creditos';
                    break;
                case '0116':
                    $banco = 'Banco Occidental de Descuentos';
                    break;
                case '0108':
                    $banco = 'Banco Provincial';
                    break;
                case '0168':
                    $banco = 'Bancrecer';
                    break;
                case '0134':
                    $banco = 'Banesco';
                    break;
                case '0137':
                    $banco = 'Sofitasa';
                    break;
                case '0104':
                    $banco = 'Banco Venezolano de Credito';
                    break;
                case '0128':
                    $banco = 'Banco Caroni';
                    break;
                case '0138':
                    $banco = 'Banco Plaza';
                    break;
                case '0146':
                    $banco = 'Bangente';
                    break;
                case '0157':
                    $banco = 'Banco del Sur';
                    break;
                case '0166':
                    $banco = 'Banco Agricola';
                    break;
                case '0169':
                    $banco = 'Mi Banco';
                    break;
                case '0171':
                    $bando = 'Banco Activo';
                    break;
                case '0172':
                    $banco = 'Banco Bancamiga';
                    break;
                case '0173':
                    $banco = 'Banco Internacional de Desarrollo';
                    break;
                case '0174':
                    $banco = 'Banplus';
                    break;
                case '0176':
                    $banco = 'Banco Espiritu Santo';
                    break;
                case '0190':
                    $banco = 'CityBank';
                    break;
                case '0177':
                    $banco = 'Banco de las Fuernas Armadas Bolivarianas';
                    break;
                case '0601':
                    $banco = 'IMCP';
                    break;
            }
            return $banco;
        }
        private function getCuentasTiendas($tienda = null){
            $select = [DB_PREFIX."cuentastiendas.id AS 'id'", DB_PREFIX."personal.nombre AS 'nombre'", DB_PREFIX."personal.apellido AS 'apellido'", 'pesos'];
            switch($this->session->userdata('tipo')){
                case USUARIO_ADMINISTRADOR:
                    if($tienda){
                        $where = ['cuentastiendas.id' => $tienda,'usuarios.estado'=>ESTADO_CUENTA_ENEABLE];
                    }else{
                        $where = ['usuarios.estado'=>ESTADO_CUENTA_ENEABLE];
                    }
                    $row = false;
                break;
                case USUARIO_TIENDA:
                    $tienda = $this->session->userdata('id');
                    $where = ['cuentastiendas.id' => $tienda];
                    $row = true;
                break;
                case USUARIO_DISTRIBUIDOR:
                    if($tienda){
                        $where = ['cuentastiendas.id' => $tienda,'usuarios.estado'=>ESTADO_CUENTA_ENEABLE];
                    }else{
                        $where = ['usuarios.estado'=>ESTADO_CUENTA_ENEABLE,'usuarios.grupo'=>$this->session->userdata('grupo'),'usuarios.id !='=>$this->session->userdata('id')];
                    }
                    $row = false;
                break;
                case USUARIO_SUBDISTRIBUIDOR:
                    if($tienda){
                        $where = ['cuentastiendas.id' => $tienda,'usuarios.estado'=>ESTADO_CUENTA_ENEABLE];
                    }else{
                        $where = ['usuarios.estado'=>ESTADO_CUENTA_ENEABLE,'usuarios.subgrupo'=>$this->session->userdata('subgrupo'),'usuarios.id !='=>$this->session->userdata('id')];
                    }
                    $row = false;
                break;
            }
            return $this->CuentasModel->getCuentasTiendas($select,$where,$row);
        }
        private function getPersonal(array $select = array('*'),array $where = ['usuarios.estado' => ESTADO_USUARIO_ENEABLE],bool $row = false){
            return $this->UsuariosModel->getPersonal($select,$where,$row);
        }
        private function getSupervisores(){
            $select = array('nombre', 'apellido', 'cedula', DB_PREFIX . 'usuarios.grupo');
            $where = ['usuarios.tipoUsuario' => USUARIO_SUPERVISOR];
            return $this->UsuariosModel->getPersonal($select,$where);
        }
        private function getTransacciones($fecha){
            $select = array(
                "fechaInicio",
                "pesos",
                DB_PREFIX . "usuarios.comision as 'comision'"
            );
            $tienda = $this->session->userdata('id');
            $where = ['fechaInicio' => $fecha, 'transacciones.tienda' => $tienda];
            return $this->TransaccionesModel->getTransaccionesGeneral($select,$where);
        }
        private function getTransaccionesRango($desde,$hasta){
            $select = array(
                "fechaInicio",
                "pesos",
                DB_PREFIX . "usuarios.comision as 'comision'"
            );
            $tienda = $this->session->userdata('id');
            $where = ['fechaInicio >=' => $desde,'fechaInicio <=' => $hasta, 'transacciones.tienda' => $tienda, 'transacciones.estado' => ESTADO_TRANSACCION_PROCESADO];
            return $this->TransaccionesModel->getTransaccionesGeneral($select,$where);
        }
        private function getTransferencias($fecha){
            switch($this->session->userdata('tipo')){
                case USUARIO_ADMINISTRADOR:
                    $select = "fecha,gvzla_personal.id as 'id', gvzla_personal.nombre as 'nombre', gvzla_personal.apellido as 'apellido', gvzla_transacciones.monto as 'monto', gvzla_usuarios.comision as 'comision'";
                    $where = ['fecha' => $fecha];
                    break;
                case USUARIO_SUPERVISOR:
                    $select = "fecha, gvzla_personal.id as 'id', gvzla_personal.nombre as 'nombre', gvzla_personal.apellido as 'apellido', gvzla_transacciones.monto as 'monto', gvzla_usuarios.comision as 'comision'";
                    $grupo = $this->session->userdata('grupo');
                    $where = ['fecha' => $fecha, 'usuarios.grupo' => $grupo];
                    break;
                case USUARIO_GIRADOR:
                    $select = "fecha, gvzla_cuentasgiradores.numero as 'cuenta', gvzla_cuentasgiradores.nombreTitular as 'titular', gvzla_transacciones.monto as 'monto', gvzla_usuarios.comision as 'comision'";
                    $girador = $this->session->userdata('id');
                    $where = ['fecha' => $fecha, 'usuarios.id' => $girador];
                    break;
                case USUARIO_TIENDA:
                    $select = "fecha, gvzla_cuentasgiradores.numero as 'cuenta', gvzla_cuentasgiradores.nombreTitular as 'titular', gvzla_transacciones.pesos as 'monto', gvzla_usuarios.comision as 'comision'";
                    $tienda = $this->session->userdata('id');
                    $where = ['fecha' => $fecha, 'transacciones.tienda' => $tienda];
                    break;
            }
            return $this->TransferenciasModel->getTransferencias($where,$select);
        }
        private function getTransaccionesTiendasRango($desde,$hasta,$tienda){
            switch($this->session->userdata('tipo')){
                case USUARIO_ADMINISTRADOR:
                    $select = "gvzla_transferencias.fecha,gvzla_personal.id as 'id', gvzla_personal.nombre as 'nombre', gvzla_personal.apellido as 'apellido', gvzla_transacciones.pesos as 'pesos', gvzla_usuarios.comision as 'comision'";
                    if($tienda){
                        $where = ['transacciones.estado'=> ESTADO_TRANSACCION_PROCESADO,'transferencias.fecha >=' => $desde,'transferencias.fecha <=' => $hasta,'transacciones.tienda'=>$tienda];
                    }else{
                        $where = ['transacciones.estado'=> ESTADO_TRANSACCION_PROCESADO,'transferencias.fecha >=' => $desde,'transferencias.fecha <=' => $hasta];
                    }
                    break;
                case USUARIO_TIENDA:
                $select = "fecha,gvzla_personal.id as 'id', gvzla_personal.nombre as 'nombre', gvzla_personal.apellido as 'apellido', gvzla_transacciones.pesos as 'pesos', gvzla_usuarios.comision as 'comision'";
                    $tienda = $this->session->userdata('id');
                    $where = ['transacciones.estado'=> ESTADO_TRANSACCION_PROCESADO,'fecha >=' => $desde,'fecha <=' => $hasta, 'transacciones.tienda' => $tienda];
                    break;
                case USUARIO_DISTRIBUIDOR:
                    $select = "gvzla_transferencias.fecha,gvzla_personal.id as 'id', gvzla_personal.nombre as 'nombre', gvzla_personal.apellido as 'apellido', gvzla_transacciones.pesos as 'pesos', gvzla_usuarios.comision as 'comision'";
                    if($tienda){
                        $where = ['transacciones.estado'=> ESTADO_TRANSACCION_PROCESADO,'transferencias.fecha >=' => $desde,'transferencias.fecha <=' => $hasta,'transacciones.tienda'=>$tienda,'usuarios.grupo'=>$this->session->userdata('grupo')];
                    }else{
                        $where = ['transacciones.estado'=> ESTADO_TRANSACCION_PROCESADO,'transferencias.fecha >=' => $desde,'transferencias.fecha <=' => $hasta,'usuarios.grupo'=>$this->session->userdata('grupo')];
                    }
                    break;
                case USUARIO_SUBDISTRIBUIDOR:
                    $select = "gvzla_transferencias.fecha,gvzla_personal.id as 'id', gvzla_personal.nombre as 'nombre', gvzla_personal.apellido as 'apellido', gvzla_transacciones.pesos as 'pesos', gvzla_usuarios.comision as 'comision'";
                    if($tienda){
                        $where = ['transacciones.estado'=> ESTADO_TRANSACCION_PROCESADO,'transferencias.fecha >=' => $desde,'transferencias.fecha <=' => $hasta,'transacciones.tienda'=>$tienda,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                    }else{
                        $where = ['transacciones.estado'=> ESTADO_TRANSACCION_PROCESADO,'transferencias.fecha >=' => $desde,'transferencias.fecha <=' => $hasta,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                    }
                    break;
            }
            return $this->TransferenciasModel->getTransferenciasTiendas($where,$select);
        }
        private function getTransferenciasRango($desde,$hasta){
            switch($this->session->userdata('tipo')){
                case USUARIO_ADMINISTRADOR:
                    $select = "fecha,gvzla_personal.id as 'id', gvzla_personal.nombre as 'nombre', gvzla_personal.apellido as 'apellido', gvzla_transacciones.monto as 'monto', gvzla_usuarios.comision as 'comision', gvzla_transacciones.pesos as 'pesos', gvzla_transacciones.tasa as 'tasa'";
                    $where = ['fecha >=' => $desde,'fecha <=' => $hasta];
                    break;
                case USUARIO_SUPERVISOR:
                    $select = "fecha, gvzla_personal.id as 'id', gvzla_personal.nombre as 'nombre', gvzla_personal.apellido as 'apellido', gvzla_transacciones.monto as 'monto', gvzla_usuarios.comision as 'comision'";
                    $grupo = $this->session->userdata('grupo');
                    $where = ['fecha >=' => $desde,'fecha <=' => $hasta, 'usuarios.grupo' => $grupo];
                    break;
                case USUARIO_GIRADOR:
                    $select = "fecha, gvzla_cuentasgiradores.numero as 'cuenta', gvzla_cuentasgiradores.nombreTitular as 'titular', gvzla_transacciones.monto as 'monto', gvzla_usuarios.comision as 'comision'";
                    $girador = $this->session->userdata('id');
                    $where = ['fecha >=' => $desde,'fecha <=' => $hasta, 'usuarios.id' => $girador];
                    break;
                case USUARIO_TIENDA:
                    $select = "fecha, gvzla_cuentasgiradores.numero as 'cuenta', gvzla_cuentasgiradores.nombreTitular as 'titular', gvzla_transacciones.pesos as 'monto', gvzla_usuarios.comision as 'comision'";
                    $tienda = $this->session->userdata('id');
                    $where = ['fecha >=' => $desde,'fecha <=' => $hasta, 'transacciones.tienda' => $tienda];
                    break;
            }
            return $this->TransferenciasModel->getTransferencias($where,$select);
        }
        protected function registrarCuentas(){
            $numero = $this->input->post('numero');
            $banco = $this->determinarBanco($numero);
            $tipoCuenta = $this->input->post('tipoCuenta');
            $nombre = $this->input->post('nombre');
            $nacionalidad = $this->input->post('nacionalidad');
            $numeroCedula = $this->input->post('numeroCedula');
            $cedula = $nacionalidad . '-' . $numeroCedula;
            $girador = $this->input->post('girador');

            $data = array(
                'numero' => $numero,
                'banco' => $banco,
                'tipo' => $tipoCuenta,
                'nombreTitular' => $nombre,
                'cedulaTitular' => $cedula,
                'girador' => $girador,
                'saldo' => 0,
                'estadoCuenta' => ESTADO_CUENTA_ENEABLE
            );

            $this->CuentasModel->setCuenta($data);
            header('location: ' . base_url());
        }
        protected function cuentas($usuario = ''){
            $select = "banco, numero, tipo, nombreTitular, gvzla_personal.nombre as 'nombreGirador', gvzla_personal.apellido as 'apellidoGirador', saldo";
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    if($usuario){
                        $where = ['usuarios.id' => $usuario,'estadoCuenta' => ESTADO_CUENTA_ENEABLE];
                    }else{
                        $where = ['estadoCuenta' => ESTADO_CUENTA_ENEABLE];
                    }
                    break;
                case USUARIO_SUPERVISOR: 
                    if($usuario){
                        $where = ['usuarios.id' => $usuario, 'estadoCuenta' => ESTADO_CUENTA_ENEABLE];
                    }else{
                        $grupo = $this->session->userdata('grupo');
                        $where = ['usuarios.grupo' => $grupo, 'estadoCuenta' => ESTADO_CUENTA_ENEABLE]; 
                    }
                    break;
                case USUARIO_GIRADOR:
                    $usuario = $this->session->userdata('id');
                    $where = ['usuarios.id' => $usuario, 'estadoCuenta' => ESTADO_CUENTA_ENEABLE];
                    break;
                case USUARIO_DISTRIBUIDOR:
                    if($usuario){
                        $where = ['usuarios.id'=>$usuario,'estadoCuenta'=>ESTADO_CUENTA_ENEABLE];
                    }else{
                        $where = ['usuarios.grupo'=>$this->session->userdata('grupo'),'estadoCuenta'=>ESTADO_CUENTA_ENEABLE];
                    }
                    break;
                case USUARIO_SUBDISTRIBUIDOR:
                    if($usuario){
                        $where = ['usuarios.id'=>$usuario,'estadoCuenta'=>ESTADO_CUENTA_ENEABLE];
                    }else{
                        $where = ['usuarios.subgrupo'=>$this->session->userdata('subgrupo'),'estadoCuenta'=>ESTADO_CUENTA_ENEABLE];
                    }
                    break;
            }
            return $this->datos['cuentas'] = $this->CuentasModel->getCuentas($select,$where);
        }
        protected function giradores(){
            $this->load->model('UsuariosModel');
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    return $this->datos['giradores'] = $this->UsuariosModel->getGiradores('gvzla_personal.id, nombre, apellido, cedula');
                    break;
                case USUARIO_SUPERVISOR:
                    return $this->datos['giradores'] = $this->UsuariosModel->getGiradoresGrupo('personal.id, nombre, apellido, cedula',array('grupo' => $this->session->userdata('grupo')));
                    break;
            }
        }
        
    }

?>
<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Transacciones extends CI_Controller{

        private $datos = array();

        function __construct(){
            parent::__construct();
            $this->load->model('TransaccionesModel');
            $this->load->model('TransferenciasModel');
            $this->load->model('CuentasModel');
            $this->load->model('ClientesModel');
            $this->load->model('UsuariosModel');
            // $this->load->library('export_excel');
        }
        # Transacciones
        public function actualizar(){
            $referencia = $this->input->get('referencia');
            $pesos = $this->input->get('pesos');
            $tasa = $this->input->get('tasa');
            $monto = $this->input->get('monto');
            $nacionalidad = $this->input->get('nacionalidad');
            $numeroCedula = $this->input->get('numeroCedula');
            $cedula = $nacionalidad . '-' . $numeroCedula;
            $nombre = $this->input->get('nombre');
            $cuenta = $this->input->get('cuenta');
            $tipoCuenta = $this->input->get('tipoCuenta');

            if (isset($monto) && !empty($monto) && isset($fecha) && !empty($fecha) && isset($tasa) && !empty($tasa) && isset($pesos) && !empty($pesos) && isset($numeroCedula) && !empty($numeroCedula) && isset($nombre) && !empty($nombre) && isset($cuenta) && !empty($cuenta) && isset($banco) && !empty($banco) && isset($tipoCuenta) && !empty($tipoCuenta)){
                $this->db->trans_start();

                $beneficiario = ['id' => null,'nombre' => $nombre, 'cedula' => $cedula];
                $rowBeneficiario = $this->insertarBeneficiario($beneficiario,$cedula);
                $cuentaBeneficiario = ['numero' => $cuenta, 'banco' => $banco, 'tipo' => $tipoCuenta, 'beneficiario' => $rowBeneficiario->id];
                $this->insertarCuenta($cuentaBeneficiario,$cuenta);
                $set = ['monto' => $monto, 'marcaTiempo' => null,'estado' => ESTADO_TRANSACCION_PENDIENTE, 'cuentaBeneficiario' => $cuenta, 'tasa' => $tasa, 'pesos' => $pesos];
                $this->update($set,$referencia);

                $this->db->trans_complete();
                header('location: ' . base_url() . "Transacciones/nuevaTransaccion?transaccion={$referencia}");
            }else {
                header('location: ' . base_url() . 'Transacciones/registro');
            }
        }
        public function asignacion(){
            $transacciones = $this->input->post('transacciones');
            $girador = $this->input->post('girador');
            $set = ['girador' => $girador];
            foreach ($transacciones as $transaccion) {
                $where = ['referenciaTransaccion' => $transaccion];
                $this->TransaccionesModel->updateEstado($set,$where);
            }
            header('location: ' . base_url() . 'Transacciones/asignarTransaccion');
        }
        public function asignarTransaccion(){
            if($this->session->userdata('login')){
                $this->getTransaccionesSinAsignar();
                $this->getPersonal();
                // $this->giradores();
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('transacciones/asignar',$this->datos);
                $this->load->view('overall/footer');
            }else{
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function busqueda(){
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->busquedaTransacciones();
            $this->load->view('transacciones/pendientes',$this->datos);
            $this->load->view('overall/footer');
        }
        public function cancelar(){
            $transacciones = $this->input->post('transacciones');
            $supervisor = $this->getSupervisor(['grupo' => $this->session->userdata('grupo')],"gvzla_personal.id as 'id'",true);
            $set = ['girador' => null];
            foreach ($transacciones as $transaccion) {
                $where = ['referenciaTransaccion' => $transaccion];
                $this->TransaccionesModel->updateEstado($set,$where);
            }
            header('location: ' . base_url() . 'Transacciones/pendientes');
        }
        public function detalles($transaccion = null){
            if($_GET['transaccion']){
                $transaccion = $this->input->get('transaccion');
            }
            $this->datos['botonPendientes'] = true;
            $this->datos['transaccion'] = $this->getTransaccion($transaccion);
            if($this->datos['transaccion']->girador){
                $select = ["nombre","apellido"];
                $where = ['personal.id' => $this->datos['transaccion']->girador];
                $this->datos['girador'] = $this->UsuariosModel->getPersonal($select,$where,true);
            }
            $this->datos['botones'] = true;
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('transacciones/detalles',$this->datos);
            $this->load->view('overall/footer');
        }
        public function detallesProcesadas($transaccion = null){
            if($_GET['transaccion']){
                $transaccion = $this->input->get('transaccion');
            }
            $this->datos['tienda'] = $this->input->get('tienda');
            $this->datos['desde'] = $this->input->get('desde');
            $this->datos['hasta'] = $this->input->get('hasta');
            $this->datos['botonProcesadas'] = true;
            $this->datos['transaccion'] = $this->getTransaccion($transaccion);
            if($this->datos['transaccion']->girador){
                $select = ["nombre","apellido"];
                $where = ['personal.id' => $this->datos['transaccion']->girador];
                $this->datos['girador'] = $this->UsuariosModel->getPersonal($select,$where,true);
            }
            $this->datos['botones'] = false;
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('transacciones/detalles',$this->datos);
            $this->load->view('overall/footer');
        }
        public function estadoTransaccion(){
            $transaccion = $this->input->post('transaccion');
            $estado = $this->input->post('estado');
            $set = ['estado' => $estado,'girador' => null];
            $where = ['referenciaTransaccion' => $transaccion];

            $this->TransaccionesModel->updateEstado($set,$where);
            header('location: ' . base_url() . 'Transacciones/detalles?transaccion=' . $transaccion);
        }
        public function estadoTransaccionBoton(){
            $transaccion = $this->input->get('transaccion');
            $estado = $this->input->get('estado');
            $set['transaccion'] = ['estado' => $estado, "girador" => null];
            $where['transaccion'] = ['referenciaTransaccion' => $transaccion];
            $giro = $this->getTransaccion($transaccion);

            if($giro->tipoUsuario != USUARIO_ADMINISTRADOR && $giro->grupoUsuario != GRUPO_EMPRESA && $estado == ESTADO_TRANSACCION_CANCELADO){
                $tienda = $this->getCuentasTiendas($giro->tienda,true);
                $saldoNuevo = $tienda->pesos + $giro->pesos;
                $set['cuenta'] = ['pesos' => $saldoNuevo];
                $where['cuenta'] = ['id' => $giro->tienda];
                $this->CuentasModel->updateCuentaTienda($set['cuenta'],$where['cuenta']);
            }
            $this->TransaccionesModel->updateEstado($set['transaccion'],$where['transaccion']);
            header('location: ' . base_url() . 'Transacciones/pendientes');
        }
        public function canceladoPorDevolucion(){
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
            header('location: ' . base_url() . 'Transferencias/devoluciones');
        }
        public function factura(){
            $transaccion = $this->input->get('transaccion');
            $where = ['referenciaTransaccion' => $transaccion];
            $datos['transaccion'] = $this->TransaccionesModel->getBusqueda($where,true);
            $html = $this->load->view('overall/header')->view('transacciones/reportePdf',$datos,true);
            
            $this->load->library('Pdfgenerator');
            $filename = "comprobante_pago_{$transaccion}";
            $this->pdfgenerator->generate($html,$filename,true);
        }
        public function filtro(){
            $tienda = $this->input->get('tienda');
            $desde = $this->input->get('desde');
            $hasta = $this->input->get('hasta');
            $estado = $this->input->get('estado');

            if(empty($tienda)){
                $tienda = null;
            }
            if(empty($desde) && empty($hasta)){
                $desde = date('Y-m-d');
                $hasta = date('Y-m-d');
            }elseif(empty($hasta)){
                $hasta = date('Y-m-d');
            }else{
                $desde = date('Y-m-d');
                $hasta = date('Y-m-d');
            }
            if(empty($estado)){
                $estado = null;
                // Cambiar valor
            }
            $select = ["fechaInicio AS 'fecha'", "referenciaTransaccion AS 'referencia'", "girador", DB_PREFIX."beneficiarios.cedula AS 'cedula'", DB_PREFIX."beneficiarios.nombre AS 'beneficiario'", DB_PREFIX."cuentasbeneficiarios.banco AS 'banco'", "cuentaBeneficiario AS 'cuenta'", "monto", "tasa", "pesos", DB_PREFIX."transacciones.estado AS 'estado'","tienda",DB_PREFIX . "personal.nombre AS 'nombreTienda'", DB_PREFIX . "personal.apellido AS 'apellidoTienda'"];
            switch ($this->session->userdata('tipo')) {
                case USUARIO_DISTRIBUIDOR:
                    if ($estado) {
                        if($tienda){
                            $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta,'estado'=>$estado,'tienda'=>$tienda];
                        }else{
                            $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta,'estado'=>$estado,'usuarios.grupo'=>$this->session->userdata('grupo')];
                        }
                    } else {
                        if($tienda){
                            $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta,'tienda'=>$tienda];
                        }else{
                            $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta,'usuarios.grupo'=>$this->session->userdata('grupo')];
                        }
                    }
                    break;
                case USUARIO_SUBDISTRIBUIDOR:
                    if ($estado) {
                        if($tienda){
                            $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta,'estado'=>$estado,'tienda'=>$tienda];
                        }else{
                            $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta,'estado'=>$estado,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                        }
                    } else {
                        if($tienda){
                            $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta,'tienda'=>$tienda];
                        }else{
                            $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                        }
                    }
                    
                break;
                case USUARIO_TIENDA:
                    if ($estado) {
                        $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta,'estado'=>$estado,'tienda'=>$this->session->userdata('id')];
                    } else {
                        $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta,'tienda'=>$this->session->userdata('id')];
                    }
                    break;
                default:
                    if ($estado) {
                        if($tienda){
                            $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta,'estado'=>$estado,'tienda'=>$tienda];
                        }else{
                            $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta,'estado'=>$estado];
                        }
                    } else {
                        if($tienda){
                            $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta,'tienda'=>$tienda];
                        }else{
                            $where = ['fechaInicio >='=>$desde,'fechaInicio <='=>$hasta];
                        }
                    }
                break;   
            }
            $transacciones = $this->TransaccionesModel->getTransaccionesGeneral($select,$where,$whereOr);
            echo json_encode(array('transacciones'));
            exit();
        }
        public function nuevaTransaccion(){
            $transaccion = $this->input->get('transaccion');
            $where = ['referenciaTransaccion' => $transaccion];
            $datos['transaccion'] = $this->TransaccionesModel->getBusqueda($where,true);
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('transacciones/nuevaTransaccion',$datos);
            $this->load->view('overall/footer');
        }
        public function pendientes(){
            $select = ['personal.id','nombre','apellido'];
            if($this->session->userdata('login')){
                switch ($this->session->userdata('tipo')) {
                    case USUARIO_DISTRIBUIDOR:
                        $where = ['usuarios.tipoUsuario !='=>USUARIO_ADMINISTRADOR,'usuarios.tipoUsuario !='=>USUARIO_GIRADOR,'usuarios.tipoUsuario !='=>USUARIO_SUPERVISOR,'usuarios.grupo'=>$this->session->userdata('grupo')];
                        break;
                    case USUARIO_SUBDISTRIBUIDOR:
                        $where = ['usuarios.tipoUsuario !='=>USUARIO_ADMINISTRADOR,'usuarios.tipoUsuario !='=>USUARIO_GIRADOR,'usuarios.tipoUsuario !='=>USUARIO_SUPERVISOR,'usuarios.subgrupo'=>$this->session->userdata('subgrupo')];
                        break;
                    default:
                        $where = ['usuarios.tipoUsuario !='=>USUARIO_ADMINISTRADOR,'usuarios.tipoUsuario !='=>USUARIO_GIRADOR,'usuarios.tipoUsuario !='=>USUARIO_SUPERVISOR];
                        break;
                }

                $datos['tiendas'] = $this->UsuariosModel->getPersonal($select,$where);
                $datos['transacciones'] = $this->obtenerTransacciones();
                $datos['total'] = $this->total($datos['transacciones']);
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('transacciones/pendientes',$datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        // Preview Ajax Test
        public function previewAjax(){
            $transaccion = $this->input->post('transaccion');
            $this->datos['transaccion'] = $this->getTransaccion($transaccion);
            if($this->datos['transaccion']->girador){
                $select = ["nombre","apellido"];
                $where = ['personal.id' => $this->datos['transaccion']->girador];
                $this->datos['girador'] = $this->UsuariosModel->getPersonal($select,$where,true);
            }
            $this->load->view('ajax/preview');
        }
        //end Test
        public function procesadas(){
            $datos['tienda'] = $this->input->get('tienda');
            $datos['tiendaSeleccionada'] = $this->input->get('tiendaSeleccionada');
            $datos['desde'] = $this->input->get('desde');
            $datos['hasta'] = $this->input->get('hasta');
            $select = [DB_PREFIX."transferencias.fecha AS 'fecha'", DB_PREFIX."transacciones.referenciaTransaccion AS 'referencia'", DB_PREFIX."beneficiarios.cedula AS 'cedula'", DB_PREFIX."beneficiarios.nombre AS 'beneficiario'", DB_PREFIX."cuentasbeneficiarios.banco AS 'banco'", DB_PREFIX."transacciones.cuentaBeneficiario AS 'cuenta'", DB_PREFIX."transacciones.monto", DB_PREFIX."transacciones.tasa", DB_PREFIX."transacciones.pesos", DB_PREFIX."transacciones.estado AS 'estado'",DB_PREFIX."transacciones.tienda",DB_PREFIX . "personal.nombre AS 'nombreTienda'", DB_PREFIX . "personal.apellido AS 'apellidoTienda'",DB_PREFIX . "usuarios.comision AS 'comision'"];
            switch($this->session->userdata('tipo')){
                case USUARIO_ADMINISTRADOR:
                    $where = ['transacciones.estado'=> ESTADO_TRANSACCION_PROCESADO,'transacciones.tienda'=>$datos['tienda'],'transferencias.fecha >= ' => $datos['desde'],'transferencias.fecha <= ' => $datos['hasta']];
                    break;
                case USUARIO_TIENDA:
                    $tiendaId = $this->session->userdata('id');
                    $where = ['transacciones.estado'=> ESTADO_TRANSACCION_PROCESADO,'transacciones.tienda' => $tiendaId,'transferencias.fecha >= ' => $datos['desde'],'transferencias.fecha <= ' => $datos['hasta']];
                    break;
            }
            $datos['transacciones'] = $this->TransferenciasModel->getTransferenciasTiendas($where,$select);
            $datos['totalPesos'] = 0;
            $datos['totalBolivares'] = 0;
            $datos['totalComision'] = 0;
            foreach ($datos['transacciones'] as $transaccion) {
                $datos['totalPesos'] += $transaccion->pesos;
                $datos['totalBolivares'] += $transaccion->monto;
                $datos['totalComision'] += $transaccion->pesos * $transaccion->comision / 100;
                $datos['nombreTienda'] = $transaccion->nombreTienda . ' ' . $transaccion->apellidoTienda;
            }
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('transacciones/procesadas',$datos);
            $this->load->view('overall/footer');
        }
        public function procesadasPDF(){
            $tienda = $this->input->get('tienda');
            $desde = $this->input->get('desde');
            $hasta = $this->input->get('hasta');
            $where = ['fechaInicio >=' => $desde,'fechaInicio <=' => $hasta, 'tienda' => $tienda];
            $datos['transacciones'] = $this->TransaccionesModel->getBusqueda($where,true);
            $html = $this->load->view('overall/header')->view('transacciones/procesadasPDF',$datos,true);
            
            $this->load->library('Pdfgenerator');
            $filename = "Reporte_{$tienda}_{$desde}-{$hasta}";
            $this->pdfgenerator->generate($html,$filename,true);
        }
        public function registrar(){
            $this->load->model('ClientesModel');
            $monto = $this->input->post('monto');
            $fecha = date('Y-m-d H:i:s');
            $tasa = $this->input->post('tasa');
            $pesos = $this->input->post('pesos');
            $nacionalidad = $this->input->post('nacionalidad');
            $numeroCedula = $this->input->post('numeroCedula');
            $cedula = $nacionalidad . '-' . $numeroCedula;
            $nombre = $this->input->post('nombre');
            $cuenta = $this->input->post('cuenta');
            $banco = $this->determinarBanco($cuenta);
            $tipoCuenta = $this->input->post('tipoCuenta');
            $tienda = $this->session->userdata('id');
            $nombreCliente = $this->input->post('nombreCliente');
            $telefono = $this->input->post('codArea') . $this->input->post('telefono');
            $nacionalidadCliente = $this->input->post('nacionalidadCliente');
            $numeroCedulaCliente = $this->input->post('numeroCedulaCliente');
            $cedulaCliente = $nacionalidadCliente . '-' . $numeroCedulaCliente;
            $mensaje = array(
                'required' => 'El campo %s es obligatorio',
                'min_length' => 'El minimo de caracteres del campo %s es de 20',
                'max_length' => 'La longitud maxima de caracarteres del campo %s es de 20'
            );
            $this->form_validation->set_rules('cuenta','cuenta','required|min_length[20]|max_length[20]');
            if($this->form_validation->run() == FALSE){
                echo json_encode(array('error'=>true,'mensaje'=>$mensaje));
                exit();
            }
            if(($this->session->userdata('tipo') == USUARIO_TIENDA && $this->session->userdata('grupo') != GRUPO_EMPRESA) || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo') == USUARIO_SUBDISTRIBUIDOR){
                $saldoDisponible = $this->getCuentasTiendas();
            }
            if (isset($monto) && !empty($monto) && isset($fecha) && !empty($fecha) && isset($tasa) && !empty($tasa) && isset($pesos) && !empty($pesos) && isset($numeroCedula) && !empty($numeroCedula) && isset($nombre) && !empty($nombre) && isset($cuenta) && !empty($cuenta) && isset($banco) && !empty($banco) && isset($tipoCuenta) && !empty($tipoCuenta)){
                if($this->session->userdata('tipo') == USUARIO_ADMINISTRADOR || ($this->session->userdata('tipo') == USUARIO_TIENDA && $this->session->userdata('grupo') == GRUPO_EMPRESA)){
                    $this->db->trans_start();
                    $cliente = ['idClientes' => null, 'nombre' => $nombreCliente, 'telefono' => $telefono,'cedula'=>$cedulaCliente];
                    $idCliente = $this->insertarCliente($cliente,$cedulaCliente);
                    $beneficiario = ['id' => null,'nombre' => $nombre, 'cedula' => $cedula];
                    $rowBeneficiario = $this->insertarBeneficiario($beneficiario,$cedula);
                    $cuentaBeneficiario = ['numero' => $cuenta, 'banco' => $banco, 'tipo' => $tipoCuenta, 'beneficiario' => $rowBeneficiario->id];
                    $this->insertarCuenta($cuentaBeneficiario,$cuenta);
                    $transaccion = ['referenciaTransaccion' => null, 'monto' => $monto, 'fechaInicio' => $fecha, 'marcaTiempo' => null,'estado' => ESTADO_TRANSACCION_PENDIENTE, 'cuentaBeneficiario' => $cuenta, 'tasa' => $tasa, 'pesos' => $pesos,"tienda" => $tienda,'cliente' => $idCliente->idClientes];
                    $idTransaccion = $this->insertarTransaccion($transaccion);
    
                    $this->db->trans_complete();
                    echo json_encode(array('error'=>false,'mensaje'=>'Giro registrador de forma correcta','giro'=>$idTransaccion));
                    exit();
                    // header('location: ' . base_url() . "Transacciones/nuevaTransaccion?transaccion={$idTransaccion}");
                }elseif($pesos <= $saldoDisponible->pesos){
                    $this->db->trans_start();
                    $cliente = ['idClientes' => null, 'nombre' => $nombreCliente, 'telefono' => $telefono,'cedula'=>$cedulaCliente];
                    $idCliente = $this->insertarCliente($cliente,$cedulaCliente);
                    $beneficiario = ['id' => null,'nombre' => $nombre, 'cedula' => $cedula];
                    $rowBeneficiario = $this->insertarBeneficiario($beneficiario,$cedula);
                    $cuentaBeneficiario = ['numero' => $cuenta, 'banco' => $banco, 'tipo' => $tipoCuenta, 'beneficiario' => $rowBeneficiario->id];
                    $this->insertarCuenta($cuentaBeneficiario,$cuenta);
                    $transaccion = ['referenciaTransaccion' => null, 'monto' => $monto, 'fechaInicio' => $fecha, 'marcaTiempo' => null,'estado' => ESTADO_TRANSACCION_PENDIENTE, 'cuentaBeneficiario' => $cuenta, 'tasa' => $tasa, 'pesos' => $pesos,"tienda" => $tienda,'cliente' => $idCliente->idClientes];
                    $idTransaccion = $this->insertarTransaccion($transaccion);
                    
                    $saldoNuevo = $saldoDisponible->pesos - $pesos;
                    $set['saldoTienda'] = ['pesos' => $saldoNuevo];
                    $where['saldoTienda'] = ['id' => $this->session->userdata('id')];
                    $this->CuentasModel->updateCuentaTienda($set['saldoTienda'],$where['saldoTienda']);
                    $this->db->trans_complete();
                    echo json_encode(array('error'=>false,'mensaje'=>'Giro registrador de forma correcta','giro'=>$idTransaccion));
                    exit();
                    header('location: ' . base_url() . "Transacciones/nuevaTransaccion?transaccion={$idTransaccion}");
                }
            }else {
                echo json_encode(array('error'=>true,'mensaje'=>'Por favor completar todos los campos'));
                header('location: ' . base_url() . 'Transacciones/registro');
            }
            #Datos del beneficiario que seán insertados en la tabla
        }
        public function registrarTasa(){
            $nuevaTasa = $this->input->post('tasa');
            // $tasa = ['idTasa' => null, 'valor' => $nuevaTasa];
            $this->TransaccionesModel->updateTasa($nuevaTasa);
            header('location: ' . base_url());
        }
        public function registro(){
            #llamar a al modelo de los usuarios para traer la informacion de las cuentas, agregarla en un array que luego sea enviado a la vista para que sea presentado en el formulario de registro de transferencias
            if($this->session->userdata('login')){
                $this->getTasa();
                if(($this->session->userdata('tipo') == USUARIO_TIENDA && $this->session->userdata('grupo') != GRUPO_EMPRESA)  || $this->session->userdata('tipo') == USUARIO_DISTRIBUIDOR || $this->session->userdata('tipo') == USUARIO_SUBDISTRIBUIDOR){
                    $this->datos['cuenta'] = $this->getCuentasTiendas();
                }
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('transacciones/registro',$this->datos);
                $this->load->view('overall/footer');
            }else {
                $this->session->sess_destroy();
                header('location: ' . base_url() . 'home');
            }
        }
        public function tasa(){
            $this->getTasa();
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('transacciones/registroTasa',$this->datos);
            $this->load->view('overall/footer');
        }
        public function transaccionNueva(){
            $transaccion = $this->input->get('transaccion');
            $datos['transaccion'] = $this->getTransaccion($transaccion);
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('transacciones/transaccionNueva',$datos);
            $this->load->view('overall/footer');
        }
        public function verificacion(){
            $transaccion = $this->input->get('transaccion');
            $where = ['referenciaTransaccion' => $transaccion];
            $datos['transaccion'] = $this->TransaccionesModel->getBusqueda($where,true);
            if($datos['transaccion']->estado != ESTADO_TRANSACCION_PROCESADO && $datos['transaccion']->estado != ESTADO_TRANSACCION_CANCELADO){
                $this->load->view('overall/header');
                $this->load->view('overall/nav');
                $this->load->view('transacciones/verificarTransaccion',$datos);
                $this->load->view('overall/footer');
            }else{
                header('location: ' . base_url());
            }
        }
        public function verificarTransaccion(){
            $referencia = $this->input->post('referencia');
            $monto = $this->input->post('monto');
            $fecha = $this->input->post('fecha');
            $tasa = $this->input->post('tasa');
            $pesos = $this->input->post('pesos');
            $nacionalidad = $this->input->post('nacionalidad');
            $numeroCedula = $this->input->post('numeroCedula');
            $cedula = $nacionalidad . '-' . $numeroCedula;
            $nombre = $this->input->post('nombre');
            $cuenta = $this->input->post('cuenta');
            $banco = $this->determinarBanco($cuenta);
            $tipoCuenta = $this->input->post('tipoCuenta');
            $id = $this->input->post('idBeneficiario');

                $beneficiario = ['nombre' => $nombre, 'cedula' => $cedula];
                $rowBeneficiario = $this->updateBeneficiario($beneficiario,$id);
                $cuentaBeneficiario = ['numero' => $cuenta, 'banco' => $banco, 'tipo' => $tipoCuenta, 'beneficiario' => $rowBeneficiario->id];
                $this->updateCuenta($cuentaBeneficiario,$cuenta);
                $set = array(
                    'estado' => ESTADO_TRANSACCION_PENDIENTE,
                    'cuentaBeneficiario' => $cuenta,
                    'girador' => null
                );
                $where = ['referenciaTransaccion' => $referencia];
                $this->TransaccionesModel->updateEstado($set,$where);
                header('location: ' . base_url() . "Transacciones/nuevaTransaccion?transaccion={$referencia}");
            
        }
        # Funciones Privadas del modulo de Tranasacciones. 9864576 -> 9864586
        
        #la funcion giradores en estos momentos está inservible, ya que está siendo suplantada por la funcion getPersonal()
        
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
        private function getCuentasTiendas($tienda = null,$row = false){
            $select = [DB_PREFIX."cuentastiendas.id AS 'id'", DB_PREFIX."personal.nombre AS 'nombre'", DB_PREFIX."personal.apellido AS 'apellido'", 'pesos'];
            switch($this->session->userdata('tipo')){
                case USUARIO_ADMINISTRADOR:
                    if($tienda){
                        $where = ['cuentastiendas.id' => $tienda];
                    }else{
                        $where = [];
                    }
                break;
                case USUARIO_TIENDA:
                    $tienda = $this->session->userdata('id');
                    $where = ['cuentastiendas.id' => $tienda];
                    $row = true;
                break;
                case USUARIO_DISTRIBUIDOR:
                    $where = ['cuentastiendas.id' => $this->session->userdata('id')];
                    $row = true;
                break;
                case USUARIO_SUBDISTRIBUIDOR:
                    $where = ['cuentastiendas.id' => $this->session->userdata('id')];
                    $row = true;
                break;
                case USUARIO_GIRADOR:
                    $where = ['cuentastiendas.id' => $tienda];
                break;

            }
            return $this->CuentasModel->getCuentasTiendas($select,$where,$row);
        }
        private function getPersonal(){
            $this->load->model('UsuariosModel');
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    $select = 'gvzla_personal.id, nombre, apellido, cedula';
                    $where = ['tipoUsuario' => USUARIO_GIRADOR,'estado' => ESTADO_USUARIO_ENEABLE];
                    break;
                case USUARIO_SUPERVISOR:
                    $select = 'gvzla_personal.id, nombre, apellido, cedula';
                    $where = ['grupo' => $this->session->userdata('grupo'), 'tipoUsuario' => USUARIO_GIRADOR, 'estado' => ESTADO_USUARIO_ENEABLE];
                    break;
            }
            $this->datos['giradores'] = $this->UsuariosModel->getPersonal($select,$where);
        }
        private function getSupervisor(array $grupo, $select ='*', bool $row = false){
            $this->load->model('UsuariosModel');
            return $this->UsuariosModel->getPersonal($select,$grupo,$row);
        }
        private function getTasa(){
            $this->datos['tasa'] = $this->TransaccionesModel->getTasa();
        }
        private function getTransaccion($transaccion){
            $select = array(
                "monto",
                "tasa",
                "pesos",
                "girador",
                "tienda",
                "fechaInicio AS 'fecha'",
                "cuentaBeneficiario AS 'cuenta'",
                "referenciaTransaccion AS 'referencia'",
                DB_PREFIX."beneficiarios.cedula AS 'cedula'",
                DB_PREFIX."beneficiarios.nombre AS 'beneficiario'",
                DB_PREFIX."cuentasbeneficiarios.banco AS 'banco'",
                DB_PREFIX."transacciones.estado AS 'estado'",
                DB_PREFIX."usuarios.grupo AS 'grupoTienda'",
                DB_PREFIX."clientes.nombre AS 'nombreCliente'",
                DB_PREFIX."clientes.telefono AS 'telefono'",
                DB_PREFIX."usuarios.tipoUsuario AS 'tipoUsuario'",
                DB_PREFIX."usuarios.grupo AS 'grupoUsuario'"
            );
            $where = ['referenciaTransaccion' => $transaccion];
            return $this->TransaccionesModel->getTransaccionesGeneral($select,$where,[],true);
        }
        private function getTransaccionesSinAsignar(){
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    $where = ['estado' => ESTADO_TRANSACCION_PENDIENTE, 'girador' => null];
                    break;
                case USUARIO_SUPERVISOR:
                    $supervisor = $this->session->userdata('id');
                    $where = ['estado' => ESTADO_TRANSACCION_PENDIENTE,'girador' => $supervisor];
                    break;
                case USUARIO_GIRADOR:
                    $girador = $this->session->userdata('id');
                    $where = ['estado' => ESTADO_TRANSACCION_PENDIENTE, 'girador' => $girador];
                    break;
            }
            $this->datos['transacciones'] = $this->TransaccionesModel->transaccionesGirador($where);
        }
        private function giradores(){
            $this->load->model('UsuariosModel');
            switch ($this->session->userdata('tipo')) {
                case USUARIO_ADMINISTRADOR:
                    $this->datos['giradores'] = $this->UsuariosModel->getGiradores('gvzla_personal.id, nombre, apellido, cedula');
                    break;
                case USUARIO_SUPERVISOR:
                    $this->datos['giradores'] = $this->UsuariosModel->getGiradoresGrupo('personal.id, nombre, apellido, cedula',array('grupo' => $this->session->userdata('grupo')));
                    break;
            }
        }
        private function insertarBeneficiario(array $beneficiario,$cedula){
            #$beneficiario = ['id' => null,'nombre' => $nombre, 'cedula' => $cedula];

                #Busca el beneficiario y verifica su existencia
            $this->load->model('BeneficiariosModel');
            $rowBeneficiario = $this->BeneficiariosModel->getBeneficiariosWhere(array ('cedula' => $cedula));
            
            if (empty($rowBeneficiario)) {
                $this->BeneficiariosModel->setBeneficiario($beneficiario);
                // $rowBeneficiario = $this->db->insert_id();
                $rowBeneficiario = $this->BeneficiariosModel->getBeneficiariosWhere(array ('cedula' => $cedula));
            }else{
            }
            return $rowBeneficiario;
        }
        private function insertarCliente(array $cliente,$cedula){
            
            $rowCliente = $this->ClientesModel->getClientes(['*'],['cedula'=>$cedula],[],true);
            
            if (empty($rowCliente)) {
                $this->ClientesModel->setClientes($cliente);
                $rowCliente = $this->ClientesModel->getClientes(['*'],['cedula'=>$cedula],[],true);
            }else{
                //Where para la actualizacion de datos
                $set = ['nombre'=>$cliente['nombre'],'telefono'=>$cliente['telefono']];
                $where = ['idClientes'=>$rowCliente->idClientes];
                $this->ClientesModel->update($set,$where);
                $rowCliente = $this->ClientesModel->getClientes(['*'],['idClientes'=>$rowCliente->idClientes],[],true);
            }
            return $rowCliente;
        }
        private function insertarCuenta(array $cuentaBeneficiario,$cuenta){
            $rowCuenta = $this->BeneficiariosModel->getCuentasBeneficiariosWhere(array ('numero' => $cuenta));

            if (empty($rowCuenta)) {
                $this->BeneficiariosModel->setCuentas($cuentaBeneficiario);
            }else{
                $set = ['beneficiario' => $cuentaBeneficiario['beneficiario']];
                $where = ['numero' => $cuenta];
                $this->BeneficiariosModel->updateCuentas($set,$where);
            }
        }
        private function insertarTransaccion(array $transaccion){
            return $this->TransaccionesModel->setTransacciones($transaccion);
        }
        private function obtenerTransacciones(){
            $select = ["fechaInicio AS 'fecha'", "referenciaTransaccion AS 'referencia'", "girador", DB_PREFIX."beneficiarios.cedula AS 'cedula'", DB_PREFIX."beneficiarios.nombre AS 'beneficiario'", DB_PREFIX."cuentasbeneficiarios.banco AS 'banco'", "cuentaBeneficiario AS 'cuenta'", "monto", "tasa", "pesos", DB_PREFIX."transacciones.estado AS 'estado'","tienda",DB_PREFIX . "personal.nombre AS 'nombreTienda'", DB_PREFIX . "personal.apellido AS 'apellidoTienda'"];
            $whereOr = [];
            if ($this->session->userdata('tipo') != USUARIO_SUPERVISOR){
                switch($this->session->userdata('tipo')){
                    case USUARIO_ADMINISTRADOR:
                        $where = ['transacciones.estado !='=> ESTADO_TRANSACCION_PROCESADO,'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO];
                    break;
                    case USUARIO_GIRADOR:
                        $girador = $this->session->userdata('id');
                        $where = ['transacciones.estado'=> ESTADO_TRANSACCION_PENDIENTE,'girador' => $girador];
                    break;
                    case USUARIO_TIENDA:
                        $tienda = $this->session->userdata('id');
                        $where = ['transacciones.estado !='=> ESTADO_TRANSACCION_PROCESADO,'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO,'tienda' => $tienda];
                    break;
                    case USUARIO_DISTRIBUIDOR:
                        $where = ['transacciones.estado !='=> ESTADO_TRANSACCION_PROCESADO,'usuarios.grupo'=>$this->session->userdata('grupo'),'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO];
                    break;
                    case USUARIO_SUBDISTRIBUIDOR:
                        $where = ['transacciones.estado !='=> ESTADO_TRANSACCION_PROCESADO,'usuarios.subgrupo'=>$this->session->userdata('subgrupo'),'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO];
                    break;
                }
                return $this->TransaccionesModel->getTransaccionesGeneral($select,$where,$whereOr);
            }else{
                $grupo = $this->session->userdata('grupo');
                $where = ['transacciones.estado !='=> ESTADO_TRANSACCION_PROCESADO,'transacciones.estado != ' => ESTADO_TRANSACCION_CANCELADO,'usuarios.grupo' => $grupo];
                return $this->TransaccionesModel->getTransaccionesSupervisor($select,$where,$whereOr);
            }
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
        private function update(array $set,array $where){
            $this->TransaccionesModel->updateEstado($set,$where);
        }
        private function updateBeneficiario(array $beneficiario,$id){
            #$beneficiario = ['id' => null,'nombre' => $nombre, 'cedula' => $cedula];

                #Busca el beneficiario y verifica su existencia
            $this->load->model('BeneficiariosModel');
            $where = ['id' => $id];
            $this->BeneficiariosModel->updateBeneficiarios($beneficiario,$where);
            $rowBeneficiario = $this->BeneficiariosModel->getBeneficiariosWhere(array ('id' => $id));
            return $rowBeneficiario;
        }
        private function updateCuenta(array $cuentaBeneficiario,$cuenta){
            $rowCuenta = $this->BeneficiariosModel->getCuentasBeneficiariosWhere(array ('numero' => $cuenta));

            if (empty($rowCuenta)) {
                $this->BeneficiariosModel->setCuentas($cuentaBeneficiario);
            }else{
                $set = ['beneficiario' => $cuentaBeneficiario['beneficiario'],'tipo' => $cuentaBeneficiario['tipo']];
                $where = ['numero' => $cuenta];
                $this->BeneficiariosModel->updateCuentas($set,$where);
            }
        }
    }
    ?>
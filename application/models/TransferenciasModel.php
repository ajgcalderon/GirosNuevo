<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class TransferenciasModel extends CI_Model{
        
        function __construct(){
            parent::__construct();
        }

        public function getTransferencias($where = array (), string $select = '*', bool $row = false, int $limit = null){
            $this->db->where($where);
            $this->db->select($select);
            #cambios
            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER')->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');

            #Fin de los Cambios
            if ($row) {
                return $this->db->get('transferencias',$limit)->row();
            }else{
                return $this->db->get('transferencias',$limit)->result();
            }
        }
        public function getTransferenciasTiendas($where = array (), $select = '*', bool $row = false, int $limit = null){
            $this->db->where($where);
            $this->db->select($select);
            #cambios
            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER')->join('usuarios','transacciones.tienda = usuarios.id','INNER')->join('personal','usuarios.id = personal.id','INNER')->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER');

            #Fin de los Cambios
            if ($row) {
                return $this->db->get('transferencias',$limit)->row();
            }else{
                return $this->db->get('transferencias',$limit)->result();
            }
        }
        
        public function getRealizadas($where = [],int $limit = null){
            if($this->session->userdata('tipo') != USUARIO_TIENDA){ 
                $this->db->select(array("idTransferencia",DB_PREFIX."transferencias.estadoTransferencia as 'estado'","referencia", "captura", DB_PREFIX."transacciones.monto as 'monto'", DB_PREFIX."transacciones.pesos as 'pesos'", DB_PREFIX."transacciones.tasa as 'tasa'", "fecha", DB_PREFIX . "beneficiarios.nombre as 'beneficiario'", DB_PREFIX ."personal.nombre AS 'girador'", DB_PREFIX . "personal.apellido AS 'apellidoGirador'", "transaccion", DB_PREFIX . "transacciones.tienda AS 'tienda'"));
            }else{
                $this->db->select(array("idTransferencia",DB_PREFIX."transferencias.estadoTransferencia as 'estado'","referencia", "captura", DB_PREFIX."transacciones.monto as 'monto'", DB_PREFIX."transacciones.pesos as 'pesos'", DB_PREFIX."transacciones.tasa as 'tasa'", "fecha", DB_PREFIX . "beneficiarios.nombre as 'beneficiario'", DB_PREFIX ."personal.nombre AS 'girador'", DB_PREFIX . "personal.apellido AS 'apellidoGirador'", "transaccion", DB_PREFIX . "transacciones.tienda AS 'tienda'"));
            }
            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER')->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
            $this->db->where($where);
            
            return $this->db->get('transferencias',$limit)->result();
        }
        public function getRealizadasDist($where = [],int $limit = null){
            $this->db->select(array("idTransferencia","referencia", "captura", DB_PREFIX."transacciones.monto as 'monto'", DB_PREFIX."transacciones.pesos as 'pesos'", DB_PREFIX."transacciones.tasa as 'tasa'", "fecha", DB_PREFIX . "beneficiarios.nombre as 'beneficiario'", DB_PREFIX ."personal.nombre AS 'girador'", DB_PREFIX . "personal.apellido AS 'apellidoGirador'", "transaccion", DB_PREFIX . "transacciones.tienda AS 'tienda'"));
            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER')->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('personal','transacciones.tienda = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
            $this->db->where($where);
            
            return $this->db->get('transferencias',$limit)->result();
        }

        public function getBusqueda($where = [],bool $row = false, int $limit = null){
            $this->db->select("idTransferencia","referencia, gvzla_transacciones.monto as 'monto', fecha, gvzla_beneficiarios.nombre as 'beneficiario', gvzla_personal.nombre AS 'girador', captura");

            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER')->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');

            foreach ($where as $key => $value) {
                $this->db->where($key,$value);
            }            
            $this->db->order_by('fecha','DESC');
            if($row){
                return $this->db->get('transferencias',$limit)->row();
            }else{
                return $this->db->get('transferencias',$limit)->result();
            }
        }

        public function setTransferencias(array $datos){
            $registro = $this->db->insert('transferencias',$datos);
        }

        public function TransferenciasRealizadasLD(array $where, $select = array('*')){
            $this->db->select($select);
            $this->db->get_where('transferencias',$where);

        }

        public function updateTrasferencia(array $set,$where){
            $this->db->update('transferencias',$set,"referencia = $where");
        }

        public function getTransferenciasDevueltas($fecha,array $where = []){
            $this->db->where('estadoTransferencia',2);
            $this->db->like('timestamp',"$fecha");
            $this->db->where($where);
            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER')->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
            return $this->db->get('transferencias')->result();
        }
        public function getTransferenciasDevueltasDist($fecha,array $where = []){
            $this->db->where('estadoTransferencia',2);
            $this->db->like('timestamp',"$fecha");
            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER')->join('personal','transacciones.tienda = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
            $this->db->where($where);
            return $this->db->get('transferencias')->result();
        }

        public function getTransferidoHoy($select,array $where = []){
            $this->db->select($select);
            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER')->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
            $this->db->where($where);
            return $this->db->get('transferencias')->result();
        }
        public function getTransferidoHoyDist($select,array $where = []){
            $this->db->select($select);
            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER')->join('personal','transacciones.tienda = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
            $this->db->where($where);
            return $this->db->get('transferencias')->result();
        }
        public function getTransferidoHoy2($fecha,array $where = []){
            $this->db->select_sum("gvzla_transacciones.monto",'monto');
            $this->db->where('transferencias.fecha',$fecha);
            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER');
            
            if ($where) {
                $this->db->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
                foreach ($where as $key => $value) {
                    $this->db->where($key,$value);
                }
            }
            
            return $this->db->get('transferencias')->row();
        }

        public function getTransferenciasDV($where = [],$row = false){
            $this->db->select("idTransferencia as 'id', referencia, fecha, gvzla_transacciones.monto as 'monto', gvzla_beneficiarios.nombre as 'beneficiario', gvzla_personal.nombre as 'girador',gvzla_transacciones.referenciaTransaccion AS 'transaccion',gvzla_transacciones.pesos as 'pesos', gvzla_transacciones.tasa as 'tasa',gvzla_transacciones.tienda as 'tienda',timestamp as 'fechaDev'");
            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER')->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
            $this->db->where($where);
            if ($row) {
                return $this->db->get('transferencias')->row();
            }else{
                return $this->db->get('transferencias')->result();
            }
        }

        public function getTransferenciasDVDist($where = [],$row = false){
            $this->db->select("idTransferencia as 'id', referencia, fecha, gvzla_transacciones.monto as 'monto', gvzla_beneficiarios.nombre as 'beneficiario', gvzla_personal.nombre as 'girador',gvzla_transacciones.referenciaTransaccion AS 'transaccion',gvzla_transacciones.pesos as 'pesos', gvzla_transacciones.tasa as 'tasa',,timestamp as 'fechaDev'");
            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER')->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('personal','transacciones.tienda = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
            $this->db->where($where);
            if ($row) {
                return $this->db->get('transferencias')->row();
            }else{
                return $this->db->get('transferencias')->result();
            }
        }

        public function tgiradores(){
            $this->db->query("SELECT * FROM gvzla_transferencias WHERE fecha >= '2019-01-06' and fecha <= '2019-01-12'")->result();
            
        }

        public function filtrarFecha($desde,$hasta){
            // return $this->db->query("SELECT * FROM gvzla_transferencias WHERE fecha between CAST({$desde} AS DATE) and CAST({$hasta} AS DATE)");
            $this->db->select("referencia, captura, gvzla_transacciones.monto as 'monto', fecha, gvzla_beneficiarios.nombre as 'beneficiario', gvzla_personal.nombre AS 'girador'");

            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER')->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');

            // $this->db->where("fecha between CAST({$desde} AS DATE) and CAST({$hasta} AS DATE)");
            $this->db->where("fecha>=CAST({$desde} AS DATE) and fecha<=CAST({$hasta} AS DATE)");

            return $this->db->get('transferencias')->result();
        }
        public function getTransferenciasNew(array $select,array $where,bool $row = false){
            $this->db->select($select);
            $this->db->join('transacciones','transferencias.transaccion = transacciones.referenciaTransaccion','INNER')->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
            $this->db->where($where);
            if ($row){
                return $this->db->get('transferencias')->row();
            }else{
                return $this->db->get('transferencias')->result();
            }
        }
    }
?>


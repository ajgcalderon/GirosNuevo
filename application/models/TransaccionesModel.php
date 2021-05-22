<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    class TransaccionesModel extends CI_Model
    {
        public function getBusqueda(array $where = [],bool $row = false){
            $this->db->select("fechaInicio AS 'fecha', referenciaTransaccion AS 'referencia', gvzla_beneficiarios.cedula AS 'cedula',gvzla_beneficiarios.id AS 'idBeneficiario', gvzla_beneficiarios.nombre AS 'beneficiario', gvzla_cuentasbeneficiarios.banco AS 'banco',, gvzla_cuentasbeneficiarios.tipo AS 'tipoCuenta', cuentaBeneficiario AS 'cuenta', monto, tasa, pesos, estado, tienda, gvzla_clientes.nombre AS 'nombreCliente', gvzla_clientes.telefono AS 'telefono', gvzla_clientes.cedula as 'cedulaCliente'");
            $this->db->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER');
            $this->db->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('clientes','transacciones.cliente = clientes.idClientes','INNER');

            $this->db->where($where);

            if($row){
                return $this->db->get('transacciones')->row();
            }else{
                return $this->db->get('transacciones')->result();
            }
        }
        public function getTasa(){
            return $this->db->get('tasas')->row();
        }
        public function getTransferencia($where,string $select = '*',bool $row = true){
            $this->db->select($select);
            $this->db->join('transferencias','transacciones.referenciaTransaccion = transferencias.transaccion','INNER');
            $this->db->where($where);
            if($row){
                return $this->db->get('transacciones')->row();
            }else{
                return $this->db->get('transacciones')->result();
            }

        }
        public function getTransacciondeTransferencia($where,$select = 'referenciaTransaccion as referencia',bool $row = true){
            $this->db->select($select);
            $this->db->join('transferencias','transacciones.referenciaTransaccion = transferencias.transaccion','INNER')->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id')->join('clientes','transacciones.cliente = clientes.idClientes','INNER');
            $this->db->where($where);
            return ($row) ? $this->db->get('transacciones')->row() : $this->db->get('transacciones')->result();
        }
        public function getTransacciones($select = array('*'),array $where = [],bool $row = false){
            $this->db->select($select);
            if($where){
                $this->db->where($where);
            }
            $this->db->join('cuentasbeneficiarios','transacciones.cuentabeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
            if($row){
                return $this->db->get('transacciones')->row();
            }else{
                return $this->db->get('transacciones')->result();
            }
        }
        public function getTransaccionesDetalles(array $select = ['*'],array $where = [],array $whereOr = [], bool $row = false){
            $this->db->select($select);
            $this->db->or_where($whereOr)->where($where);
            $this->db->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','transacciones.tienda = usuarios.id','INNER');
            if($row){
                return $this->db->get('transacciones')->row();
            }else{
                return $this->db->get('transacciones')->result();
            }
        }
        public function getTransaccionesGeneral(array $select = ['*'],array $where = [],array $whereOr = [], bool $row = false){
            $this->db->select($select);
            $this->db->or_where($whereOr);
            $this->db->where($where);
            $this->db->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('usuarios','transacciones.tienda = usuarios.id','INNER')->join('personal','usuarios.id = personal.id','INNER')->join('clientes','transacciones.cliente = clientes.idClientes','INNER');
            if($row){
                return $this->db->get('transacciones')->row();
            }else{
                return $this->db->get('transacciones')->result();
            }
        }
        public function getTransaccionesPendientes($where = ['estado' => ESTADO_TRANSACCION_PENDIENTE]){
            $this->db->select("fechaInicio AS 'fecha', referenciaTransaccion AS 'referencia', gvzla_beneficiarios.cedula AS 'cedula', gvzla_beneficiarios.nombre AS 'beneficiario', gvzla_cuentasbeneficiarios.banco AS 'banco', cuentaBeneficiario AS 'cuenta', monto, tasa, pesos, estado");
            $this->db->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER');
            $this->db->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER');

            // $this->db->where('estado',ESTADO_TRANSACCION_PENDIENTE);
            $this->db->where($where);
            return $this->db->get('transacciones')->result();
            // return $this->db->get_where('transacciones',$where)->result();
        }
        public function getTransaccionesSupervisor(array $select = ['*'],array $where = [],array $whereOr = [], bool $row = false){
            $this->db->select($select);
            $this->db->or_where($whereOr)->where($where);
            $this->db->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id');
            if($row){
                return $this->db->get('transacciones')->row();
            }else{
                return $this->db->get('transacciones')->result();
            }
        }
        public function getTransaccionesTienda(array $select = ['*'],array $where = [], bool $row = false){
            $this->db->select($select);
            if($where){
                $this->db->where($where);
            }
            $this->db->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('usuarios','transacciones.tienda = usuarios.id');
            if($row){
                return $this->db->get('transacciones')->row();
            }else{
                return $this->db->get('transacciones')->result();
            }
        }
        public function getTransaccionWhere($select = array ('*'), array $where, bool $row = true){
            $this->db->select($select);
            $this->db->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
            foreach ($where as $key => $value) {
                $this->db->where($key,$value);
            }
            if (!$row) {
                return $this->db->get('transacciones')->result();
            }
            return $this->db->get('transacciones')->row();
        }
        public function pendientesPorBanco(array $where){
            $this->db->select("referenciaTransaccion, monto, cuentaBeneficiario, gvzla_cuentasbeneficiarios.banco, gvzla_beneficiarios.nombre as 'nombre', pesos");
            $this->db->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER');
            
            foreach ($where as $key => $value) {
                $this->db->where($key,$value);
            }
            return $this->db->get('transacciones')->result();
        }
        public function setTasa(array $datos){
            $this->db->insert('tasas',$datos);
        }
        public function setTransacciones(array $datos){
            $this->db->insert('transacciones',$datos);
            return $this->db->insert_id();
        }
        public function sumaPendientes(){
            $this->db->select_sum('monto','monto');
            $this->db->where('estado',ESTADO_TRANSACCION_PENDIENTE);
            $this->db->get('transacciones')->row();
        }
        public function sumaRealizadas(){
            $this->db->select_sum('monto','monto');
            $this->db->where('estado',ESTADO_TRANSACCION_PROCESANDO);
            $this->db->get('transacciones')->row();
        }
        public function resumenTotal(array $select = ['*'],array $where = [], bool $row = false){
            $this->db->select($select);
            if($where){
                $this->db->where($where);
            }
            $this->db->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('transferencias','transacciones.referenciaTransaccion = transferencias.transaccion','INNER');
            if($row){
                return $this->db->get('transacciones')->row();
            }else{
                return $this->db->get('transacciones')->result();
            }
        }
        public function transaccionesGirador($where = null,int $estado = 1){
            $this->db->select("fechaInicio AS 'fecha', referenciaTransaccion AS 'referencia', gvzla_beneficiarios.cedula AS 'cedula', gvzla_beneficiarios.nombre AS 'beneficiario', gvzla_cuentasbeneficiarios.banco AS 'banco', cuentaBeneficiario AS 'cuenta', monto, tasa, pesos");
            $this->db->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER');
            $this->db->where($where);
            // $this->db->where('girador',$where);
            // $this->db->where('estado',1);

            return $this->db->get('transacciones')->result();
        }
        public function updateEstado(array $set,array $where){
            $this->db->update('transacciones',$set,$where);
        }
        public function updateTasa($tasaNueva){
            $set = ['valor' => $tasaNueva];
            $where = ['idTasa' => 1];
            $this->db->update('tasas',$set,$where);
        }
    }
    
?>
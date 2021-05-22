<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class CuentasModel extends CI_Model{

        public function getCuentas($campos = '*', array $where = [], $row = false){
            // $this->db->query('SELECT cuentasgiradores.tipo FROM usuarios INNER JOIN personal on usuarios.id = personal.id INNER JOIN cuentasgiradores on personal.id = cuentasgiradores.girador');
            $this->db->select($campos);
            if ($where) {
                foreach ($where as $key => $value) {
                    $this->db->where($key,$value);
                }
            }
            $this->db->join('personal','cuentasgiradores.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
            $cuentas = $this->db->get('cuentasgiradores');
            if (!$row) {
                return $cuentas->result();
            }else{
                return $cuentas->row();
            }
            #agregar el id del personal comparado con el id de la sesion en un where al final para filtrar solo las cuentas del usuario
        }
        public function getCuentasTiendas(array $campos = ['*'], array $where = [], $row = false){
            // $this->db->query('SELECT cuentasgiradores.tipo FROM usuarios INNER JOIN personal on usuarios.id = personal.id INNER JOIN cuentasgiradores on personal.id = cuentasgiradores.girador');
            $this->db->select($campos);
            $this->db->where($where);
            $this->db->join('usuarios','cuentastiendas.id = usuarios.id','INNER')->join('personal','usuarios.id = personal.id','INNER');
            $cuentas = $this->db->get('cuentastiendas');
            if (!$row) {
                return $cuentas->result();
            }else{
                return $cuentas->row();
            }
            #agregar el id del personal comparado con el id de la sesion en un where al final para filtrar solo las cuentas del usuario
        }

        public function updateCuenta(array $set,array $where){
            $this->db->update('cuentasgiradores',$set,$where);
        }
        public function updateCuentaTienda(array $set,array $where){
            $this->db->update('cuentastiendas',$set,$where);
        }

        public function getSaldos(string $select,array $where = []){
            $this->db->select($select);
            $this->db->join('personal','cuentasgiradores.girador = personal.id','INNER');
            $this->db->join('usuarios','personal.id = usuarios.id');
            if($where){
                foreach ($where as $key => $value) {
                    $this->db->where($key,$value);
                }
            }
            return $this->db->get('cuentasgiradores')->result();
        }

        public function setCuenta(array $datos){
            $this->db->insert('cuentasgiradores',$datos);
        }
        public function setCuentaTienda(array $datos){
            $this->db->insert('cuentastiendas',$datos);
        }


    }
?>
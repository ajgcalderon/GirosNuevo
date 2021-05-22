<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class RecargasModel extends CI_Model{

        public function getRecargas($campos = ['*'], array $where = [], $row = false){
            // $this->db->query('SELECT cuentasgiradores.tipo FROM usuarios INNER JOIN personal on usuarios.id = personal.id INNER JOIN cuentasgiradores on personal.id = cuentasgiradores.girador');
            $this->db->select($campos);
            $this->db->where($where);
            $this->db->join('cuentastiendas','recargastiendas.tienda = cuentastiendas.id')->join('usuarios','cuentastiendas.id = usuarios.id','INNER')->join('personal','usuarios.id = personal.id','INNER');
            $cuentas = $this->db->get('recargastiendas');
            if (!$row) {
                return $cuentas->result();
            }else{
                return $cuentas->row();
            }
            #agregar el id del personal comparado con el id de la sesion en un where al final para filtrar solo las cuentas del usuario
        }
        public function getRecargasOr($campos = ['*'], $where = [],array $where_or = [], $row = false){
            // $this->db->query('SELECT cuentasgiradores.tipo FROM usuarios INNER JOIN personal on usuarios.id = personal.id INNER JOIN cuentasgiradores on personal.id = cuentasgiradores.girador');
            $this->db->select($campos);
            $this->db->where($where);
            $this->db->or_where($where_or);
            $this->db->join('cuentastiendas','recargastiendas.tienda = cuentastiendas.id')->join('usuarios','cuentastiendas.id = usuarios.id','INNER')->join('personal','usuarios.id = personal.id','INNER');
            $cuentas = $this->db->get('recargastiendas');
            if (!$row) {
                return $cuentas->result();
            }else{
                return $cuentas->row();
            }
            #agregar el id del personal comparado con el id de la sesion en un where al final para filtrar solo las cuentas del usuario
        }
        public function updateRecarga(array $set,array $where){
            $this->db->update('recargastiendas',$set,$where);
        }
        public function setRecarga(array $datos){
            $this->db->insert('recargastiendas',$datos);
        }
        public function deleteRecarga(array $where){
            $this->db->where($where);
            $this->db->delete('recargastiendas');
        }


    }
?>
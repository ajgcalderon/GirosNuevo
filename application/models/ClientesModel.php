<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class ClientesModel extends CI_Model{
        public function getClientes($select = ['*'],$where = [],$orWhere = [], bool $row = false){
            $this->db->select($select)->where($where);
            $this->db->or_where($orWhere);
            if($row){
                return $this->db->get('clientes')->row();
            }else{
                return $this->db->get('clientes')->result();
            }
        }
        public function getClientesOr($select = ['*'],$where_or = [], bool $row = false){
            $this->db->select($select)->or_where($where_or);
            if($row){
                return $this->db->get('clientes')->row();
            }else{
                return $this->db->get('clientes')->result();
            }
        }
        public function setClientes(array $datos){
            $this->db->insert('clientes',$datos);
            return $this->db->insert_id();
        }
        public function update(array $set, array $where){
            $this->db->update('clientes',$set,$where);
        }
    }
?>
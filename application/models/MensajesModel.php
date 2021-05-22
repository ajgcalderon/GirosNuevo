<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class MensajesModel extends CI_Model{
        public function get($select = ['*'],$where = [], bool $row = false){
            $this->db->select($select)->where($where);
            if($row){
                return $this->db->get('mensajes')->row();
            }else{
                return $this->db->get('mensajes')->result();
            }
        }
        public function set(array $datos){
            $this->db->insert('mensajes',$datos);
            return $this->db->insert_id();
        }
        public function update(array $set,array $where){
            $this->db->update('mensajes',$set,$where);
        }
    }
?>
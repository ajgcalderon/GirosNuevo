<?php

    defined('BASEPATH') OR exit('No direct script access allowed');
    class UsuariosModel extends CI_Model{
        
        public function login($username, $password){
            $this->db->where(array('nombreUsuario'=>$username,'estado'=>1));

            $data = $this->db->get('usuarios');

            if ($data->num_rows()>0) {
                $r = $data->row();
                if ($this->encryption->decrypt($r->clave) === $this->encryption->decrypt($password)) {
                    return $r;
                }
            }else {
                return false;
            }
        }

        public function getGiradores(string $select = '*'){
            $this->db->select($select);
            $this->db->join('usuarios','personal.id = usuarios.id','INNER');
            $this->db->where('tipoUsuario',3);
            return $this->db->get('personal')->result();
        }
        public function getPersonal($select = '*',array $where = [], bool $row = false){
            $this->db->select($select);
            $this->db->where($where);
            $this->db->join('usuarios','personal.id = usuarios.id','INNER');
            if($row){
                return $this->db->get('personal')->row();
            }else{
                return $this->db->get('personal')->result();
            }
        }
        public function getUsuario(array $select = ['*'],array $where = [],bool $row = false){
            $this->db->select($select);
            $this->db->where($where);
            $this->db->join('personal','usuarios.id = personal.id','INNER');
            if($row){
                return $this->db->get('usuarios')->row();
            }else{
                return $this->db->get('usuarios')->result();
            }
        }
        public function getGiradoresGrupo(string $select = '*', $where = array()){
            $this->db->select($select);
            $this->db->join('usuarios','personal.id = usuarios.id','INNER');
            $this->db->where($where);
            $this->db->where('tipoUsuario',3);
            return $this->db->get('personal')->result();
        }
        
        public function setPersonal(array $datos){
            $this->db->insert('personal',$datos);
            return $this->db->insert_id();
        }
        
        public function setUsuarios(array $datos){
            $this->db->insert('usuarios',$datos);
            return $this->db->insert_id();
        }

        private function prueba(){
            $this->db->trans_begin();
            $this->db->trans_rollback();
            $this->db->trans_commit();
        }
        public function updatePersonal(array $set,array $where){
            $this->db->update('personal',$set,$where);
        }
        public function updateUsuarios(array $set,array $where){
            $this->db->update('usuarios',$set,$where);
        }
    }
?>
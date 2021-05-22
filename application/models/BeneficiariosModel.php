<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class BeneficiariosModel extends CI_Model{

        public function getBeneficiarios(){
            return $this->db->get('beneficiarios')->result();
        }

        public function getBeneficiariosWhere(array $datos, $campos = array('*')){
            $this->db->select($campos);
            return $this->db->get_where('beneficiarios',$datos)->row();
        }
        public function obtenerBeneficiarios(array $select = ['*'], array $where = [''], bool $row = false){
            $this->db->select($select);
            $this->db->where($where);
            $this->db->join('cuentasbeneficiarios','beneficiarios.id = cuentasbeneficiarios.beneficiario','GET');
            if($row){
                return $this->db->get('beneficiarios')->row();
            }else{
                return $this->db->get('beneficiarios')->result();
            }
        }
        public function setBeneficiario(array $datos){
            $registro = $this->db->insert('beneficiarios',$datos);
        }

        public function getCuentasBeneficiariosWhere(array $datos, $campos = array('*')){
            $this->db->select($campos);
            return $this->db->get_where('cuentasbeneficiarios',$datos)->row();
        }
        
        public function setCuentas(array $datos){
            $registro = $this->db->insert('cuentasbeneficiarios',$datos);
            
        }
        public function updateBeneficiarios(array $set,array $where){
            $this->db->update('beneficiarios',$set,$where);
        }
        public function updateCuentas(array $set,array $where){
            $this->db->update('cuentasbeneficiarios',$set,$where);
        }
    }
?>
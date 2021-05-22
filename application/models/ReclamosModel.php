<?php

class ReclamosModel extends CI_Model{
    public function getReclamos($select = '*', array $where = [],bool $row = false){
        $this->db->select($select);
        $this->db->join('transacciones','reclamos.transaccion = transacciones.referenciaTransaccion','INNER')->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('personal','transacciones.girador = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
        $this->db->where($where);
        if($row){
            return $this->db->get('reclamos')->row();
        }else{
            return $this->db->get('reclamos')->result();
        }
    }
    public function getReclamosAdministrador(array $select = ['*'],array $where = [],bool $row = false){
        $this->db->select($select);
        $this->db->join('transacciones','reclamos.transaccion = transacciones.referenciaTransaccion')->join('cuentasbeneficiarios','transacciones.cuentaBeneficiario = cuentasbeneficiarios.numero','INNER')->join('beneficiarios','cuentasbeneficiarios.beneficiario = beneficiarios.id','INNER')->join('personal','transacciones.tienda = personal.id','INNER')->join('usuarios','personal.id = usuarios.id','INNER');
        $this->db->where($where);
        if($row){
            return $this->db->get('reclamos')->row();
        }else{
            return $this->db->get('reclamos')->result();
        }
    }
    public function setReclamo(array $set, bool $return = false){
        $this->db->insert('reclamos',$set);
        if ($return){
            return $this->db->insert_id();
        }
    }
    public function update(array $set,array $where){
        $this->db->update('reclamos',$set,$where);
    }
}

?>
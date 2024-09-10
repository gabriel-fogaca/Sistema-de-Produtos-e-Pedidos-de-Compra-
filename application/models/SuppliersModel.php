<?php

class SuppliersModel extends CI_Model
{
    public function get_all()
    {
        return $this->db->get('suppliers')->result_array();
    }

    public function get_active_suppliers()
    {
        $this->db->where('is_active', 1);
        return $this->db->get('suppliers')->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('suppliers', ['id' => $id])->row_array();
    }

    public function insert($data)
    {
        return $this->db->insert('suppliers', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('suppliers', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('suppliers');
    }
}
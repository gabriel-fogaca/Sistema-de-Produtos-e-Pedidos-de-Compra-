<?php

class ProductsModel extends CI_Model
{
    public function index()
    {
        return $this->db->get("products")->result_array();
    }

    public function store($product)
    {
        $this->db->insert("products", $product);

        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id(); 
        } else {
            return false;
        }
    }

    public function show($id = null, $search = null)
    {
        $this->db->where('active', 1);

        if ($id !== null) {
            $this->db->where('id', $id);
        }

        if ($search !== null) {
            $this->db->like('name', $search);
        }
        
        $query = $this->db->get("products");

        return $query->result_array();
    }



    public function update($id, $product)
    {
        try {
            $this->db->where("id", $id);
            $this->db->update("products", $product);

            if ($this->db->error()['code'] !== 0) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $this->db->where("id", $id);
            $this->db->update("products", array("active" => 0));

            if ($this->db->affected_rows() > 0) {
                return true;
            }

            false;
        } catch (Exception $e) {
            return false;
        }

    }
    public function inactivate($id)
{
    try {
        $this->db->where("id", $id);
        $this->db->update("products", array("active" => 0));

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    } catch (Exception $e) {
        return false;
    }
}

    public function reactivate($id)
    {
        try {
            $this->db->where("id", $id);
            $this->db->update("products", array("active" => 1));

            if ($this->db->affected_rows() > 0) {
                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

}
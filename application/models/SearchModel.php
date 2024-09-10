<?php

class SearchModel extends CI_Model
{
    public function search($search)
    {
        if (empty($search)) {
            return array();
        }

        $search = $this->input->post("search");
        $this->db->like("name", $search);
        return $this->db->get("products")->result_array();
    }
}
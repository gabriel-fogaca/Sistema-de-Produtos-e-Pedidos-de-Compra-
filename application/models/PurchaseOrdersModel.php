<?php

class PurchaseOrdersModel extends CI_Model
{
    public function get_all()
    {
        $this->db->select('purchase_orders.*, suppliers.name as supplier_name');
        $this->db->join('suppliers', 'purchase_orders.supplier_id = suppliers.id');
        return $this->db->get('purchase_orders')->result_array();
    }

    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('purchase_orders')->row_array();
    }

    public function show($id)
    {
        $this->db->where('id', $id);
        $purchaseOrderQuery = $this->db->get('purchase_orders');
        $purchaseOrder = $purchaseOrderQuery->row_array();
    
        if ($purchaseOrder) {
            $this->db->where('order_items.purchase_order_id', $purchaseOrder['id']);
            $this->db->select('order_items.*, products.name');
            $this->db->from('order_items');
            $this->db->join('products', 'order_items.product_id = products.id');
            $orderItemsQuery = $this->db->get();
            $orderItems = $orderItemsQuery->result_array();

            $purchaseOrder['items'] = $orderItems;

            return $purchaseOrder;
        } 
        return null;
    }

    public function insert($table, $data) 
    {
        $this->db->insert($table, $data);

        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $this->db->where("id", $id);
            $this->db->update('purchase_orders', $data);

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
            $this->db->where("purchase_order_id", $id);
            $this->db->delete("order_items");
            if ($this->db->affected_rows() > 0) {
                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function finalize($id)
    {
        try {
            $this->db->where("id", $id);
            $this->db->update('purchase_orders', ['status' => 'finalizado']);

            if ($this->db->error()['code'] !== 0) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
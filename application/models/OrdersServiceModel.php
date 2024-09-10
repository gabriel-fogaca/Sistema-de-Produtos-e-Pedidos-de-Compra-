<?php

class OrdersServiceModel extends CI_Model
{
    public function getAllOrders($status = null)
    {
        $ret = array();
        if(!empty($status)){
            $this->db->where('status', $status);
        }
        $ret = $this->db->get('purchase_orders')->result_array();

        if(!empty($ret)){
            foreach ($ret as $index => $order){
                $this->db->where('order_items.purchase_order_id', $order['id']);
                $this->db->select('order_items.*, products.name');
                $this->db->from('order_items');
                $this->db->join('products', 'order_items.product_id = products.id');
                $ret[$index]['items'] = $this->db->get()->result_array();

            }
        };
        return $ret;
    }
}
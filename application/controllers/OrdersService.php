<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/Api.php');
class OrdersService extends Api
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('OrdersServiceModel');
    }

    public function index()
    {
        if (!$this->authenticate()) {
            return;
        }

        $orders = $this->OrdersServiceModel->getAllOrders();

        $this->return_json($orders);
    }

    public function getAllFinalized()
    {
        if (!$this->authenticate()) {
            return;
        }

        $order = $this->OrdersServiceModel->getAllOrders('finalizado');

        if ($order) {
            $this->return_json($order);
        } else {
            $this->return_json(['error' => 'Pedidos não encontrados'], 404);
        }
    }
}
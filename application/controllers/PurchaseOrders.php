<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PurchaseOrders extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        permission();
        $this->load->model('PurchaseOrdersModel');
        $this->load->model('SuppliersModel');
        $this->load->model('LogModel');
        $this->load->library('form_validation');
        $this->user_id = $this->session->userdata('user_id');
    }

    public function index()
    {
        $data['purchase_orders'] = $this->PurchaseOrdersModel->get_all();
        $data['title'] = 'Pedido de Compra';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav-top', $data);
        $this->load->view('pages/purchase-orders', $data);
        $this->load->view('templates/footer', $data);
    }

    public function create()
    {
        $data['suppliers'] = $this->SuppliersModel->get_all();
        $data['title'] = 'Novo Pedido de Compra';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav-top', $data);
        $this->load->view('pages/form-purchase-orders', $data);
        $this->load->view('templates/footer', $data);
    }

    public function store()
    {
        $ret = [
            'status' => 'error',
            'message' => 'Não foi possível criar o pedido de compra!',
            'class' => 'danger'
        ];

        $this->form_validation->set_rules('supplier_id', 'Fornecedor', 'required');
        $this->form_validation->set_rules('order_date', 'Data do Pedido', 'required');
        $this->form_validation->set_rules('final_total', 'Valor Total do Pedido', 'required');
        $this->form_validation->set_rules('product_id[]', 'Produto', 'required');
        $this->form_validation->set_rules('unit_price[]', 'Preço', 'required');
        $this->form_validation->set_rules('quantity[]', 'Quantidade', 'required');
        $this->form_validation->set_rules('total_price[]','Preço Total', 'required');

        if ($this->form_validation->run() == TRUE) {
            $this->db->trans_begin();

            $notes = $this->input->post('notes');
            $data = [
                'supplier_id' => $this->input->post('supplier_id'),
                'order_date' => $this->input->post('order_date'),
                'total_amount' => $this->input->post('final_total'),
                'notes' => isset($notes) ? $notes : '',
            ];
            $purchaseOrderId = $this->PurchaseOrdersModel->insert('purchase_orders', $data);

            $itemsOrder = [];
            
            if (!empty($purchaseOrderId)) {
                $productIds = $this->input->post('product_id');
                $unitPrices = $this->input->post('unit_price');
                $quantities = $this->input->post('quantity');
                $totalPrices = $this->input->post('total_price');
        
                foreach ($productIds as $index => $productId) {
                    $orderItemData = [
                        'purchase_order_id' => $purchaseOrderId,
                        'product_id' => $productId,
                        'quantity' => $quantities[$index],
                        'unit_price' => $unitPrices[$index],
                        'total_price' => $totalPrices[$index]
                    ];
    
                    $itemInsert = $this->PurchaseOrdersModel->insert('order_items', $orderItemData);
    
                    if ($itemInsert) {
                        $itemsOrder[] = $itemInsert;
                    }
                }
            }

            if($this->db->trans_status() == FALSE || (empty($purchaseOrderId) || empty($itemsOrder))) {
                $this->db->trans_rollback();
                
            }else{
                $this->db->trans_commit();
                $ret['status'] = 'success';
                $ret['message'] = 'Pedido de compra criado com sucesso!';
                $ret['class'] = 'success';
                if ($this->user_id) {
                    $this->load->model('LogModel');
                    $this->LogModel->create_log($this->user_id, 'CREATE', "Pedido de compra criado com ID: $purchaseOrderId");
                }
            }
        } 

        $this->session->set_userdata('message_form', $ret);
        redirect('PurchaseOrders/create');
    }

    public function edit($id = null)
    {
        $ret = [
            'status' => 'error',
            'message' => 'Não foi possível encontrar o pedido!',
            'class' => 'danger'
        ];

        if (empty($id)) {
            $this->session->set_userdata('message_form', $ret);
            redirect("PurchaseOrders");
        }

        $data["purchase_order"] = $this->PurchaseOrdersModel->show($id);
        $data['suppliers'] = $this->SuppliersModel->get_all();
        
        if ((empty($data["purchase_order"]) || empty($data["purchase_order"]["items"])) || empty($data["suppliers"])) {
            $this->session->set_userdata('message_form', $ret);
            redirect("PurchaseOrders");
        }

        $data["title"] = "Editar Pedido - CodeIgniter";

        if ($this->user_id) {
            $this->LogModel->create_log($this->user_id, 'VIEW', "Visualizou o pedido de compra para edição: ID: $id");
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav-top', $data);
        $this->load->view('pages/form-purchase-orders', $data);
        $this->load->view('templates/footer', $data);
    }

    public function visualize($id = null)
    {
        $ret = [
            'status' => 'error',
            'message' => 'Não foi possível encontrar o pedido!',
            'class' => 'danger'
        ];

        if (empty($id)) {
            $this->session->set_userdata('message_form', $ret);
            redirect("PurchaseOrders");
        }

        $data["purchase_order"] = $this->PurchaseOrdersModel->show($id);
        $data['suppliers'] = $this->SuppliersModel->get_all();
        $data['isVisualized'] = 1;

        if ((empty($data["purchase_order"]) || empty($data["purchase_order"]["items"])) || empty($data["suppliers"])) {
            $this->session->set_userdata('message_form', $ret);
            redirect("PurchaseOrders");
        }

        $data["title"] = "Editar Pedido - CodeIgniter";

        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav-top', $data);
        $this->load->view('pages/form-purchase-orders', $data);
        $this->load->view('templates/footer', $data);
    }

    public function update()
    {
        $ret = [
            'status' => 'error',
            'message' => 'Não foi possível criar o pedido de compra!',
            'class' => 'danger'
        ];

        $id = $this->input->post('id');
        
        $this->form_validation->set_rules('supplier_id', 'Fornecedor', 'required');
        $this->form_validation->set_rules('order_date', 'Data do Pedido', 'required');
        $this->form_validation->set_rules('final_total', 'Valor Total do Pedido', 'required');
        $this->form_validation->set_rules('product_id[]', 'Produto', 'required');
        $this->form_validation->set_rules('unit_price[]', 'Preço', 'required');
        $this->form_validation->set_rules('quantity[]', 'Quantidade', 'required');
        $this->form_validation->set_rules('total_price[]','Preço Total', 'required');

        if ($this->form_validation->run() == TRUE) {
            $this->db->trans_begin();
            $notes = $this->input->post('notes');
            $data = [
                'supplier_id' => $this->input->post('supplier_id'),
                'order_date' => $this->input->post('order_date'),
                'total_amount' => $this->input->post('final_total'),
                'notes' => isset($notes) ? $notes : '',
            ];
            $itemsOrder = [];
            
            if ($this->PurchaseOrdersModel->update($id, $data)) {
                
                $productIds = $this->input->post('product_id');
                $unitPrices = $this->input->post('unit_price');
                $quantities = $this->input->post('quantity');
                $totalPrices = $this->input->post('total_price');
                
                if($this->PurchaseOrdersModel->delete($id)){
                    foreach ($productIds as $index => $productId) {
                        $orderItemData = [
                            'purchase_order_id' => $id,
                            'product_id' => $productId,
                            'quantity' => $quantities[$index],
                            'unit_price' => $unitPrices[$index],
                            'total_price' => $totalPrices[$index]
                        ];
        
                        $itemInsert = $this->PurchaseOrdersModel->insert('order_items', $orderItemData);
                        
                        if ($itemInsert) {
                            $itemsOrder[] = $itemInsert;
                        }
                    }
                }
            }

            if($this->db->trans_status() == FALSE  || empty($itemsOrder)) {
                $this->db->trans_rollback();
                
            }else{
                $this->db->trans_commit();

                if ($this->user_id) {
                    $this->LogModel->create_log($this->user_id, 'UPDATE', "Pedido de compra atualizado (ID: $id)");
                }

                $ret['status'] = 'success';
                $ret['message'] = 'Pedido de compra criado com sucesso!';
                $ret['class'] = 'success';
            }
        } 

        $this->session->set_userdata('message_form', $ret);
        
        if (!empty($id)) {
            redirect("PurchaseOrders/edit/{$id}");
        }
        redirect("PurchaseOrders");
    }

    public function delete($id)
    {
        if ($this->PurchaseOrdersModel->delete($id)) {
            if ($this->user_id) {
                $this->LogModel->create_log($this->user_id, 'DELETE', "Pedido de compra excluído: ID: $id");
            }

            $this->session->set_userdata('message_form', [
                'status' => 'success',
                'message' => 'Pedido de compra excluído com sucesso!',
                'class' => 'success'
            ]);
        } else {
            $this->session->set_userdata('message_form', [
                'status' => 'error',
                'message' => 'Não foi possível excluir o pedido de compra!',
                'class' => 'danger'
            ]);
        }
    }

    public function finalizeOrder($id){
        $ret = [
            'status' => 'error',
            'message' => 'Não foi possível finalizar o pedido de compra!',
            'class' => 'danger'
        ];

        if($this->PurchaseOrdersModel->finalize($id)) 
        {
            $ret['status'] = 'success';
            $ret['message'] = 'Pedido de compra finalizado com sucesso!';
            $ret['class'] = 'success';

            if ($this->user_id) {
                $this->LogModel->create_log($this->user_id, 'FINALIZE', "Pedido de compra finalizado: ID: $id");
            }
        }

        echo json_encode($ret);
    }
}
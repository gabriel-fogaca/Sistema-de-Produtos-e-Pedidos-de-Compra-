<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        permission();
        $this->load->model('ProductsModel');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('LogModel');
        $this->user_id = $this->session->userdata('user_id');
    }
    public function index()
    {
        $data["products"] = $this->ProductsModel->index();
        $data["title"] = "Produtos - CodeIgniter";

        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav-top', $data);
        $this->load->view('pages/products', $data);
        $this->load->view('templates/footer', $data);
    }

    public function create()
    {
        $data["title"] = "Produtos - CodeIgniter";

        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav-top', $data);
        $this->load->view('pages/form-products', $data);
        $this->load->view('templates/footer', $data);
    }

    public function store()
    {
        $ret = [
            'status' => 'error',
            'message' => 'Não foi possível criar o produto!',
            'class' => 'danger'
        ];

        $this->form_validation->set_rules('name', 'Nome', 'required');
        $this->form_validation->set_rules('code', 'Código', 'required');
        $this->form_validation->set_rules('unit_price', 'Preço Unitário', 'required|numeric');

        if ($this->form_validation->run() == TRUE) {
            $this->db->trans_begin();
            $product = [
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'unit_price' => $this->input->post('unit_price'),
                'active' => 1
            ];

            $productId = $this->ProductsModel->store($product);

            if ($productId) {
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    log_message('error', 'Transação falhou ao inserir o produto.');
                } else {
                    $this->db->trans_commit();
                    $ret['status'] = 'success';
                    $ret['message'] = 'Produto criado com sucesso!';
                    $ret['class'] = 'success';

                    if ($this->user_id) {
                        $this->LogModel->create_log($this->user_id, 'CREATE', "Produto criado: Nome: {$product['name']} (ID: $productId)");
                    } else {
                        log_message('error', 'user_id não definido na sessão');
                    }
                }
            } else {
                $this->db->trans_rollback();
                log_message('error', 'Erro ao tentar inserir o produto no banco de dados.');
            }
        } else {
            log_message('error', 'Falha na validação do formulário.');
        }

        $this->session->set_userdata('message_form', $ret);
        redirect("products/create");
    }


    public function edit($id = null)
    {
        $ret = [
            'status' => 'error',
            'message' => 'Não foi possível encontrar o produto!',
            'class' => 'danger'
        ];

        if (empty($id)) {
            $this->session->set_userdata('message_form', $ret);
            redirect("Products");
        }

        $data["product"] = $this->ProductsModel->show($id)[0];
        if (empty($data["product"])) {
            $this->session->set_userdata('message_form', $ret);
            redirect("Products");
        }


        $data["title"] = "Editar Produtos - CodeIgniter";

        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav-top', $data);
        $this->load->view('pages/form-products', $data);
        $this->load->view('templates/footer', $data);
    }

    public function update()
    {
        $ret = [
            'status' => 'error',
            'message' => 'Não foi possível atualizar o produto!',
            'class' => 'danger'
        ];
        $this->load->library('form_validation');

        $this->form_validation->set_rules('code', 'Código', 'required');
        $this->form_validation->set_rules('name', 'Nome', 'required');
        $this->form_validation->set_rules('unit_price', 'Preço', 'required|numeric');

        $id = $this->input->post('id');

        if ($this->form_validation->run() == TRUE && !empty($id)) {

            $data = [
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'unit_price' => $this->input->post('unit_price')
            ];

            if ($this->ProductsModel->update($id, $data)) {
                $ret['status'] = 'success';
                $ret['message'] = 'Produto atualizado com sucesso!';
                $ret['class'] = 'success';
                if ($this->user_id) {
                    $this->LogModel->create_log($this->user_id, 'UPDATE', "Produto atualizado: Nome: {$data['name']} (ID: $id)");
                }
            }
        }
        $this->session->set_userdata('message_form', $ret);

        if (!empty($id)) {
            redirect("Products/edit/{$id}");
        }
        redirect("Products/index");
    }

    public function delete($id = null)
    {
        if (empty($id)) {
            $this->session->set_userdata('message_form', [
                'status' => 'error',
                'message' => 'ID do produto não fornecido!',
                'class' => 'danger'
            ]);
            redirect("Products");
        }

        if ($this->ProductsModel->delete($id)) {
            $this->session->set_userdata('message_form', [
                'status' => 'success',
                'message' => 'Produto excluído com sucesso!',
                'class' => 'success'
            ]);
            if ($this->user_id) {
                $this->LogModel->create_log($this->user_id, 'DELETE', "Produto excluído: ID: $id");
            }
        } else {
            $this->session->set_userdata('message_form', [
                'status' => 'error',
                'message' => 'Não foi possível excluir o produto!',
                'class' => 'danger'
            ]);
        }

        redirect("Products");
    }

    public function reactivate($id = null)
    {
        if (empty($id)) {
            $this->session->set_userdata('message_form', [
                'status' => 'error',
                'message' => 'ID do produto não fornecido!',
                'class' => 'danger'
            ]);
            redirect("Products");
        }

        if ($this->ProductsModel->reactivate($id)) {
            $this->session->set_userdata('message_form', [
                'status' => 'success',
                'message' => 'Produto reativado com sucesso!',
                'class' => 'success'
            ]);
            if ($this->user_id) {
                $this->LogModel->create_log($this->user_id, 'REACTIVATE', "Produto reativado: ID: $id");
            }
        } else {
            $this->session->set_userdata('message_form', [
                'status' => 'error',
                'message' => 'Não foi possível reativar o produto!',
                'class' => 'danger'
            ]);
        }

        redirect("Products");
    }

    public function getProducts(){
        $search = $this->input->post("searchTerm") ?: '';
        $products = $this->ProductsModel->show(null, $search);
        $ret = [];
        foreach ($products as $product) {
            $ret[] = [
                "id"=> $product['id'],
                "text"=> $product['name']
            ];
        }
        echo json_encode($ret);
    }

    public function getProductById(){
        $ret = [
            'status' => 'error',
            'message' => 'Não foi possível obter informações do produto!',
            'class' => 'danger'
        ];
        $id = $this->input->post('id') ?: '';
        if(empty($id)){
            echo json_encode($ret);
            return;
        }

        $products = $this->ProductsModel->show($id);
        if(empty($products)){
            echo json_encode($ret);
            return;
        }
        $ret['status'] = 'success';
        $ret['message'] = 'Produto obtido com sucesso!';
        $ret['class'] = 'success';
        $ret['data'] = $products[0];
        echo json_encode($ret);
    }
}
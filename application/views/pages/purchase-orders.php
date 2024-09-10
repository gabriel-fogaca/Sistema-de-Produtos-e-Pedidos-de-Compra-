<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <?php $this->load->view('templates/message'); ?>
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Pedidos de Compra</h1>
        <div class="btn-group mr-2">
            <a href="<?= base_url() ?>PurchaseOrders/create" class="btn btn-sm btn-outline-secondary"><i
                    class="fas fa-plus-square"></i> Pedido de Compra</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fornecedor</th>
                    <th>Data do Pedido</th>
                    <th>Valor Total</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchase_orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['supplier_name'] ?></td>
                    <td><?= $order['order_date'] ?></td>
                    <td><?= $order['total_amount'] ?></td>
                    <td>
                        <?php if ($order['status'] == 'ativo'): ?>
                        <span class="badge badge-success">Ativo</span>
                        <?php else: ?>
                        <span class="badge badge-success">Finalizado</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($order['status'] == 'ativo'): ?>
                        <a href="<?= base_url() ?>PurchaseOrders/edit/<?= $order['id'] ?>"
                            class="btn btn-sm btn-warning">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <button type="button" class="finalize_order btn btn-sm btn-success"
                            data-url="<?= base_url() ?>PurchaseOrders/finalizeOrder/<?= $order['id'] ?>">
                            <i class="fas fa-check"></i>
                        </button>
                        <?php else: ; ?>
                        <a href="<?= base_url() ?>PurchaseOrders/visualize/<?= $order['id'] ?>"
                            class="btn btn-sm btn-primary">
                            <i class="fas fa-search"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</main>

<script>
$('.finalize_order').on('click', function() {
    const urlButton = $(this).data("url");
    $.ajax({
        url: urlButton,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert('Pedido finalizado com sucesso!');
                location.reload();
            } else {
                alert('Não foi possível finalizar o pedido: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('Ocorreu um erro ao processar a requisição: ' + error);
        }
    });
});
</script>
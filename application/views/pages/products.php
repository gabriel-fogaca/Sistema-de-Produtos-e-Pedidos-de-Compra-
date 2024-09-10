<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <?php $this->load->view('templates/message'); ?>
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Produto</h1>
        <div class="btn-group mr-2">
            <a href="<?= base_url() ?>products/create" class="btn btn-sm btn-outline-secondary"><i
                    class="fas fa-plus-square"></i>
                Produto</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= $product['code'] ?></td>
                    <td><?= $product['name'] ?></td>
                    <td><?= $product['unit_price'] ?></td>
                    <td>
                        <?php if ($product['active'] == 1): ?>
                        <span class="badge badge-success">Ativo</span>
                        <?php else: ?>
                        <span class="badge badge-danger">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($product['active'] == 1): ?>
                        <a href="<?= base_url() ?>products/edit/<?= $product['id'] ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a href="javascript:goDelete(<?= $product['id'] ?>)" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                        <?php else: ?>
                        <a href="<?= base_url() ?>products/reactivate/<?= $product['id'] ?>"
                            class="btn btn-sm btn-success">
                            <i class="fas fa-check"></i> Reativar
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
function goDelete(id) {
    var myUrl = 'products/delete/' + id
    if (confirm("Deseja realmente apagar este registro?")) {
        window.location.href = myUrl;
    }
}
</script>
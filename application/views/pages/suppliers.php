<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Fornecedores</h1>
        <div class="btn-group mr-2">
            <a href="<?= base_url() ?>suppliers/create" class="btn btn-sm btn-outline-secondary"><i
                    class="fas fa-plus-square"></i> Novo Fornecedor</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CNPJ</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td><?= $supplier['id'] ?></td>
                        <td><?= $supplier['name'] ?></td>
                        <td><?= $supplier['cnpj'] ?></td>
                        <td>
                            <?= $supplier['is_active'] ? '<span class="badge badge-success">Ativo</span>' : '<span class="badge badge-danger">Inativo</span>' ?>
                        </td>
                        <td>
                            <a href="<?= base_url() ?>suppliers/edit/<?= $supplier['id'] ?>"
                                class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i></a>
                            <a href="<?= base_url() ?>suppliers/delete/<?= $supplier['id'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')"><i
                                    class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</main>
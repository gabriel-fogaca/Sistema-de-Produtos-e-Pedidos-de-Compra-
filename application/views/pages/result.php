<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $title ?></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="<?= base_url() ?>products/create" class="btn btn-sm btn-outline-secondary"><i
                        class="fas fa-plus-square"></i>
                    Produto</a>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Código</th>
                    <th>Name</th>
                    <th>Preço</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $product): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= $product['code'] ?></td>
                    <td><?= $product['name'] ?></td>
                    <td><?= $product['unit_price'] ?></td>

                    <?php endforeach ?>
                </tr>
            </tbody>
        </table>
    </div>
</main>
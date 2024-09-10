<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"></h1>
    </div>

    <div class="col-md-12">
        <?php $this->load->view('templates/message'); ?>
        <form action="<?= base_url() . (isset($product) ? "products/update/" : "products/store") ?>" method="post">
            <?php if (isset($product)): ?>
                <input type="hidden" class="form-control" name="id" id="id" placeholder=""
                    value="<?= isset($product) ? $product["id"] : "" ?>">
            <?php endif; ?>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="code">Código</label>
                    <input type="text" class="form-control" name="code" id="code" placeholder="Código"
                        value="<?= isset($product) ? $product["code"] : "" ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Name"
                        value="<?= isset($product) ? $product["name"] : "" ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="unit_price">Preço</label>
                    <input type="text" class="form-control" name="unit_price" id="unit_price" placeholder="Preço"
                        value="<?= isset($product) ? $product["unit_price"] : "" ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <button type="submit" class="btn btn-success btn-xs"><i class="fas fa-check"></i> Save</button>
                <a href="<?= base_url() ?>products" class="btn btn-danger btn-xs"><i class="fas fa-times"></i>
                    Cancel</a>
            </div>
    </div>
    </form>
    </div>
</main>
</div>
</div>
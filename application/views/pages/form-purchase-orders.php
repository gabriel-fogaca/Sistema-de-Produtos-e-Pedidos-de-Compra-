<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/nav-top'); ?>
<?php  $disableInput = isset($isVisualized) ? 'disabled' : ''?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $title ?></h1>
    </div>

    <div id="product-item-template" class="d-none">
        <div class="product-item row mb-2">
            <div class="col-md-5">
                <label for="product_id">Produto:</label>
                <select name="product_id[]" class="form-control product-select" required></select>
            </div>

            <div class="col-md-2">
                <label for="unit_price">Valor Unitário:</label>
                <input type="text" name="unit_price[]" class="form-control unit-price" value="0.00" readonly>
            </div>
            <div class="col-md-2">
                <label for="quantity">Quantidade:</label>
                <input type="number" min="1" name="quantity[]" class="form-control quantity" value="1" required>
            </div>
            <div class="col-md-2">
                <label for="total_price">Valor Total:</label>
                <input type="text" name="total_price[]" class="form-control total-price" value="0.00" readonly>
            </div>
            <div class="col-md-1 mt-4 remove-btn-container">
                <button type="button" class="btn btn-danger btn-sm remove-product">X</button>
            </div>
        </div>
    </div>

    <?php $this->load->view('templates/message'); ?>

    <form method="post"
        action="<?= isset($purchase_order) ? base_url('PurchaseOrders/update/') : base_url('PurchaseOrders/store') ?>">
        <?php if (isset($purchase_order)): ?>
        <input type="hidden" class="form-control" name="id" id="id" placeholder="" value="<?= $purchase_order['id']?>">
        <?php endif; ?>
        <div class="form-group">
            <label for="supplier_id">Fornecedor:</label>
            <select name="supplier_id" class="form-control" required <?=$disableInput?>>
                <option value="">Selecione um fornecedor</option>
                <?php foreach ($suppliers as $supplier): ?>
                <option value="<?= $supplier['id'] ?>"
                    <?= isset($purchase_order) && $purchase_order['supplier_id'] == $supplier['id'] ? 'selected' : '' ?>>
                    <?= $supplier['name'] ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="order_date">Data do Pedido:</label>
            <input type="date" name="order_date" class="form-control"
                value="<?= isset($purchase_order) ? $purchase_order['order_date'] : '' ?>" required <?=$disableInput?>>
        </div>

        <div id="product-items">
            <?php if (!empty($purchase_order['items'])) { ?>
            <?php foreach($purchase_order['items'] as $item) { ?>
            <div class="product-item row mb-2">
                <div class="col-md-5">
                    <label for="product_id">Produto:</label>
                    <select name="product_id[]" class="form-control product-select" required <?=$disableInput?>>
                        <option value="<?= $item['product_id']; ?>" selected>
                            <?= $item['name']; ?>
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="unit_price">Valor Unitário:</label>
                    <input type="text" name="unit_price[]" class="form-control unit-price"
                        value="<?= $item['unit_price']; ?>" readonly>
                </div>
                <div class="col-md-2">
                    <label for="quantity">Quantidade:</label>
                    <input type="number" min="1" name="quantity[]" class="form-control quantity"
                        value="<?= $item['quantity']; ?>" required <?=$disableInput?>>
                </div>
                <div class="col-md-2">
                    <label for="total_price">Valor Total:</label>
                    <input type="text" name="total_price[]" class="form-control total-price"
                        value="<?= $item['total_price']; ?>" readonly>
                </div>
            </div>
            <?php } ?>
            <?php } else { ?>
            <div class="product-item row mb-2">
                <div class="col-md-5">
                    <label for="product_id">Produto:</label>
                    <select name="product_id[]" class="form-control product-select" required></select>
                </div>
                <div class="col-md-2">
                    <label for="unit_price">Valor Unitário:</label>
                    <input type="text" name="unit_price[]" class="form-control unit-price" value="0.00" readonly>
                </div>
                <div class="col-md-2">
                    <label for="quantity">Quantidade:</label>
                    <input type="number" min="1" name="quantity[]" class="form-control quantity" value="1" required>
                </div>
                <div class="col-md-2">
                    <label for="total_price">Valor Total:</label>
                    <input type="text" name="total_price[]" class="form-control total-price" value="0.00" readonly>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php if (!isset($isVisualized)): ?>
        <button type="button" id="add-product" class="btn btn-primary mb-3">Adicionar Produto</button>
        <?php endif;?>
        <div class="form-group">
            <label for="final_total">Valor Final da Nota:</label>
            <input type="text" id="final-total" name="final_total" class="form-control"
                value="<?= isset($purchase_order) ? $purchase_order['total_amount'] : '0.00' ?>" readonly>
        </div>
        <div class="form-group">
            <label for="notes">Observação:</label>
            <textarea id="notes" name="notes" class="form-control"
                <?=$disableInput?>><?= isset($purchase_order) ? $purchase_order['notes'] : '' ?></textarea>
        </div>

        <?php if (!isset($isVisualized)): ?>
        <div class="form-group">
            <button type="submit"
                class="btn btn-success"><?= isset($purchase_order) ? 'Atualizar' : 'Salvar' ?></button>
        </div>
        <?php endif;?>
    </form>
</main>

<?php $this->load->view('templates/footer'); ?>
<script>
$(document).ready(function() {
    function initProductSelect(element) {
        element.select2({
            placeholder: 'Selecione um produto',
            ajax: {
                url: '<?= base_url('/Products/getProducts')?>',
                dataType: 'json',
                delay: 250,
                type: 'POST',
                data: function(params) {
                    return {
                        searchTerm: params.term
                    }
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            }
        });
    }

    $(document).on('select2:select', '.product-select', function(e) {
        const productId = e.params.data.id;
        const productItem = $(this).closest('.product-item');

        $.ajax({
            url: '<?= base_url('/Products/getProductById') ?>',
            type: 'POST',
            data: {
                id: productId
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const unitPrice = response.data.unit_price;
                    console.log(unitPrice)
                    productItem.find('.unit-price').val(unitPrice);

                    const quantity = productItem.find('.quantity').val();
                    const totalPrice = unitPrice * quantity;
                    productItem.find('.total-price').val(totalPrice.toFixed(2));
                    calculateOrderTotal()
                } else {
                    alert(response
                        .message
                    );
                }
            },
            error: function() {
                alert(
                    'Erro na requisição. Não foi possível obter as informações do produto.'
                );
            }
        });
    });

    $(document).on('input', '.quantity', function() {
        const productItem = $(this).closest('.product-item');
        const unitPrice = productItem.find('.unit-price').val();
        const quantity = $(this).val();

        if (unitPrice && quantity) {
            const totalPrice = unitPrice * quantity;
            console.log(totalPrice);
            productItem.find('.total-price').val(totalPrice);
        }

        calculateOrderTotal()
    });

    function calculateOrderTotal() {
        let orderTotal = 0;

        $('#product-items .product-item').each(function() {
            const totalPrice = parseFloat($(this).find('.total-price').val()) || 0;
            orderTotal += totalPrice;
        });

        $('#final-total').val(orderTotal.toFixed(2));
    }

    $(document).on('click', '.remove-product', function() {
        $(this).closest('.product-item').remove();
        calculateOrderTotal();
    });

    $('#add-product').on('click', function() {
        const newProductItem = $('#product-item-template .product-item').clone().removeClass('d-none');

        newProductItem.find('select').val('');
        newProductItem.find('input').val('0.00');
        newProductItem.find('.quantity').val(1);

        $('#product-items').append(newProductItem);

        initProductSelect(newProductItem.find('.product-select'));
    });

    $(document).on('click', '.remove-product', function() {
        $(this).closest('.product-item').remove();
        calculateOrderTotal();
    });

    initProductSelect($('#product-items .product-select'));
});
</script>
<section id="page-title" class="page-title page-title-layout7">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <h1 class="pagetitle__heading color-dark">Cart</h1>
            </div><!-- /.col-lg-6 -->
            <div class="col-sm-12 col-md-6 col-lg-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a>Home</a></li>
                        <li class="breadcrumb-item"><a href="/order-now">Order</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cart</li>
                    </ol>
                </nav>
            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.page-title -->

<section id="ShoppinCcart" class="shop shopping-cart pb-50 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="cart-table table-responsive">

                    <form method="get" id="code"></form>

                    <form method="post">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        <h6 class="mt-3">Product</h6>
                                    </th>
                                    <th>
                                        <h6>Price</h6>
                                    </th>
                                    <th>
                                        <h6>Quantity</h6>
                                    </th>
                                    <th>
                                        <h6>Total</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($cart['items'] as $key => $value) :
                                    $items = $cart['items'];
                                    $item_id = $items[$key]['item_id'];
                                    $item_name = $items[$key]['item_name'];
                                    $item_price = $items[$key]['item_price'];
                                    $item_total = $items[$key]['item_total'];
                                    $item_quantity = $items[$key]['item_quantity'];
                                    $item_modifier = $items[$key]['modifier'];
                                    $item_addon = $items[$key]['addon'];
                                ?>
                                    <tr class="cart__product ">
                                        <td class="cart__product-item" style="border-bottom:0;border-left:0;border-right:0;">
                                            <div class="cart__product-title">
                                                <h5 class="mb-2" style="font-size: 15px;"><?= $item_name; ?></h5>
                                                <?php if (!empty($item_modifier)) : ?>
                                                    <?php foreach ($item_modifier as $modifier) : ?>
                                                        <span class="text-muted ml-2"><?= $modifier['modifier_item']; ?></span><br>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <?php if (!empty($item_addon)) : ?>
                                                    <?php foreach ($item_addon as $addon) : ?>
                                                        <span class="text-muted ml-2">+ <?= $addon['addon_item']; ?></span><br>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                            <a href="/cart/remove/<?= $key; ?>"><i class="fa fa-close cart__product-remove"></i></a>
                                        </td>
                                        <td class="cart__product-price">$ <?= $item_price; ?><br>
                                            <?php if (!empty($item_modifier)) : ?>
                                                <?php foreach ($item_modifier as $modifier) : ?>
                                                    <span class="text-muted" style="font-weight: 400 !important;"><?= $modifier['modifier_price'] > 0 ? '+ $' . number_format($modifier['modifier_price'], "1", ".", "") : ''; ?></span><br>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <?php if (!empty($item_addon)) : ?>
                                                <?php foreach ($item_addon as $addon) : ?>
                                                    <span class="text-muted" style="font-weight: 400 !important;">+ $<?= $addon['addon_price'] > 0 ? number_format($addon['addon_price'], "1", ".", "") : ''; ?></span><br>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                        </td>
                                        <td style="display:none;" class="cart__product-full-price"><?= $item_total; ?></td>

                                        <td class="cart__product-quantity">
                                            <div class="product-quantity">
                                                <div class="quantity__input-wrap">
                                                    <i class="fa fa-minus decrease-qty"></i>
                                                    <input type="number" value="<?= $item_quantity; ?>" name="<?= $key; ?>" class="qty-input">
                                                    <i class="fa fa-plus increase-qty"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="cart__product-total">$ <?= $item_total; ?>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>


                                <tr class="cart__product-action">

                                    <td colspan="4">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-lg-12 cart__product-action-content">
                                                <div class="col-6 pl-0">
                                                    <label>Special Instructions</label>
                                                    <textarea class="form-control" cols="20" rows="2" style="width:40em;height:8em;min-height:8em" name="instruct" maxlength="250"></textarea>
                                                </div>

                                                <div>
                                                    <div class="d-flex flex-wrap mb-30">
                                                        <input type="text" class="form-control mb-10 mr-10" form="code" name="promo" placeholder="Coupon Code">
                                                        <button class="btn btn__primary mb-10" form="code">Apply
                                                            Coupon</button>
                                                    </div>
                                                    <a class="btn btn__secondary mr-10" href="/order-now">Add Items</a>
                                                    <button class="btn btn__primary float-right" type="submit">Proceed to Payment</button>
                                                </div>
                                            </div><!-- /.col-lg-2  -->
                                        </div><!-- /.row  -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div><!-- /.cart-table -->
            </div><!-- /.col-lg-12 -->
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="cart__total-amount">
                    <h6>Cart Totals :</h6>
                    <ul class="list-unstyled mb-0">
                        <li><span>Cart Subtotal :</span><span id="subtotal">$ 00.00</span></li>
                        <li><span>Promotions :</span><span id="promo">-$ 00.00</span></li>
                        <li><span>Sales Tax (11.5%) :</span><span id="tax">$ 00.00</span></li>
                        <li><span>Order Total :</span><span id="total">$ 00.00</span></li>
                    </ul>
                </div><!-- /.cart__total-amount -->
            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.shopping-cart -->


<script>
    function removeItem(element) {
        $(element).parent().parent().remove();
        updateCartTotal();
    }

    function updateCartTotal() {
        total = 0;
        $(document).find('.cart__product-total').each(function(index) {
            val = parseFloat($(this).text().substr(1));
            total += val;
        });
        subtotal = total.toFixed(2);
        promo = 0;
        <?php if (isset($_SESSION['cart']['cart_promo'])) {
            if ($_SESSION['cart']['cart_promo']['type'] == 'percent') { ?>
                promo = (subtotal * <?= $_SESSION['cart']['cart_promo']['amount'] ?>).toFixed(2);
            <?php } else { ?>
                promo = (<?= $_SESSION['cart']['cart_promo']['amount'] ?>).toFixed(2);
            <?php } ?>
            $('#promo').text('- $ ' + promo);
        <?php } ?>
        tax = ((total.toFixed(2) * 11.5) / 100).toFixed(2);
        if (promo) {
            total = (parseFloat(subtotal) + parseFloat(tax) - parseFloat(promo)).toFixed(2);
        } else {
            total = (parseFloat(subtotal) + parseFloat(tax)).toFixed(2);
        }
        $('#subtotal').text('$ ' + subtotal);
        $('#tax').text('$ ' + tax);
        $('#total').text('$ ' + total);
    }
    updateCartTotal();
</script>
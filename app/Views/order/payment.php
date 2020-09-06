<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<script src="https://www.paypal.com/sdk/js?client-id=ATE4yA5JUr6WkY0Q84F_naz0Q5JK0YOZgsO_B6cchIvoXmHNW3MKjc8TXeJ2kjVzmtdeZDJP1E391oAS&disable-funding=credit,card">
</script>

<section id="page-title" class="page-title page-title-layout7">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <h1 class="pagetitle__heading color-dark">Checkout</h1>
            </div><!-- /.col-lg-6 -->
            <div class="col-sm-12 col-md-6 col-lg-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a>Home</a></li>
                        <li class="breadcrumb-item"><a href="/order-now">Order</a></li>
                        <li class="breadcrumb-item"><a href="/cart">Cart</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Checkout</li>

                    </ol>
                </nav>
            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.page-title -->
<form method="post" id="form_checkout">
    <section class="shop shopping-cart pb-50" style="padding-top:50px !important;">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="cart__shiping">
                        <h5 class="mb-1">Choose your pickup restuarant</h5>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-10">
                                <label>Restuarant</label>
                                <select class="form-control" id="rest" name="rest_id">
                                    <option value="1">North Eve.</option>
                                    <option value="2">Evanston</option>
                                    <option value="3">West Illinois St</option>
                                    <option value="4">Van Buren</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <h5 class="mb-2">Customer Information</h5>
                            <?php if (empty($_SESSION['cus_id'])) : ?>
                                <span class="color-light">Already have an account?</span> <a href="/user/login" class="color-theme">Sign in to get an exciting discount!</a>
                            <?php endif; ?>
                            <hr>
                            <div class="row">
                                <?php if (isset($msg)) { ?>
                                    <div class=" col-md-12 offset-lg-1 alert alert-danger" role="alert" id="err">
                                        <?= $msg; ?>
                                    </div>
                                <?php } ?>
                                <input type="hidden" name="cus_id" class="form-control" placeholder="John Doe" value="<?= !empty($_SESSION['cus_id']) ? $_SESSION['cus_id'] : ''; ?>" required>
                                <div class="col-md-10">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" value="<?= !empty($_SESSION['cus_name']) ? $_SESSION['cus_name'] : '' ?>" placeholder="John Doe" required>
                                </div>
                                <div class="col-md-10">
                                    <label>Email address</label>
                                    <input type="email" name="email" class="form-control" value="<?= !empty($_SESSION['cus_email']) ? $_SESSION['cus_email'] : '' ?>" placeholder="john@example.com" required>
                                </div>
                                <div class="col-md-10">
                                    <label>Phone</label>
                                    <input type="number" name="phone" class="form-control" value="<?= !empty($_SESSION['cus_phone']) ? $_SESSION['cus_phone'] : '' ?>" placeholder="123 123 1234" required>
                                </div>
                            </div>
                        </div>
                        <h5 class="mb-2 mt-3">Payment Information</h5>
                        <hr>
                        <div class="row">
                            <?php if (isset($error)) { ?>
                                <div class="col-md-10 alert alert-danger">
                                    <?= $error; ?>
                                </div>
                            <?php } ?>
                            <input type="hidden" name="paypal" id="paypal" value="0">
                            <div class="col-md-10">
                                <label>Credit Card Number</label>
                                <input type="text" name="card_num" class="form-control" placeholder="1234 1234 1234 1234" id="card_num" maxlength="19" type="number">
                            </div>
                            <div class="col-md-5">
                                <label>Expiry Date (YYYY/MM)</label>
                                <input type="text" name="exp_date" class="form-control" placeholder="YYYY/MM" id="exp_date" maxlength="7">
                            </div>
                            <div class="col-md-5">
                                <label>CVV</label>
                                <input type="text" name="cvv" class="form-control" placeholder="123" maxlength="3" type="number">
                            </div>
                            <div class="col-10">
                                <button class="btn btn__primary btn-block" type="submit">Pay with card</button>
                            </div>
                            <div class="col-10">
                                <h5 class="mt-4 text-center col-12">OR</h5>
                                <div id="paypal-button-container"></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="cart__total-amount">
                        <h5 class="mb-2">Order Summary</h5>
                        <hr>
                        <ul class="list-unstyled mb-0 col-6">
                            <li><span>Subtotal :</span><span>$ <?= $subtotal; ?></span></li>
                            <?php if (isset($discount)) : ?>
                                <li><span>Promotions :</span><span>- $ <?= $discount;  ?></span></li>
                            <?php
                            endif; ?>
                            <li><span>Sales Tax (11.5%) :</span><span>$ <?= $tax; ?></span></li>
                            <li><span>Order Total :</span><span>$ <?= $total; ?></span></li>
                        </ul>
                    </div><!-- /.cart__total-amount -->
                </div>
            </div>
        </div>
    </section>
</form>

<script>
    paypal.Buttons({
        style: {
            layout: 'vertical',
            color: 'blue',
            label: 'pay',
        },
        createOrder: function() {
            return fetch('/checkout/pay-paypal', {
                method: 'post',
                headers: {
                    'content-type': 'application/json'
                }
            }).then(function(res) {
                return res.json();
            }).then(function(data) {
                return data.result.id;
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                $('#paypal').val(details.purchase_units[0].reference_id);
                $('form#form_checkout').submit();
            });
        }
    }).render('#paypal-button-container');


    $('#card_num').keyup(function() {
        var foo = $(this).val().split(" ").join("");
        if (foo.length > 0) {
            foo = foo.match(new RegExp('.{1,4}', 'g')).join(" ");
        }
        $(this).val(foo);
    });

    $('#card_num').keyup(function() {
        var foo = $(this).val().split(" ").join(""); // remove hyphens
        if (foo.length > 0) {
            foo = foo.match(new RegExp('.{1,4}', 'g')).join(" ");
        }
        $(this).val(foo);
    });

    $('#exp_date').keyup(function() {
        var foo = $(this).val().split("/").join(""); // remove hyphens
        if (foo.length > 0) {
            foo = foo.match(new RegExp('.{1,4}', 'g')).join("/");
        }
        $(this).val(foo);
    });
</script>
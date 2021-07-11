<link rel="stylesheet" href="/css/loader.css">
<link rel="stylesheet" href="/css/checkout.css">
<script src="/js/loader.js"></script>

<script src="https://www.paypal.com/sdk/js?client-id=AVQv9FQshWl1NnPJK_zPySNeRq_w-OGHjBfdTHNHvrQxNTbftNXiNF3FtM2_oaWi6G3ONs-WSjoX0KAm&disable-funding=credit,card">
</script>
<script src="https://js.stripe.com/v3/"></script>

<section id="page-title" class="page-title page-title-layout7">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <h1 class="pagetitle__heading color-dark">Checkout</h1>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a>Home</a></li>
                        <li class="breadcrumb-item"><a href="/order-now">Order</a></li>
                        <li class="breadcrumb-item"><a href="/cart">Cart</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Checkout</li>

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<section class="shop shopping-cart pb-50" style="padding-top:50px !important;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="cart__shiping">
                    <form method="post" id="form_checkout" class="needs-validation" novalidate>
                        <div>
                            <h5 class="mb-2">Customer Information</h5>
                            <?php if (empty($_SESSION['cus_id'])) : ?>
                                <span class="color-light">Already have an account?</span> <a href="/user/login" class="color-theme">Sign in to get exciting discounts!</a>
                            <?php endif; ?>
                            <hr>
                            <div class="row">
                                <?php if (isset($msg)) { ?>
                                    <div class=" col-md-12 offset-lg-1 alert alert-danger" role="alert" id="err">
                                        <?= $msg; ?>
                                    </div>
                                <?php } ?>
                                <input type="hidden" name="cus_id" class="form-control" placeholder="John Doe" value="<?= !empty($_SESSION['cus_id']) ? $_SESSION['cus_id'] : ''; ?>" required>
                                <div class="col-md-12">
                                    <label>Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="<?= !empty($_SESSION['cus_name']) ? $_SESSION['cus_name'] : '' ?>" required autofocus>
                                    <div class="invalid-feedback" style="margin-bottom:20px">
                                        Please enter your name.
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>Email address</label>
                                    <input type="email" name="email" id="email" class="form-control" value="<?= !empty($_SESSION['cus_email']) ? $_SESSION['cus_email'] : '' ?>" required>
                                    <div class="invalid-feedback" style="margin-bottom:20px">
                                        Please enter your email address.
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>Phone</label>
                                    <input type="text" id="phone" name="phone" pattern="\d*" class="form-control" value="<?= !empty($_SESSION['cus_phone']) ? $_SESSION['cus_phone'] : '' ?>" maxlength="10" required>
                                    <div class="invalid-feedback" style="margin-bottom:20px">
                                        Please enter your phone number.
                                    </div>
                                </div>
                            </div>
                            <h5 class="mb-2 mt-3">Payment Information</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                            <label class="form-check-label" for="invalidCheck">
                                                I have read and agreed to <a href="/terms" style="text-decoration: underline; color:#83bb43">terms</a> and <a href="/terms#refund" style="text-decoration: underline;color:#83bb43">refund policy</a>
                                            </label>
                                            <div class="invalid-feedback">
                                                You must agree before submitting.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>Name on Card</label>
                                    <input type="text" id="namecard" name="namecard" class="form-control" required>
                                    <div class="invalid-feedback" style="margin-bottom:20px">
                                        Please enter your name on card.
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>Billing Address</label>
                                    <input type="text" id="address" name="address" class="form-control" required>
                                    <div class="invalid-feedback" style="margin-bottom:20px">
                                        Please enter your address.
                                    </div>
                                </div>
                                <div class="row col">
                                    <div class="col-md-4">
                                        <label>City</label>
                                        <input type="text" id="city" name="city" class="form-control" required>
                                        <div class="invalid-feedback" style="margin-bottom:20px">
                                            Please enter your city name.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>State</label>
                                        <input type="text" id="state" name="state" class="form-control" required>
                                        <div class="invalid-feedback" style="margin-bottom:20px">
                                            Please enter your state name.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Postal Code</label>
                                        <input type="text" id="postal" name="postal" class="form-control" required>
                                        <div class="invalid-feedback" style="margin-bottom:20px">
                                            Please enter your postal code.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="paypal" id="paypal" value="0">
                            <input type="hidden" name="card" id="card" value="0">

                        </div>
                    </form>
                    <div class="row">
                        <?php if (getEnv('CI_ENVIRONMENT') === 'development') : ?>
                            <div class="col alert alert-warning">
                                <h6 class="alert-heading mb-2">This is a test site!</h6>
                                <p class="mb-1">Use following credit card details:</p>
                                <p class="mb-0">Credit Card Number: 4242 4242 4242 4242</p>
                                <p class="mb-0">Expirt Date: 11/23</p>
                                <p class="mb-0">CVV: 123</p>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-10 alert alert-danger" id="error" style="display: none">
                        </div>
                        <div class="cell paycard card-stripe" id="example-3" style="width:100%">
                            <form id="card-form">
                                <div class="row col">
                                    <div class="col-md-12">
                                        <label>Credit Card Number</label>
                                        <div id="card-number" class="form-control field"></div>
                                    </div>
                                </div>
                                <div class="row col">
                                    <div class="col-md-6">
                                        <label>Expiry Date</label>
                                        <div id="card-expiry" class="form-control field"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>CVC</label>
                                        <div id="card-cvc" class="form-control field"></div>
                                    </div>
                                </div>
                                <div class="col-8 mx-auto">
                                    <button type="submit" class="btn btn__primary btn-block"><b>Pay</b> with <b>Card</b></button>
                                </div>
                            </form>
                        </div>

                        <!-- <div class="col-8 mx-auto">
                            <h5 class="mt-4 text-center col-12">OR</h5>
                            <div id="paypal-button-container"></div>
                        </div> -->
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
                </div>
            </div>
        </div>
    </div>
</section>

<script id="checkout" secret="<?= $intent->client_secret; ?>" publish-key="<?= getEnv('CI_ENVIRONMENT') == 'development' ? getEnv('STRIPE_PUBLISH_KEY_DEV') : getEnv('STRIPE_PUBLISH_KEY'); ?>" src="/js/checkout.js"></script>
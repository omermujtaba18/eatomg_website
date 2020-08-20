<section id="page-title" class="page-title page-title-layout7">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <h1 class="pagetitle__heading color-dark">Order Confirmation</h1>
            </div><!-- /.col-lg-6 -->
            <div class="col-sm-12 col-md-6 col-lg-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a>Home</a></li>
                        <li class="breadcrumb-item"><a href="/order-now">Order</a></li>
                        <li class="breadcrumb-item"><a href="/checkout">Cart</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payment</li>

                    </ol>
                </nav>
            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.page-title -->

<section class="shop shopping-cart pb-50" style="padding-top:50px !important;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="cart__shiping">
                    <h5>Your order has been placed! </h5>
                    <hr>
                    <p>
                        Dear customer,</br>
                        Your order has been placed at Olive Mediterranean Grill, Noth Eve.<br></br>

                        Your order number is: <span class="color-theme"> <?= $order['order_num']; ?></span> <br>
                        <?php $deliver_at = new DateTime($order['placed_at']);

                        ?>
                        Kindly, pick up your order at: <span class="color-theme"> <?= ' ' . $deliver_at->format('H:i A'); ?></span></br></br>

                        Thank you, </br>
                        Olive Mediterranean Grill </p>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="cart__total-amount">
                    <h5 class="mb-2">Order Summary</h5>
                    <hr>
                    <ul class="list-unstyled mb-0 col-6">
                        <li><span>Order # :</span><span><?= $order['order_num']; ?></span></li>
                        <li><span>Pick up at :</span><span><?= $deliver_at->format('H:i A'); ?></span></li>
                        <li><span>Restaurant :</span><span>North Eve</span></li>
                        <li><span>Subtotal :</span><span>$ <?= $order['order_subtotal']; ?></span></li>
                        <li><span>Sales Tax (11.5%) :</span><span>$ <?= $order['order_tax']; ?></span></li>
                        <li><span>Order Total :</span><span>$ <?= $order['order_total']; ?></span></li>
                    </ul>
                </div><!-- /.cart__total-amount -->
            </div>
        </div>
    </div>
</section>
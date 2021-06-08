<?php
$buttonStyle = [
    'navbar__action-btn-reserve btn btn__secondary',
    'navbar__action-btn-reserve btn btn__primary'
]

?>

<section id="page-title" class="page-title page-title-layout7">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h2 class="pagetitle__desc color-dark"><?= $restaurant['rest_name']; ?></h2>
                <p><?= $restaurant['rest_address']; ?><br><?= $restaurant['rest_phone']; ?></p>

            </div>
            <div class="col-6 align-top">
                <div class="navbar-actions-wrap float-right">
                    <div class="navbar-actions d-flex align-items-center">
                        <a href="/cart" class="navbar__action-btn ">
                            <i class="icon-cart"></i><span class="cart__label"><?= $cart_total; ?></span>
                        </a>
                        <a href="/cart">
                            <h1 class="pagetitle__heading color-theme">Checkout</h1>
                        </a>

                    </div><!-- /.navbar-actions -->
                    <div class="float-right mt-1">
                        <a href="/empty_cart">
                            <span>Clear Cart</span>
                        </a>
                    </div><!-- /.navbar-actions -->
                </div><!-- /.navbar-actions-wrap -->
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <h2 class="pagetitle__desc color-dark pb-2">Change Restaurant</h2>
                <a href="https://north-avenue.ninetofab.com/order-now" class="<?= $restaurant['rest_name'] == 'OMG - North Ave' ? $buttonStyle[0] : $buttonStyle[1] ?> ml-2">North Ave</a>
                <a href="https://van-buren.ninetofab.com/order-now" class="<?= $restaurant['rest_name'] == 'OMG - Van Buren' ? $buttonStyle[0] : $buttonStyle[1] ?> ml-2">Van Buren</a>
                <a href="https://omg-catering.ninetofab.com/order-now" class="<?= $restaurant['rest_name'] == 'OMG - Catering' ? $buttonStyle[0] : $buttonStyle[1] ?> ml-2">OMG - Catering</a>
            </div>
        </div>
    </div>
</section>

<section class="shop shopping-cart pb-50" style="padding-top:50px !important;">

    <?php include("restaurant_close.php"); ?>

    <div class="container">
        <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo ($_SESSION['message']);
                unset($_SESSION['message']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="cart__shiping">
                    <h5>MENU</h5>
                    <a class="color-theme font-weight-bolder large-font" href="/order-now/build-your-own/38">Build Your Own Plate</a>
                    <hr>
                    <?php foreach ($categories as $category) : ?>
                        <?php if ($category['category_id'] != 1) : ?>
                            <a href="/order-now/<?= $category['category_slug']; ?>" class="color-theme large-font <?= $title == $category['category_name'] ? 'font-weight-bolder' : '' ?>"><?= $category['category_name']; ?></a>
                            <hr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <?= isset($content) ? $content : '<div class="col-sm-12 col-md-9 col-lg-9">
    <div class="cart__shiping">
        <span class="color-theme">Active Orders</span>
        <hr>
        You have no active orders
    </div>
</div>'; ?>


        </div>
    </div>
</section>

<script>
    setTimeout(() => {
        $(".alert").alert('close')
    }, 100000);

    <?php
    if ($close) { ?>
        $('#timeModal').modal('show');
    <?php } ?>
</script>
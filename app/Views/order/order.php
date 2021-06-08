<?php
$buttonStyle = [
    'navbar__action-btn-reserve btn btn__secondary',
    'navbar__action-btn-reserve btn btn__primary'
]

?>

<section id="page-title" class="page-title page-title-layout7">

    <div class="container d-none d-sm-block">
        <div class="row">
            <div class="col-12">
                <h1 class="pagetitle__desc color-dark pb-2" style="text-transform:none">Select a restuarant location</h1>
            </div>
        </div>

        <div class="row">
            <?php foreach ($restaurants as $restaurant) : ?>
                <div class="col-lg-2 col-md-3">
                    <a href=" <?= $restaurant['is_coming_soon'] ? '#' : $restaurant['url']; ?>" class="<?= $restaurant['rest_id'] == getEnv('REST_ID') ? $buttonStyle[0] : $buttonStyle[1] ?> mb-2"><?= substr($restaurant['rest_name'], 0, 10); ?></a>
                    <?= $restaurant['is_coming_soon'] ? 'Coming soon!' : '' ?>
                    <div class="d-none d-md-block"><?= $restaurant['rest_address']; ?><br><?= $restaurant['rest_phone']; ?></div> </br>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="row">
            <div class="col-6">
            </div>
            <div class="col-6 align-top offset-md-6">
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
    </div>

    <div class="container d-sm-none">
        <div class="row">
            <div class="col-6">
                <h1 class="pagetitle__desc color-dark pb-2" style="text-transform:none">Select a restuarant location</h1>
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
                <?php foreach ($restaurants as $restaurant) : ?>
                    <a href=" <?= $restaurant['url']; ?>" class="<?= $restaurant['rest_id'] == getEnv('REST_ID') ? $buttonStyle[0] : $buttonStyle[1] ?> ml-2"><?= substr($restaurant['rest_name'], 0, 10); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</section>

<section class="shop shopping-cart pb-50" style="padding-top:50px !important;">

    <?php include("restaurant_close.php"); ?>

    <div class="container">

        <div class="row">
            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="cart__shiping">
                    <h5>MENU</h5>
                    <hr>
                    <?php foreach ($categories as $category) : ?>
                        <a href="/order-now/<?= $category['category_slug']; ?>" class="color-theme large-font <?= $title == $category['category_name'] ? 'font-weight-bolder' : '' ?>"><?= $category['category_name']; ?></a>
                        <hr>
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
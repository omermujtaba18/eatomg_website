<section id="page-title" class="page-title page-title-layout7">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <h1 class="pagetitle__heading color-dark">My Account</h1>
            </div><!-- /.col-lg-6 -->
            <div class="col-sm-12 col-md-6 col-lg-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $title; ?></li>
                    </ol>
                </nav>
            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.page-title -->

<section class="shop shopping-cart pb-50" style="padding-top:50px !important;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="cart__shiping">
                    <a href="/user/account" class="color-theme">Account Information</a>
                    <hr>
                    <a href="/user/change-password" class="color-theme">Change Password</a>
                    <hr>
                    <a href="/user/order-history" class="color-theme">Order History</a>
                    <hr>
                    <!-- <a href="/user/promotions" class="color-theme">Promotions</a> -->
                    <!-- <hr> -->
                    <a href="/user/logout" class="color-theme">Log Out</a>
                </div><!-- /.cart__shiping -->
            </div><!-- /.col-lg-6 -->

            <?= isset($content) ? $content : '<div class="col-sm-12 col-md-9 col-lg-9">
    <div class="cart__shiping">
        <span class="color-theme">Active Orders</span>
        <hr>
        You have no active orders
    </div>
</div>'; ?>


        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.shopping-cart -->
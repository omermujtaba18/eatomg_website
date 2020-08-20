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
                        <li class="breadcrumb-item active" aria-current="page">Register</li>
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
                <div class="">
                    <img src="/images/discount/My Post-39.jpg">
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="cart__shiping">
                    <h5 class="text-center">CREATE AN ACCOUNT</h5>
                    <hr>
                    <form class="row" method="post" action="/register">
                        <?php if (isset($msg)) { ?>
                            <div class=" col-md-10 offset-lg-1 alert alert-danger" role="alert" id="err">
                                <?= $msg; ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-10 offset-lg-1">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe">
                        </div>
                        <div class="col-md-10 offset-lg-1">
                            <label>Email address</label>
                            <input type="text" name="email" class="form-control" placeholder="john@exampl.com">
                        </div>
                        <div class="col-md-10 offset-lg-1">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="*********">
                        </div>
                        <div class="col-md-10 offset-lg-1">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control" placeholder="*********">
                        </div>
                        <div class="col-md-10 offset-lg-1 pb-3">
                            <button type="submit" class="btn btn__primary btn-block">CREATE ACCOUNT</button>
                        </div>
                        <div class="col-md-10 offset-lg-1">
                            <span class="color-light">Already have an account?</span> <a href="/login" class="color-theme">Sign In!</a>
                        </div>
                    </form>
                </div><!-- /.cart__shiping -->
            </div><!-- /.col-lg-6 -->

        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.shopping-cart -->

<script>
</script>
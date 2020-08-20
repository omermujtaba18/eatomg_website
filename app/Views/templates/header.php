<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="description" content="Olive Mediterranean Grill">
    <link href="/images/favicon/OMG_Logo-Final_Icon-Black.png" rel="icon">
    <title>Olive Mediterranean Grill</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Rubik:400,500,700%7cShadows+Into+Light&display=swap">
    <link rel="stylesheet" href="/css/libraries.css" />
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <meta property="og:url" content="http://eatomg.morango.net" />
    <meta property="og:type" content="Website" />
    <meta property="og:title" content="Olive Mediterranean Grill" />
    <meta property="og:description" content="Best Mediterranean Restaurant In Town!" />
    <!-- <link rel="stylesheet" href="assets/css/animate.css" /> -->
    <link rel="stylesheet" href="/css/style.css" />
</head>

<body>
    <div class="wrapper">
        <header id="header" class="header <?= $header; ?>">
            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand" href="/">
                        <img src="/images/logo/OMG_Logo-Final_White.png" class="logo-light" alt="logo">
                        <img src="/images/logo/OMG_Logo-Final_Black.png" class="logo-dark" alt="logo">
                    </a>
                    <button class="navbar-toggler" type="button">
                        <span class="menu-lines"><span></span></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mainNavigation">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav__item with-dropdown">
                                <a href="/" class="dropdown-toggle nav__item-link <?= $title == 'index' ? 'active' : '' ?>">HOME</a>
                            </li><!-- /.nav-item -->
                            <li class="nav__item with-dropdown">
                                <a href="/founder-story" class="dropdown-toggle nav__item-link <?= $title == 'founder-story' ? 'active' : '' ?>">FOUNDERS
                                    STORY</a>
                            </li><!-- /.nav-item -->
                            <li class="nav__item with-dropdown">
                                <a href="/menu" class="dropdown-toggle nav__item-link <?= $title == 'menu' ? 'active' : '' ?>">MENU</a>
                            </li><!-- /.nav-item -->
                            <li class="nav__item with-dropdown">
                                <a href="/gallery" class="dropdown-toggle nav__item-link <?= $title == 'gallery' ? 'active' : '' ?>">GALLERY</a>
                            </li><!-- /.nav-item -->
                            <li class="nav__item with-dropdown">
                                <a href="/blog" class="dropdown-toggle nav__item-link <?= $title == 'blog' ? 'active' : '' ?>">BLOG</a>
                            </li><!-- /.nav-item -->
                            <li class="nav__item">
                                <a href="/shop" class="nav__item-link <?= $title == 'shop' ? 'active' : '' ?>">SHOP</a>
                            </li><!-- /.nav-item -->
                            <?php if (!is_null($cus_id)) : ?>
                                <li class="nav__item">
                                    <a href="/user/order-history" class="nav__item-link color-theme ">MY ACCOUNT</a>
                                </li><!-- /.nav-item -->
                            <?php endif; ?>
                        </ul><!-- /.navbar-nav -->
                    </div><!-- /.navbar-collapse -->
                    <div class="navbar-actions-wrap">
                        <div class="navbar-actions d-flex align-items-center">
                            <!-- <a href="#" class="navbar__action-btn search-popup-trigger"><i class="fa fa-search"></i></a> -->
                            <!-- <a href="reservation.html" class="navbar__action-btn navbar__action-btn-reserve btn btn__primary">Order
                Now</a> -->
                            <?php if (is_null($cus_id)) : ?>
                                <a href="/user/login" class="btn btn__white btn__bordered" style="height: 37px; min-width: 124px; line-height: 35px; border-color:#83bb43; color:#83bb43;">LOGIN</a>
                            <?php endif; ?>
                            <a href="/order-now" class="navbar__action-btn navbar__action-btn-reserve btn btn__primary">ORDER
                                NOW</a>
                            <ul class="social__icons">
                                <li><a href="https://www.facebook.com/eatomg"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="https://www.instagram.com/olivemediterranean/"><i class="fa fa-instagram"></i></a></li>
                            </ul>
                        </div><!-- /.navbar-actions -->
                    </div><!-- /.navbar-actions-wrap -->
                </div><!-- /.container -->
            </nav><!-- /.navabr -->
        </header><!-- /.Header -->
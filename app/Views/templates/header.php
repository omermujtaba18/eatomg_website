<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="description" content="<?= $restaurant['rest_description']; ?>">
    <link href="<?= $restaurant['logo']; ?>" rel="icon">
    <title><?= $restaurant['rest_name']; ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Rubik:400,500,700%7cShadows+Into+Light&display=swap">
    <link rel="stylesheet" href="/css/libraries.css" />
    <!-- <script src="/js/jquery-3.3.1.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <!-- <link rel="stylesheet" href="assets/css/animate.css" /> -->
    <link rel="stylesheet" href="/css/style.css" />
</head>

<body>
    <div class="wrapper">
        <header id="header" class="header <?= $header; ?>">
            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand" href="<?= $restaurant['url']; ?>">
                        <img src="<?= $restaurant['logo']; ?>" class="logo-dark" alt="logo">
                    </a>
                    <button class="navbar-toggler" type="button">
                        <span class="menu-lines"><span></span></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mainNavigation">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav__item with-dropdown">
                                <a href="http://eatomg.com/" class="dropdown-toggle nav__item-link <?= $title == 'index' ? 'active' : '' ?>">HOME</a>
                            </li>
                            <li class="nav__item with-dropdown">
                                <a href="http://eatomg.com/founder-story" class="dropdown-toggle nav__item-link <?= $title == 'founder-story' ? 'active' : '' ?>">FOUNDERS
                                    STORY</a>
                            </li>
                            <li class="nav__item with-dropdown">
                                <a href="http://eatomg.com/menu" class="dropdown-toggle nav__item-link <?= $title == 'menu' ? 'active' : '' ?>">MENU</a>
                            </li>
                            <li class="nav__item with-dropdown">
                                <a href="http://eatomg.com/gallery" class="dropdown-toggle nav__item-link <?= $title == 'gallery' ? 'active' : '' ?>">GALLERY</a>
                            </li>
                        </ul>
                        <div class="navbar-actions-wrap">
                            <div class="navbar-actions d-flex align-items-center">
                                <?php if (is_null($cus_id)) : ?>
                                    <a href="/login" class="navbar__action-btn navbar__action-btn-reserve btn btn__primary">LOGIN</a>
                                <?php endif; ?>
                                <?php if (!is_null($cus_id)) : ?>
                                    <a href="/user/order-history" class="navbar__action-btn navbar__action-btn-reserve btn btn__primary">MY ACCOUNT</a>
                                <?php endif; ?>
                                <a href="/" class="navbar__action-btn navbar__action-btn-reserve btn btn__primary">ORDER NOW</a>
                                <?php if ($restaurant['url_facebook']) : ?>
                                    <a href="<?= $restaurant['url_facebook'] ?>"><i class="fa fa-facebook"></i></a>
                                <?php endif; ?>
                                <?php if ($restaurant['url_instagram']) : ?>
                                    <a href="<?= $restaurant['url_instagram'] ?>"><i class="fa fa-instagram"></i></a>
                                <?php endif; ?>
                                <?php if ($restaurant['url_twitter']) : ?>
                                    <a href="<?= $restaurant['url_twitter'] ?>"><i class="fa fa-twitter"></i></a>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
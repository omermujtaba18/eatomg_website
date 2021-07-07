<div class="col-sm-12 col-md-0 col-lg-9" id="itemsection">
    <?php if (isset($_SESSION['message'])) : ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo ($_SESSION['message']);
            unset($_SESSION['message']); ?> </br></br>
            <a href="/cart" style="text-decoration:underline">Click here to view your cart</a>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <div class="cart__shiping">
        <h5><?= $category['category_name']; ?></h5>
        <hr>

        <div class="menu-layout1">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?php foreach ($items as $item) : ?>
                            <div class="menu-item">
                                <div class="row">
                                    <div class="col-sm-3 col-md-2 col-lg-2 mb-2">
                                        <img width="100" height="100" src="<?= !empty($item['item_pic']) ? $item['item_pic'] : "https://test-admin.ninetofab.com/assets/uploads/1625655828_6ace6f85df04f6cddeea.jpg"; ?>">
                                    </div>
                                    <div class="col-sm-9 col-md-10 col-lg-10">
                                        <h4 class="menu__item-title"><a href="/order-now/<?= $category['category_slug'] ?>/<?= $item['item_id'] ?>"><?= $item['item_name']; ?></a></h4>
                                        <span class="menu__item-price"><?= "$" . $item['item_price']; ?></span><br>
                                        <div class="col-11 pl-0"><span class="menu__item-desc"><?= empty($item['item_desc']) ? "-" : $item['item_desc']; ?></span></div>
                                        <a href="/order-now/<?= $category['category_slug'] ?>/<?= $item['item_id'] ?>" class="menu__item-cart navbar__action-btn">
                                            <i class="fa fa-cart-plus fa-2x" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
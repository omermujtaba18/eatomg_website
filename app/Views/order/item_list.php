<div class="col-sm-12 col-md-9 col-lg-9">
    <div class="cart__shiping">
        <h5><?= $category['category_name']; ?></h5>
        <hr>

        <div class="menu-layout1">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?php foreach ($items as $item) : ?>
                            <div class="menu-item">
                                <h4 class="menu__item-title"><?= $item['item_name']; ?></h4>
                                <span class="menu__item-price"><?= "$" . $item['item_price']; ?></span><br>
                                <span class="menu__item-desc"><?= empty($item['item_desc']) ? "-" : $item['item_desc']; ?></span>
                                <a href="/order-now/<?= $category['category_slug'] ?>/<?= $item['item_id'] ?>" class="menu__item-cart navbar__action-btn">
                                    <i class="fa fa-cart-plus fa-2x" aria-hidden="true"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12 col-md-9 col-lg-9">
    <div class="cart__shiping">
        <span class="color-theme">Order Summary</span>
        <hr>
        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Order#:</div>
            <div class="col-md-8 text-right">#<a href="../order/<?= $info['order_num']; ?>" class="color-theme"><?= $info['order_num']; ?></a></div>
        </div>
        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Status:</div>
            <div class="col-md-8 text-right"><?= $info['order_status']; ?></div>
        </div>
        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Ordered on:</div>
            <div class="col-md-8 text-right"><?= $info['order_placed_time']; ?></div>
        </div>
        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Delivered At:</div>
            <div class="col-md-8 text-right"><?= $info['order_delivery_time']; ?></div>
        </div>

        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Order Total:</div>
            <div class="col-md-8 text-right">$<?= $info['order_total']; ?></div>
        </div>
        <div class="row col-md-6 pb-3">
            <div class="col-md-4 font-weight-bold">Restaurant:</div>
            <div class="col-md-8 text-right"><?= $info['rest_name']; ?></div>
        </div>

        <span class="color-theme">Order Details</span>
        <hr>
        <?php foreach ($items as $item) : ?>
            <div class="row col-md-6">
                <div class="col-md-2 font-weight-bold"><?= $item->order_item_quantity ?> X</div>
                <div class="col-md-8 font-weight-bold"><?= $item->item_name ?></div>
                <div class="col-md-2 font-weight-bold"><?= '$' . $item->order_item_price ?></div>
            </div>
            <?php if (!empty($item->modifier)) : ?>
                <?php $modifier = explode(",", $item->modifier);
                foreach ($modifier as $m) : ?>
                    <div class="row col-md-6">
                        <div class="col-md-2 font-weight-bold"></div>
                        <div class="col-md-10"><?= !empty($m) ? ucwords(' - ' . $m) : ''; ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!empty($item->addon)) : ?>
                <?php $addon = explode(",", $item->addon);
                foreach ($addon as $a) : ?>
                    <div class="row col-md-6">
                        <div class="col-md-2 font-weight-bold"></div>
                        <div class="col-md-10"><?= !empty($a) ? ucwords(' + ' . $a) : ''; ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <br>
        <?php endforeach; ?>

        <span class="color-theme">Order Charges</span>
        <hr>
        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Subtotal</div>
            <div class="col-md-8 text-right">$<?= $info['order_subtotal']; ?></div>
        </div>
        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Promotions</div>
            <div class="col-md-8 text-right">- $ <?= empty($info['order_discount']) ? '00.00' : $info['order_discount']; ?></div>
        </div>
        <div class="row col-md-6 mb-1">
            <div class="col-md-4 font-weight-bold">Tax</div>
            <div class="col-md-8 text-right border-bottom pb-2">$<?= $info['order_tax']; ?></div>
        </div>
        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Total</div>
            <div class="col-md-8 text-right">$<?= $info['order_total']; ?></div>
            <hr>
        </div>

    </div><!-- /.cart__shiping -->
</div><!-- /.col-lg-6 -->
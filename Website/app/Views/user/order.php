<div class="col-sm-12 col-md-9 col-lg-9">
    <div class="cart__shiping">
        <span class="color-theme">Order Summary</span>
        <hr>
        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Order#:</div>
            <div class="col-md-8 text-right">#<a href="../order/<?= $order['order_num']; ?>" class="color-theme"><?= $order['order_num']; ?></a></div>
        </div>
        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Status:</div>
            <div class="col-md-8 text-right"><?= $order['order_status']; ?></div>
        </div>
        <div class="row col-md-6">
            <?php
            $placed_at = new DateTime($order['placed_at']);
            $deliver_at = new DateTime($order['deliver_at']);
            ?>
            <div class="col-md-4 font-weight-bold">Order at:</div>
            <div class="col-md-8 text-right"><?= $placed_at->format('h:i A'); ?></div>
        </div>
        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Pickup at:</div>
            <div class="col-md-8 text-right"><?= $deliver_at->format('h:i A'); ?></div>
        </div>

        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Order Total:</div>
            <div class="col-md-8 text-right">$<?= $order['order_total']; ?></div>
        </div>
        <div class="row col-md-6 pb-3">
            <div class="col-md-4 font-weight-bold">Restaurant:</div>
            <div class="col-md-8 text-right"><?= $order['rest_name']; ?></div>
        </div>

        <span class="color-theme">Order Details</span>
        <hr>
        <?php foreach ($items as $item) : ?>
            <div class="row col-md-6">
                <div class="col-md-2 font-weight-bold"><?= $item['order_item_quantity']; ?> X</div>
                <div class="col-md-8 font-weight-bold"><?= $item['item_name']; ?></div>
                <div class="col-md-2 font-weight-bold"><?= '$' . $item['item_price']; ?></div>
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
            <div class="col-md-8 text-right">$<?= $order['order_subtotal']; ?></div>
        </div>
        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Promotions</div>
            <div class="col-md-8 text-right">- $ <?= empty($order['order_discount']) ? '00.00' : $order['order_discount']; ?></div>
        </div>
        <div class="row col-md-6 mb-1">
            <div class="col-md-4 font-weight-bold">Tax (11.5%)</div>
            <div class="col-md-8 text-right border-bottom pb-2">$<?= $order['order_tax']; ?></div>
        </div>
        <div class="row col-md-6">
            <div class="col-md-4 font-weight-bold">Total</div>
            <div class="col-md-8 text-right">$<?= $order['order_total']; ?></div>
            <hr>
        </div>

    </div><!-- /.cart__shiping -->
</div><!-- /.col-lg-6 -->
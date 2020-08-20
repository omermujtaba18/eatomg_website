<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<div class="col-sm-12 col-md-9 col-lg-9">
    <div class="cart__shiping">
        <span class="color-theme">Active Orders</span>
        <hr>
        <div class="row pb-4">
            <?php if (!empty($orderOpen)) { ?>
                <?php foreach ($orderOpen as $order) : ?>
                    <div class="col-md-4"><?= $order['order_placed_time']; ?></div>
                    <div class="col-md-8">#<a href="order/<?= $order['order_num']; ?>" class="color-theme"><?= $order['order_num']; ?></a></div>
                <?php endforeach; ?>
            <?php } else { ?>
                <div class="col-md-12">No active orders found.</div>
            <?php } ?>
        </div>
        <span class="color-theme">Order History</span>
        <hr>

        <div class="row pb-4">
            <?php if (!empty($orderPast)) { ?>
                <?php foreach ($orderPast as $order) : ?>
                    <div class="col-md-4"><?= $order['order_placed_time']; ?></div>
                    <div class="col-md-8">#<a href="order/<?= $order['order_num']; ?>" class="color-theme"><?= $order['order_num']; ?></a></div>
                <?php endforeach; ?>
            <?php } else { ?>
                <div class="col-md-12">No past orders found.</div>
            <?php } ?>
        </div>
    </div><!-- /.cart__shiping -->
</div><!-- /.col-lg-6 -->
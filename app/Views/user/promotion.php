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
        <span class="color-theme">PROMOTIONS</span>
        <hr>

        <div class="col-sm-12 col-md-6 col-lg-6">
            <form class="row" method="post" action="">
                <?php if (isset($msg)) { ?>
                    <div class=" col-md-10 alert alert-danger" role="alert" id="err">
                        <?= $msg; ?>
                    </div>
                <?php } ?>
                <div class="col-md-12">
                    <label>Enter promo code</label>
                    <input type="number" name="email" class="form-control" placeholder="#EATOMGFRI">
                </div>
                <div class="col-md-6 pb-3">
                    <button type="submit" class="btn btn__primary btn-block">SUMIT</button>
                </div>
            </form>
        </div><!-- /.col-lg-6 -->
        <div class="color-theme pt-3">ACTIVE PROMOTIONS</div>
        <hr>
        <div class="col"> No active promotions </div>
    </div><!-- /.cart__shiping -->
</div><!-- /.col-lg-6 -->
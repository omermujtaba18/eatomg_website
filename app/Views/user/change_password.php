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
        <span class="color-theme">Change Password</span>
        <hr>

        <div class="col-sm-12 col-md-6 col-lg-6">
            <form class="row" method="post" action="">
                <?php if (isset($msg)) { ?>
                    <div class="col-md-10 alert alert-success" role="alert" id="err">
                        <?= $msg; ?>
                    </div>
                <?php } ?>
                <?php if (isset($err)) { ?>
                    <div class="col-md-10 alert alert-danger" role="alert" id="err">
                        <?= $err; ?>
                    </div>
                <?php } ?>
                <div class="col-md-12">
                    <label>Old Password</label>
                    <input type="text" name="oldpass" class="form-control" placeholder="********">
                </div>
                <div class="col-md-12">
                    <label>New Passoword</label>
                    <input type="text" name="newpass" class="form-control" placeholder="********">
                </div>
                <div class="col-md-12">
                    <label>Confirm Password</label>
                    <input type="text" name="newpassc" class="form-control" placeholder="********">
                </div>
                <div class="col-md-6 pt-3 pb-3">
                    <button type="submit" class="btn btn__primary btn-block">UPDATE ACCOUNT</button>
                </div>
            </form>
        </div><!-- /.col-lg-6 -->
    </div><!-- /.cart__shiping -->
</div><!-- /.col-lg-6 -->
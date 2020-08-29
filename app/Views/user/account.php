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
        <span class="color-theme">Account Information</span>
        <hr>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <form class="row" method="post" action="">
                <?php if (isset($msg)) { ?>
                    <div class=" col-md-10 alert alert-success" role="alert" id="err">
                        <?= $msg; ?>
                    </div>
                <?php } ?>

                <div class="col-md-6">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="<?= isset($customer['cus_name']) ? $customer['cus_name'] : ''; ?>" placeholder="(Example) John Doe">
                </div>
                <div class="col-md-6">
                    <label>Email address</label>
                    <input type="text" name="email" class="form-control" value="<?= isset($customer['cus_email']) ? $customer['cus_email'] : ''; ?>" placeholder="(Example) john@example.com">
                </div>
                <div class="col-md-12">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control" value="<?= isset($customer['cus_address']) ? $customer['cus_address'] : ''; ?>" placeholder="(Example) 123 Example Ave. Bradenton, FL 34208">
                </div>
                <div class="col-md-4">
                    <label>City</label>
                    <input type="text" name="city" class="form-control" value="<?= isset($customer['cus_city']) ? $customer['cus_city'] : ''; ?>" placeholder="(Example) New York">
                </div>
                <input type="hidden" id="stateValue" value="<?= isset($customer['cus_state']) ? $customer['cus_state'] : ''; ?>">
                <div class="col-md-4">
                    <label>State</label>
                    <select class="form-control" name="state" id="state">
                        <option value="AL">Alabama</option>
                        <option value="AK">Alaska</option>
                        <option value="AZ">Arizona</option>
                        <option value="AR">Arkansas</option>
                        <option value="CA">California</option>
                        <option value="CO">Colorado</option>
                        <option value="CT">Connecticut</option>
                        <option value="DE">Delaware</option>
                        <option value="DC">District Of Columbia</option>
                        <option value="FL">Florida</option>
                        <option value="GA">Georgia</option>
                        <option value="HI">Hawaii</option>
                        <option value="ID">Idaho</option>
                        <option value="IL">Illinois</option>
                        <option value="IN">Indiana</option>
                        <option value="IA">Iowa</option>
                        <option value="KS">Kansas</option>
                        <option value="KY">Kentucky</option>
                        <option value="LA">Louisiana</option>
                        <option value="ME">Maine</option>
                        <option value="MD">Maryland</option>
                        <option value="MA">Massachusetts</option>
                        <option value="MI">Michigan</option>
                        <option value="MN">Minnesota</option>
                        <option value="MS">Mississippi</option>
                        <option value="MO">Missouri</option>
                        <option value="MT">Montana</option>
                        <option value="NE">Nebraska</option>
                        <option value="NV">Nevada</option>
                        <option value="NH">New Hampshire</option>
                        <option value="NJ">New Jersey</option>
                        <option value="NM">New Mexico</option>
                        <option value="NY">New York</option>
                        <option value="NC">North Carolina</option>
                        <option value="ND">North Dakota</option>
                        <option value="OH">Ohio</option>
                        <option value="OK">Oklahoma</option>
                        <option value="OR">Oregon</option>
                        <option value="PA">Pennsylvania</option>
                        <option value="RI">Rhode Island</option>
                        <option value="SC">South Carolina</option>
                        <option value="SD">South Dakota</option>
                        <option value="TN">Tennessee</option>
                        <option value="TX">Texas</option>
                        <option value="UT">Utah</option>
                        <option value="VT">Vermont</option>
                        <option value="VA">Virginia</option>
                        <option value="WA">Washington</option>
                        <option value="WV">West Virginia</option>
                        <option value="WI">Wisconsin</option>
                        <option value="WY">Wyoming</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>ZIP Code</label>
                    <input type="text" name="zip" class="form-control" value="<?= isset($customer['cus_zip']) ? $customer['cus_zip'] : ''; ?>" placeholder="(Example) 12345">
                </div>
                <div class="col-md-6">
                    <label>Date of Birth (DD/MM/YYYY)</label>
                    <input type="text" name="dob" class="form-control mr-1" value="<?= isset($customer['cus_dob']) ? $customer['cus_dob'] : ''; ?>" placeholder="DD/MM/YYYY" maxlength="10" onkeyup="this.value=this.value.replace(/^(\d\d)(\d)$/g,'$1/$2').replace(/^(\d\d\/\d\d)(\d+)$/g,'$1/$2').replace(/[^\d\/]/g,'')">
                </div>
                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="number" name="phone" class="form-control" value="<?= isset($customer['cus_phone']) ? $customer['cus_phone'] : ''; ?>" placeholder="(Example) 1231231234">
                </div>
                <div class="col-md-6 pt-3 pb-3">
                    <button type="submit" class="btn btn__primary btn-block">UPDATE ACCOUNT</button>
                </div>
            </form>
        </div><!-- /.col-lg-6 -->
    </div><!-- /.cart__shiping -->
</div><!-- /.col-lg-6 -->

<script>
    $('#state').val($('#stateValue').val());
</script>
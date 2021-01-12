<div class="col-sm-12 col-md-9 col-lg-9" id="itemsection">
    <div class="cart__shiping">
        <h5><?= $item['item_name']; ?> - $<?= $item['item_price']; ?></h5>
        <p><?= $item['item_desc']; ?></p>
        <hr>

        <form method="post">
            <div class="row menu-item mb-0 ml-2">
                <?php if (isset($item_modifier)) :
                    $count = 1;
                    foreach ($item_modifier as $i_m) :
                        $modifier_group = $modifierGroup->where('modifier_group_id', $i_m['modifier_group_id'])->first();
                ?>
                        <div class="col-md-6 col-lg-6 col-sm-12 text-truncate mb-4">
                            <?php if (!empty($modifier_group['step'])) : ?>
                                <h4 class="menu__item-title color-theme mb-1">Step <?= $modifier_group['step']; ?></h4>
                            <?php endif; ?>
                            <p class="color-theme" style="font-size: 18px;"><?= $modifier_group['modifier_group_instruct']; ?></p>

                            <?php $modifiers = $modifier->where('modifier_group_id', $modifier_group['modifier_group_id'])->findAll();
                            foreach ($modifiers as $m) :
                                if ($modifier_group['multi_select']) {
                            ?>
                                    <div class="form-check" style="font-size: 16px;">
                                        <div class="row">
                                            <div class="col-6">
                                                <input class="form-check-input" type="checkbox" name="m[<?= $m['modifier_group_id']; ?>][<?= $m['modifier_id'] ?>]" value="<?= $m['modifier_item']; ?>">

                                                <label class="form-check-label">
                                                    <?= $m['modifier_item'] ?>
                                                    <?= $m['modifier_price'] > 0 ? '<small>(add $' . number_format($m['modifier_price'], 2, ".", "") . ")</small>" : ''; ?>
                                                </label>
                                            </div>
                                            <?php if (!empty($m['modifier_pic'])) : ?>
                                                <div class="col-6 mb-4">
                                                    <img class="align-self-right" src="<?= $m['modifier_pic']; ?>" height="50" width="50">
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                <?php } else {
                                ?>
                                    <div class="form-check" style="font-size: 16px;">
                                        <div class="row">
                                            <div class="col-6">
                                                <input class="form-check-input" type="radio" name="m[<?= $m['modifier_group_id']; ?>]" value="<?= $m['modifier_item']; ?>" required>
                                                <label class="form-check-label">
                                                    <?= $m['modifier_item'] ?>
                                                    <?= $m['modifier_price'] > 0 ? '<small>(add $' . number_format($m['modifier_price'], 2, ".", "") . ")</small>" : ''; ?>
                                                </label>
                                            </div>
                                            <?php if (!empty($m['modifier_pic'])) : ?>
                                                <div class="col-6 mb-4">
                                                    <img class="align-self-right" src="<?= $m['modifier_pic']; ?>" height="50" width="50">
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                <?php } ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                    <input type="hidden" name="total" value="<?php count($modifiers); ?>" />
                    <hr class="col-11">
                <?php endif; ?>
                <?php if (isset($item_addon)) :
                    $count = 1;
                    foreach ($item_addon as $i_a) :
                        $addon_group = $addonGroup->where('addon_group_id', $i_a['addon_group_id'])->first();
                ?>
                        <div class="col-md-6 col-lg-6 col-sm-12 text-truncate mb-4">
                            <p class="color-theme" style="font-size: 18px;"><?= $addon_group['addon_group_instruct'] . ' (Optional)'; ?></p>
                            <?php $addons = $addon->where('addon_group_id', $addon_group['addon_group_id'])->findAll();
                            foreach ($addons as $a) :
                                if ($addon_group['multi_select']) {
                            ?>
                                    <div class="form-check" style="font-size: 16px;">
                                        <div class="row">
                                            <div class="col-6">
                                                <input class="form-check-input" type="checkbox" name="a[<?= $a['addon_group_id']; ?>][<?= $a['addon_id'] ?>]" value="<?= $a['addon_item']; ?>">
                                                <label class="form-check-label">
                                                    <?= $a['addon_item']; ?>
                                                    <?= $a['addon_price'] > 0 ? '<small>(add $' . number_format($a['addon_price'], 2, ".", "") . ")</small>" : ''; ?>
                                                </label>
                                            </div>
                                            <?php if (!empty($a['addon_pic'])) : ?>
                                                <div class="col-6 mb-4">
                                                    <img class="align-self-right" src="<?= $a['addon_pic']; ?>" height="50" width="50">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php } else {
                                ?>

                                    <div class="form-check" style="font-size: 16px;">
                                        <div class="row">
                                            <div class="col-6">
                                                <input class="form-check-input" type="radio" name="a[<?= $a['addon_group_id']; ?>]" value="<?= $a['addon_item']; ?>">
                                                <label class="form-check-label">
                                                    <?= $a['addon_item']; ?>
                                                    <?= $a['addon_price'] > 0 ? '<small>(add $' . number_format($a['addon_price'], 2, ".", "") . ")</small>" : ''; ?>
                                                </label>
                                            </div>
                                            <?php if (!empty($a['addon_pic'])) : ?>
                                                <div class="col-6 mb-4">
                                                    <img class="align-self-right" src="<?= $a['addon_pic']; ?>" height="50" width="50">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>


                                <?php } ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                    <input type="hidden" name="total" value="<?php count($addons); ?>" />
                    <hr class="col-11">
                <?php endif; ?>

                <div class="col-12">
                    <label class="text-dark">Quantity</label>
                    <input type="number" name="quantity" class="col-md-2 form-control text-dark" value="1" min="1" max="100">
                </div>
                <div class="col-12 mb-4">
                    <label class="text-dark">Instructions</label><br>
                    <textarea class="col-md-6 form-control" cols="20" rows="5" name="instruction" maxlength="200"></textarea>
                </div>
            </div>
            <button class="navbar__action-btn navbar__action-btn-reserve btn btn__primary ml-0 mt-2">ADD TO CART</button>
        </form>
    </div>
</div>
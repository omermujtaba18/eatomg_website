<div class="col-sm-12 col-md-9 col-lg-9">
    <div class="cart__shiping">
        <h5><?= $item['item_name']; ?></h5>
        <hr>

        <form method="post">
            <div class="row menu-item mb-0 ml-2">
                <?php if (isset($item_modifier)) :
                    $count = 1;
                    foreach ($item_modifier as $i_m) :
                        $modifier_group = $modifierGroup->where('modifier_group_id', $i_m['modifier_group_id'])->first();
                ?>
                        <div class="col-6 text-truncate mb-4">
                            <h4 class="menu__item-title color-theme mb-1">Step <?= $count++; ?></h4>
                            <p class="color-theme" style="font-size: 18px;"><?= $modifier_group['modifier_group_instruct']; ?></p>
                            <?php $modifiers = $modifier->where('modifier_group_id', $modifier_group['modifier_group_id'])->findAll();
                            foreach ($modifiers as $m) : ?>
                                <div class="form-check" style="font-size: 16px;">
                                    <input class="form-check-input" type="radio" name="m[<?= $m['modifier_group_id']; ?>]" value="<?= $m['modifier_item']; ?>" required>
                                    <label class="form-check-label">
                                        <?= $m['modifier_item'] ?>
                                        <?= $m['modifier_price'] > 0 ? '<small>(add $' . number_format($m['modifier_price'], 2, ".", "") . ")</small>" : ''; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                    <input type="hidden" name="total" value="<?php count($modifiers); ?>" />
                <?php endif; ?>
                <?php if (isset($item_addon)) :
                    $count = 1;
                    foreach ($item_addon as $i_a) :
                        $addon_group = $addonGroup->where('addon_group_id', $i_a['addon_group_id'])->first();
                ?>
                        <div class="col-6 text-truncate mb-4">
                            <p class="color-theme" style="font-size: 18px;"><?= $addon_group['addon_group_instruct'] . ' (Optional)'; ?></p>
                            <?php $addons = $addon->where('addon_group_id', $addon_group['addon_group_id'])->findAll();
                            foreach ($addons as $a) : ?>
                                <div class="form-check" style="font-size: 16px;">
                                    <input class="form-check-input" type="radio" name="a[<?= $a['addon_group_id']; ?>]" value="<?= $a['addon_item']; ?>">
                                    <label class="form-check-label">
                                        <?= $a['addon_item']; ?>
                                        <?= $a['addon_price'] > 0 ? '<small>(add $' . number_format($a['addon_price'], 2, ".", "") . ")</small>" : ''; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                    <input type="hidden" name="total" value="<?php count($addons); ?>" />
                <?php endif; ?>
            </div>
            <button class="navbar__action-btn navbar__action-btn-reserve btn btn__primary ml-0 mt-2">ADD TO CART</button>
        </form>
    </div>
</div>
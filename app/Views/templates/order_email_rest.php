<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title><?= $rest['rest_name']; ?></title>

  <style>
    .invoice-box {
      max-width: 800px;
      margin: auto;
      padding: 30px;
      border: 1px solid #eee;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
      font-size: 16px;
      line-height: 24px;
      font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
      color: #555;
    }

    .invoice-box table {
      width: 100%;
      line-height: inherit;
      text-align: left;
    }

    .invoice-box table td {
      padding: 5px;
      vertical-align: top;
    }

    .invoice-box table tr td:nth-child(2) {
      text-align: right;
    }

    .invoice-box table tr.top table td {
      padding-bottom: 100px;
    }

    .invoice-box table tr.top table td.title {
      font-size: 44px;
      line-height: 44px;
      color: #333;
    }

    .invoice-box table tr.information table td {
      padding-bottom: 40px;
    }

    .invoice-box table tr.heading td {
      background: #eee;
      border-bottom: 1px solid #ddd;
      font-weight: bold;
    }

    .invoice-box table tr.details td {
      padding-bottom: 20px;
    }

    .invoice-box table tr.item td {
      border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
      border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(2) {
      border-top: 2px solid #eee;
      font-weight: bold;
    }

    @media only screen and (max-width: 600px) {
      .invoice-box table tr.top table td {
        width: 100%;
        display: block;
        text-align: center;
      }

      .invoice-box table tr.information table td {
        width: 100%;
        display: block;
        text-align: center;
      }
    }

    /** RTL **/
    .rtl {
      direction: rtl;
      font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial,
        sans-serif;
    }

    .rtl table {
      text-align: right;
    }

    .rtl table tr td:nth-child(2) {
      text-align: left;
    }
  </style>
</head>

<body>
  <div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
      <tr class="top">
        <td colspan="2">
          <table>
            <tr>
              <td class="title" colspan="2"><?= $rest['rest_name']; ?></td>
            </tr>
          </table>
        </td>
      </tr>

      <tr class="heading">
        <td>Customer Information</td>
        <td>Payment Method</td>
      </tr>

      <tr class="details">
        <td>
          <?= ucwords(strtolower($customer['cus_name'])); ?><br />
          <?= $customer['cus_email']; ?><br />
          <?= $customer['cus_phone']; ?><br />
        </td>
        <td><?= $order['order_payment_type']; ?><br /></td>
      </tr>

      <tr class="heading">
        <td colspan="2">Order Information</td>
      </tr>

      <tr class="item">
        <td colspan="2"><b>Order #</b>: <?= $order['order_num']; ?> </td>
      </tr>
      <?php
      $deliver_at = new DateTime($order['deliver_at']);
      $placed_at = new DateTime($order['placed_at']);
      ?>
      <tr class="item">
        <td colspan="2"><b>Placed on </b>: <?= $placed_at->format('M d, Y @ h:i:s A') ?></td>
      </tr>


      <tr class="item">
        <td colspan="2"><b>Pickup at </b>: <?= $deliver_at->format('M d, Y @ h:i:s A') ?></td>
      </tr>

      <tr class="details">
        <td colspan="2"><b>Special Instruction: </b>: <?= $order['order_instruct']; ?></td>
      </tr>

      <tr class="heading">
        <td>Item</td>

        <td>Price</td>
      </tr>

      <?php foreach ($cart['items'] as $item) : ?>
        <tr class="item">
          <td><?= $item['item_quantity']; ?> x <?= $item['item_name']; ?> <br /><br />

            <?php foreach ($item['modifier'] as $m) : ?>
              <?= $m['modifier_item'] . '<br/>'; ?>
            <?php endforeach; ?>

            <?php foreach ($item['addon'] as $a) : ?>
              <?= $a['addon_item'] . '<br/>'; ?>
            <?php endforeach; ?>
            <br />
            <?= $item['item_instruct'] == '' ? 'Instruction:' . $item['item_instruct'] : ''; ?>
          </td>
          <td>$ <?= $item['item_total']; ?></td>
        </tr>
      <?php endforeach; ?>

      <tr class="total">
        <td></td>

        <td></td>
      </tr>

      <tr class="item last">
        <td></td>

        <td>Subtotal: $<?= $order['order_subtotal']; ?></td>
      </tr>

      <tr class="item last">
        <td></td>

        <td>Promotion: -$<?= $order['order_discount']; ?></td>
      </tr>
      <tr class="item last">
        <td></td>

        <td>Tax: $<?= $order['order_tax']; ?></td>
      </tr>

      <tr class="item last">
        <td></td>

        <td>
          <b> Total: $<?= $order['order_total']; ?> </b>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>
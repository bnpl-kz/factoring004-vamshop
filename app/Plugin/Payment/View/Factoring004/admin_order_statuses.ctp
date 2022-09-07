<?php

use BnplPartners\Factoring004VamShop\Helper\Config;

require_once ROOT . '/app/View/Orders/admin_order_statuses.ctp';

$factoring004DisabledStatuses = implode(',', [
    Config::get('factoring004_paid_status'),
    Config::get('factoring004_decline_status'),
    Config::get('factoring004_delivery_status'),
    Config::get('factoring004_return_status'),
    Config::get('factoring004_cancel_status'),
]);
?>

<script>
  (function () {
    const options = document.querySelectorAll('#OrderOrderStatusId option');
    const disabledStatuses = [<?=$factoring004DisabledStatuses?>];

    for (const option of options) {
      if (disabledStatuses.indexOf(+option.value) > -1) {
        option.disabled = true;
        option.title = 'Disabled by Factoring004';
      }
    }
  })();
</script>

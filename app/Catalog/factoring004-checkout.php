<?php

use BnplPartners\Factoring004VamShop\Helper\Config;

require_once ROOT . '/app/Vendor/BnplPartnersFactoring004VamShop/vendor/autoload.php';

global $order;
$totalSum = $order['Order']['total'];
$payment = Config::getPayment();
$paymentId = $payment['PaymentMethod']['id'];
?>
<link rel="stylesheet" href="/app/webroot/css/factoring004-payment-schedule.css">
<script src="/app/webroot/js/factoring004-payment-schedule.js"></script>
<script>
    $(function () {
        $(document).on('click', 'input[name=payment_method_id]', function (e) {
            if (e.target.id === 'payment_<?php echo $paymentId; ?>') {
                $('#payment_method').after('<div id="factoring004-payment-schedule"></div>')
                const plugin = new Factoring004.PaymentSchedule({ elemId:'factoring004-payment-schedule', totalAmount: '<?php echo $totalSum; ?>' });
                plugin.render();
            } else {
                $('#factoring004-payment-schedule').remove()
            }
        })
    })
</script>;


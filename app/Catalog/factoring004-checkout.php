<?php

use BnplPartners\Factoring004VamShop\Helper\Config;

require_once ROOT . '/app/Vendor/BnplPartnersFactoring004VamShop/vendor/autoload.php';

global $order;
$totalSum = $order['Order']['total'];
$payment = Config::getPayment();
$paymentId = $payment['PaymentMethod']['id'];

?>
<script>
    $(function () {
        let paymentId = '<?= $paymentId; ?>';
        let totalSum = '<?= $totalSum; ?>'
        const maxSum = 200000;
        const minSum = 6000;

        if (totalSum < minSum) {
            $(`#payment_${paymentId}`).prop({'checked': false, 'disabled': true}).parent().parent()
                .after(`<span class="text-danger">Минимальная сумма покупки в рассрочку 6000 Тенге. Не хватает ${minSum - totalSum} тенге</span>`)
            .css('pointer-events', 'none').parent().hover(function() {
                $(this).css("border-color","transparent")
            });
        } else if (totalSum > maxSum) {
            $(`#payment_${paymentId}`).prop({'checked': false, 'disabled': true}).parent().parent()
                .after(`<span class="text-danger">Максимальная сумма покупки в рассрочку 200000 Тенге. Сумма превышает ${maxSum - totalSum} тенге</span>`)
                .css('pointer-events', 'none').parent().hover(function() {
                $(this).css("border-color","transparent")
            });
        }
    })
</script>


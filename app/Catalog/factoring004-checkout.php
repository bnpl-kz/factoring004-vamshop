<?php

use BnplPartners\Factoring004VamShop\Helper\Config;

require_once ROOT . '/app/Vendor/BnplPartnersFactoring004VamShop/vendor/autoload.php';

global $order;
$totalSum = $order['Order']['total'];
$payment = Config::getPayment();
$paymentId = $payment['PaymentMethod']['id'];
$paymentOfferFile = $payment['PaymentMethodValue'][13]['value'];

?>
<link rel="stylesheet" href="/app/webroot/css/factoring004-payment-schedule.css">
<script src="/app/webroot/js/factoring004-payment-schedule.js"></script>
<script>
    $(function () {
        let offerFile = '<?= $paymentOfferFile; ?>';
        let paymentId = '<?= $paymentId; ?>';
        let currentPaymentId = $('input[name="payment_method_id"]:checked').val()
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
        } else {
            factoring004BlockRender(currentPaymentId)

            $(document).on('change', 'input[name=payment_method_id]', function (e) {
                factoring004BlockRender(e.target.value)
            })

            $(document).on('submit', '#contentform', function (e) {
                if ($('#factoring004-offer-file').length && !$('.factoring004-offer-file-input').is(':checked')) {
                    e.preventDefault()
                    alert('Вам необходимо согласиться с условиями Рассрочка 0-0-4')
                    return;
                }
            })

            function factoring004BlockRender(id)
            {
                if (paymentId === id) {
                    if (!$('#factoring004-payment-schedule').length) {
                        $('#payment_method').after('<div id="factoring004-payment-schedule"></div>')
                    }
                    const plugin = new Factoring004.PaymentSchedule({ elemId:'factoring004-payment-schedule', totalAmount: totalSum });
                    plugin.render();
                    if (offerFile) {
                        if (!$('#factoring004-offer-file').length) {
                            $('#factoring004-payment-schedule').after('<div id="factoring004-offer-file" style="padding: 36px;"><input style="margin-right: 5px;" type="checkbox" class="factoring004-offer-file-input">Я ознакомлен и согласен с условиями <a href="/app/webroot/files/' + offerFile + '" target="_blank">Рассрочка 0-0-4</a></div>')
                        }
                    }
                } else {
                    $('#factoring004-payment-schedule').remove()
                    $('#factoring004-offer-file').remove()
                }
            }
        }

    })
</script>


<?php
/* -----------------------------------------------------------------------------------------
   VamShop - http://vamshop.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2014 VamSoft Ltd.
   License - http://vamshop.com/license.html
   ---------------------------------------------------------------------------------------*/

echo '<style>
    .custom-html {
        padding: 0 0 10px 0;
    }
    .deliveries {
        display: flex;
    }
    .title {
        width: 15%;
        color: #545452;
        text-align: right;
        padding: 0 10px 0 0;
        margin-bottom: 0;
        font-weight: normal;
        border: 0px solid red;
    }
    .delivery-input {
        margin-right: 10px !important;
    }
    .offer-file {
        display: flex;
    }
</style>';

$delivery_methods = explode(',', $data['PaymentMethodValue'][8]['value']);

$html = '<div class="custom-html">';

echo $this->Form->input('factoring004.factoring004_api_host', array(
    'label' => __d('factoring004','API Host'),
    'type' => 'text',
    'value' => $data['PaymentMethodValue'][0]['value']
));

echo $this->Form->input('factoring004.factoring004_token_bp', array(
    'label' => __d('factoring004','OAuth Token bnpl-partners'),
    'type' => 'textarea',
    'value' => $data['PaymentMethodValue'][1]['value']
));

echo $this->Form->input('factoring004.factoring004_token_as', array(
    'label' => __d('factoring004','OAuth Token AccountingService'),
    'type' => 'textarea',
    'value' => $data['PaymentMethodValue'][2]['value']
));

echo $this->Form->input('factoring004.factoring004_partner_name', array(
    'label' => __d('factoring004','Partner Name'),
    'type' => 'text',
    'value' => $data['PaymentMethodValue'][3]['value']
));

echo $this->Form->input('factoring004.factoring004_partner_code', array(
    'label' => __d('factoring004','Partner Code'),
    'type' => 'text',
    'value' => $data['PaymentMethodValue'][4]['value']
));

echo $this->Form->input('factoring004.factoring004_point_code', array(
    'label' => __d('factoring004','Point Code'),
    'type' => 'text',
    'value' => $data['PaymentMethodValue'][5]['value']
));

echo $this->Form->input('factoring004.factoring004_partner_email', array(
    'label' => __d('factoring004','Partner Email'),
    'type' => 'text',
    'value' => $data['PaymentMethodValue'][6]['value']
));

echo $this->Form->input('factoring004.factoring004_partner_website', array(
    'label' => __d('factoring004','Partner Website'),
    'type' => 'text',
    'value' => $data['PaymentMethodValue'][7]['value']
));

$html .= '<div class="deliveries">';
$html .= '<div class="title">'.__d('factoring004','Delivery methods').'</div><div class="inputs">';

foreach ($shippings as $key => $shipping) {
    $checked = in_array($key, $delivery_methods) ? "checked" : "";
    $html .= '<div class="input"><input '.$checked.' class="delivery-input" type="checkbox" id='.$key.'><label for='.$key.'>'.$shipping.'</label></div>';
}

$html .= '<input class="delivery-methods" type="hidden" name="data[factoring004][factoring004_delivery_methods]" value="'.$data['PaymentMethodValue'][8]['value'].'"></div></div>';

$html .= '<div class="offer-file"><div class="title">'.__d('factoring004','File offer').'</div><div class="buttons">';
if ($data['PaymentMethodValue'][13]['value']) {
    $html .= '<a id="factoring004-offer-file-link" target="_blank" href="'. "/app/files" . DS . $data['PaymentMethodValue'][13]['value'] .'" type="button" class="btn btn-primary">'.__d("factoring004","Show offer").'</a><button id="factoring004-agreement-file-remove" type="button" class="btn btn-danger">'.__d("factoring004","Delete offer").'</button>';
} else {
    $html .= '<button type="button" id="button-upload-file" class="btn btn-info">'.__d("factoring004","Upload offer").'</button><input id="factoring004-upload-file" style="display:none;" type="file"><p style="margin-top: 5px; font-style: italic;">'.__d("factoring004","Help offer").'</p>';
}
$html .= '<input type="hidden" id="factoring004-offer-file" name="data[factoring004][factoring004_offer_file]" value="'.$data['PaymentMethodValue'][13]['value'].'"></div></div>';

$html .= '</div>';

echo $html;

echo $this->Form->input('factoring004.factoring004_decline_status', array(
    'label' => __d('factoring004','Status declined'),
    'type' => 'select',
    'value' => $data['PaymentMethodValue'][9]['value'],
    'options' => array_merge(['-'],$statuses)
));

echo $this->Form->input('factoring004.factoring004_delivery_status', array(
    'label' => __d('factoring004','Status delivery'),
    'type' => 'select',
    'value' => $data['PaymentMethodValue'][10]['value'],
    'options' => array_merge(['-'],$statuses)
));

echo $this->Form->input('factoring004.factoring004_return_status', array(
    'label' => __d('factoring004','Status return'),
    'type' => 'select',
    'value' => $data['PaymentMethodValue'][11]['value'],
    'options' => array_merge(['-'],$statuses)
));

echo $this->Form->input('factoring004.factoring004_cancel_status', array(
    'label' => __d('factoring004','Status cancel'),
    'type' => 'select',
    'value' => $data['PaymentMethodValue'][12]['value'],
    'options' => array_merge(['-'],$statuses)
));

echo '<script>
    $(document).ready(function () {
        let ids;
        if ("'.$data['PaymentMethodValue'][8]['value'].'") 
            ids = "'.$data['PaymentMethodValue'][8]['value'].'".split(",")
        else
            ids = []
        $(document).on("change", ".delivery-input", function (e) {
             if ($(this).is(":checked")) {
                ids.push(e.target.id);
             } else {
                ids.splice($.inArray(e.target.id, ids), 1);
             }
            $(".delivery-methods").val("").val(ids)
        })
        
        $(document).on("click", "#button-upload-file", function () {
             let fileInput = $("#factoring004-upload-file")
             fileInput.click()
             fileInput.on("change",function (e) {
                 if (!e.target.files.length) return;
                 let fd = new FormData();
                 fd.append("file", e.target.files[0]);
                 $.ajax({
                    url: "/factoring004-upload-file",
                    data: fd,
                    method: "post",
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $("button[type=submit]").prop("disabled", true)
                    },
                    success: function(data) {
                       if (!data.success) return alert("Не удалось загрузить файл!")
                       $("#factoring004-offer-file").val(data.filename)
                       $("#button-upload-file").prop("disabled", true)
                    },
                    complete: function () {
                        $("button[type=submit]").prop("disabled", false)
                    }
                 })
             })
        })
        
        $(document).on("click", "#factoring004-agreement-file-remove", function () {
            let filename = $("#factoring004-offer-file").val();
                $.ajax({
                    url: "/factoring004-remove-file",
                    data: {filename: filename},
                    method: "post",
                beforeSend: function () {
                    $("button[type=submit]").prop("disabled", true)
                },
                success: function(data) {
                    if (!data.success) return alert("Не удалось удалить файл!")
                    $("#factoring004-offer-file").val("")
                    $("#factoring004-offer-file-link").removeAttr("href")
                    $("#factoring004-agreement-file-remove").prop("disabled", true)
                },
                complete: function () {
                    $("button[type=submit]").prop("disabled", false)
                }
            });
        })
        
    })
</script>';

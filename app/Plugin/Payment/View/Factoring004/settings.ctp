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
</style>';

$html = '<div class="custom-html">';

echo $this->Form->input('factoring004.factoring004_api_host', array(
    'label' => __d('factoring004','API Host'),
    'type' => 'text',
    'value' => $data['PaymentMethodValue'][0]['value']
));

echo $this->Form->input('factoring004.factoring004_login', array(
    'label' => __d('factoring004','Login'),
    'type' => 'text',
    'value' => $data['PaymentMethodValue'][1]['value']
));

echo $this->Form->input('factoring004.factoring004_password', array(
    'label' => __d('factoring004','Password'),
    'type' => 'text',
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

echo $this->Form->input('factoring004.factoring004_client_route', array(
    'label' => __d('factoring004','Client route'),
    'type' => 'select',
    'value' => $data['PaymentMethodValue'][8]['value'],
    'options' => ['redirect' => __d('factoring004','Redirect'), 'modal' => __d('factoring004','Modal')],
));

$html .= '</div>';

echo $html;


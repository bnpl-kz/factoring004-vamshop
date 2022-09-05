<?php

/**
 * @var \View $this
 * @var string $current_crumb
 */

echo $this->Admin->ShowPageHeaderStart($current_crumb);

echo $this->Form->create('false');
echo $this->Form->input('otp', [
    'label' => __d('factoring004', 'OTP'),
    'required' => true,
    'minlength' => 4,
    'maxlength' => 4,
    'pattern' => '\\d+',
    'autofocus' => true,
]);

echo '<div class="clear"></div>';

echo $this->Admin->formButton(__d('factoring004', 'Check'), 'cus-tick', [
    'class' => 'btn btn-primary',
    'type' => 'submit',
    'name' => 'check',
]);
echo $this->Form->end();

echo $this->Admin->ShowPageHeaderEnd();

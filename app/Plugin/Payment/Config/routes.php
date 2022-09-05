<?php

Router::connect('/orders/:action', ['plugin' => 'Payment', 'controller' => 'factoring004Orders']);

Router::connect(
    '/factoring004-otp/check/:type',
    ['plugin' => 'Payment', 'controller' => 'factoring004Otp', 'action' => 'index', '[method]' => 'GET'],
    ['pass' => ['type'], 'type' => 'delivery']
);

Router::connect(
    '/factoring004-otp/check/:type',
    ['plugin' => 'Payment', 'controller' => 'factoring004Otp', 'action' => 'check', '[method]' => 'POST'],
    ['pass' => ['type'], 'type' => 'delivery']
);

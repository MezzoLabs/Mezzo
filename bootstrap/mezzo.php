<?php

$app = app();

$mezzo = new MezzoLabs\Mezzo\Core\Mezzo($app);

require __DIR__ . '/../src/Core/helpers.php';

return $mezzo;


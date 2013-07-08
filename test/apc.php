<?php
echo "Let's do something with success", PHP_EOL;

apc_store('anumber', 42);

echo apc_fetch('anumber'), PHP_EOL;

echo apc_inc('anumber'), PHP_EOL;


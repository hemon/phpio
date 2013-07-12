<?php
require './config.php';

if ( PHPIO::requestId()  ) {
    PHPIO::hook();
}
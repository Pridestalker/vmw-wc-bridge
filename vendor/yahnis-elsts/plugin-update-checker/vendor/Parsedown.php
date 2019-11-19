<?php
if (!class_exists('Parsedown', false) ) {
    //Load the Parsedown version that's compatible with the current PHP version.
    if (version_compare(PHP_VERSION, '5.3.0', '>=') ) {
        include __DIR__ . '/ParsedownModern.php';
    } else {
        include __DIR__ . '/ParsedownLegacy.php';
    }
}

<?php

namespace SaaSPlatform\Core\Testing;

function assertEquals($expected, $actual, $msg = '') {
    if ($expected !== $actual) {
        throw new Exception($msg ?: "Expected " . var_export($expected, true) . " got " . var_export($actual, true));
    }
}

function assertTrue($cond, $msg = '') {
    if ($cond !== true) throw new Exception($msg ?: "Expected true");
}

function assertFalse($cond, $msg = '') {
    if ($cond !== false) throw new Exception($msg ?: "Expected false");
}

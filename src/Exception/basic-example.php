<?php
declare(strict_types=1);

require_once 'MyOwnInvalidArgumentException.php';

/**
 * This has 3 possible throwables 2 times about input and return value (method contract)
 * 1 time a custom error
 *
 * @param int $mandatoryInput
 */
function baseException(int $mandatoryInput): void {
    if ($mandatoryInput > 999 || $mandatoryInput < 0) {
        throw new InvalidArgumentException('mandatory input not between 0 and 999');
    }
}

/**
 * This has 3 possible throwables 2 times about input and return value (method contract)
 * 1 time a custom error
 *
 * @param int $mandatoryInput
 */
function customException(int $mandatoryInput): void {
    if ($mandatoryInput > 999 || $mandatoryInput < 0) {
        throw new MyOwnInvalidArgumentException('mandatory input not between 0 and 999');
    }
}

try {
    baseException(-2);
} catch (InvalidArgumentException $e) {
    print_r($e);
} catch (Throwable $t) {
    print_r($t);
}

try {
    customException(-2);
} catch (MyOwnInvalidArgumentException $e) {
    print_r($e);
} catch (Throwable $t) {
    print_r($t);
}
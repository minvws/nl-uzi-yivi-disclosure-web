<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Class TestCase.
 *
 * @package Tests
 * @author annejan@noprotocol.nl
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}

<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Indica si el seeder predeterminado debe ejecutarse antes de cada prueba.
     *
     * @var bool
     */
    protected bool $seed = false;
}

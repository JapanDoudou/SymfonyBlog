<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @name \App\Tests\AbstractTestCase
 */
abstract class AbstractTestCase extends KernelTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		self::bootKernel();
	}
}
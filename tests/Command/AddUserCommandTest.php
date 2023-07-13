<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Command;

use App\Command\AddUserCommand;
use App\Tests\Traits\UserTestTrait;
use Generator;
use const PHP_OS_FAMILY;

final class AddUserCommandTest extends AbstractCommandTest
{
	use UserTestTrait;

	public const INTERACTIVE = 'interactive';
	public const NON_INTERACTIVE = 'non_interactive';
	public function setUp(): void
	{
		parent::setUp();
		if ('Windows' === PHP_OS_FAMILY) {
			$this->markTestSkipped('`stty` is required to test this command.');
		}
	}

	/**
	 * @dataProvider isAdminDataProvider
	 *
	 * This test provides all the arguments required by the command, so the
	 * command runs non-interactively and it won't ask for any argument.
	 */
	public function testCreateUserNonInteractive(bool $isAdmin): void
	{
		$userData = $this->getBaseUserDataArray(self::NON_INTERACTIVE, $isAdmin);
		$this->executeCommand($userData);
		$this->assertUserCreated($userData);
	}

	/**
	 * @dataProvider isAdminDataProvider
	 *
	 * This test doesn't provide all the arguments required by the command, so
	 * the command runs interactively and it will ask for the value of the missing
	 * arguments.
	 * See https://symfony.com/doc/current/components/console/helpers/questionhelper.html#testing-a-command-that-expects-input
	 */
	public function testCreateUserInteractive(bool $isAdmin): void
	{
		$userData = $this->getBaseUserDataArray(self::INTERACTIVE, $isAdmin);
		$arrayValues = [];
		foreach ($userData as $data)
		{
			if (is_array($data))
			{
				foreach($data as $d)
				{
					$arrayValues[] = $d;
				}
			} else {
				$arrayValues[] = $data;
			}
		}
		$this->executeCommand(
			// these are the arguments (only 1 is passed, the rest are missing)
			['roles' => $userData['roles']],
			// these are the responses given to the questions asked by the command
			// to get the value of the missing required arguments
			$arrayValues
		);

		$this->assertUserCreated($userData);
	}

	/**
	 * This is used to execute the same test twice: first for normal users
	 * (isAdmin = false) and then for admin users (isAdmin = true).
	 */
	public function isAdminDataProvider(): Generator
	{
		yield [false];
		yield [true];
	}

	/**
	 * This helper method checks that the user was correctly created and saved
	 * in the database.
	 */


	protected function getCommandFqcn(): string
	{
		return AddUserCommand::class;
	}
}

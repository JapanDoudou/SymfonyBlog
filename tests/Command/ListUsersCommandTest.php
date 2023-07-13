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

use App\Command\ListUsersCommand;
use Generator;

final class ListUsersCommandTest extends AbstractCommandTest
{
	/**
	 * @dataProvider maxResultsProvider
	 *
	 * This test verifies the amount of data is right according to the given parameter max results.
	 */
	public function testListUsers(int $maxResults): void
	{
		$tester = $this->executeCommand(
			['--max-results' => $maxResults]
		);

		if ($maxResults === 0)
		{
			$emptyDisplayLines = 4;
		} else {
			$emptyDisplayLines = 5;
		}
		$this->assertSame($emptyDisplayLines + $maxResults, mb_substr_count($tester->getDisplay(), "\n"));
	}

	public function maxResultsProvider(): Generator
	{
		yield [0];
		yield [1];
	}

	public function testItSendsNoEmailByDefault(): void
	{
		$this->executeCommand([]);

		self::assertEmailCount(0);
	}

	public function testItQueuedAnEmailIfOptionProvided(): void
	{
		$this->executeCommand(['--send-to' => 'john.doe@symfony.com']);

		self::assertQueuedEmailCount(1);
	}

	// public function testItSendsAnEmailIfOptionProvided(): void
	// {
	//  ToDo : Execute messengers:consume to send emails
	// 	self::assertEmailCount(1);
	// }

	protected function getCommandFqcn(): string
	{
		return ListUsersCommand::class;
	}
}

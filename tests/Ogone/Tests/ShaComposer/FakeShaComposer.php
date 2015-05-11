<?php

/*
 * This file is part of the Marlon PostFinance package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PostFinance\Tests\ShaComposer;

use PostFinance\ShaComposer\ShaComposer;

/**
 * Fake SHA Composer to decouple test from actual SHA composers
 */
class FakeShaComposer implements ShaComposer
{
	const FAKESHASTRING = 'foo';

	public function compose(array $responseParameters)
	{
		return self::FAKESHASTRING;
	}
}
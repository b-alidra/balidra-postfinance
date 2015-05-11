<?php

/*
 * This file is part of the Wysow PostFinance package.
 *
 * (c) Gaultier Boniface <gboniface@wysow.fr>
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PostFinance\ShaComposer;

/**
 * SHA Composers interface
 */
interface ShaComposer
{
	/**
	 * Compose SHA string based on PostFinance response parameters
	 * @param array $parameters
	 */
	public function compose(array $parameters);
}

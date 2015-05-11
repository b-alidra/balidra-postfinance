<?php
/*
 * This file is part of the Marlon PostFinance package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PostFinance\Tests\DirectLink;

use PostFinance\Tests\ShaComposer\FakeShaComposer;
use PostFinance\DirectLink\CreateAliasRequest;
use PostFinance\DirectLink\Alias;

class CreateAliasRequestTest extends \TestCase {

    /**
     * @test
     */
    public function IsValidWhenRequiredFieldsAreFilledIn()
    {
        $aliasRequest = $this->provideMinimalAliasRequest();
        $aliasRequest->validate();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function isInvalidWithNonPostFinanceUrl()
    {
        $aliasRequest = $this->provideMinimalAliasRequest();
        $aliasRequest->setPostFinanceUri('http://example.com');
        $aliasRequest->validate();
    }

    /**
     * @test
     */
    public function isValidWithPostFinanceUrl()
    {
        $aliasRequest = $this->provideMinimalAliasRequest();
        $aliasRequest->setPostFinanceUri(CreateAliasRequest::PRODUCTION);
        $aliasRequest->validate();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function IsInvalidWhenFieldsAreMissing()
    {
        $aliasRequest = new CreateAliasRequest(new FakeShaComposer);
        $aliasRequest->validate();
    }

    /**
     * @test
     */
    public function IsValidWithAliasSet()
    {
        $alias = new Alias('customer_123');

        $aliasRequest = $this->provideMinimalAliasRequest();
        $aliasRequest->setAlias($alias);
        $aliasRequest->validate();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function IsInvalidWithTooLongAlias()
    {
        $alias = new Alias(str_repeat('repeat', 10));

        $aliasRequest = $this->provideMinimalAliasRequest();
        $aliasRequest->setAlias($alias);
        $aliasRequest->validate();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function IsInvalidWithAliasWrongCharacter()
    {
        $alias = new Alias('customer_#!§?');

        $aliasRequest = $this->provideMinimalAliasRequest();
        $aliasRequest->setAlias($alias);
        $aliasRequest->validate();
    }
}

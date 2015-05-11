<?php
/*
 * @author Nicolas Clavaud <nicolas@lrqdo.fr>
 */

namespace PostFinance\Tests\DirectLink;

use PostFinance\Tests;
use PostFinance\Tests\ShaComposer\FakeShaComposer;
use PostFinance\DirectLink\DirectLinkQueryRequest;
use PostFinance\DirectLink\Alias;

class DirectLinkQueryRequestTest extends \TestCase {

    /** @test */
    public function IsValidWhenRequiredFieldsAreFilledIn()
    {
        $directLinkQueryRequest = $this->provideMinimalDirectLinkQueryRequest();
        $directLinkQueryRequest->validate();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function IsInvalidWhenFieldsAreMissing()
    {
        $directLinkQueryRequest = new DirectLinkQueryRequest(new FakeShaComposer);
        $directLinkQueryRequest->validate();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function isInvalidWithNonPostFinanceUrl()
    {
        $directLinkQueryRequest = $this->provideMinimalDirectLinkQueryRequest();
        $directLinkQueryRequest->setPostFinanceUri('http://example.com');
        $directLinkQueryRequest->validate();
    }

    /**
     * @test
     */
    public function isValidWithPostFinanceUrl()
    {
        $directLinkQueryRequest = $this->provideMinimalDirectLinkQueryRequest();
        $directLinkQueryRequest->setPostFinanceUri(DirectLinkQueryRequest::PRODUCTION);
        $directLinkQueryRequest->validate();
    }

    /**
     * @test
     * @dataProvider provideBadParameters
     * @expectedException \InvalidArgumentException
     */
    public function BadParametersCauseExceptions($method, $value)
    {
        $directLinkQueryRequest = new DirectLinkQueryRequest(new FakeShaComposer);
        $directLinkQueryRequest->$method($value);
    }

    public function provideBadParameters()
    {
        return array(
            array('setPassword', '12'),
            array('setUserid', '12'),
        );
    }
}

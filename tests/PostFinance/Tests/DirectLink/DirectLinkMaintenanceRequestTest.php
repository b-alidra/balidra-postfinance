<?php
/*
 * @author Nicolas Clavaud <nicolas@lrqdo.fr>
 */

namespace PostFinance\Tests\DirectLink;

use PostFinance\Tests;
use PostFinance\Tests\ShaComposer\FakeShaComposer;
use PostFinance\DirectLink\DirectLinkMaintenanceRequest;
use PostFinance\DirectLink\Alias;

class DirectLinkMaintenanceRequestTest extends \TestCase {

    /** @test */
    public function IsValidWhenRequiredFieldsAreFilledIn()
    {
        $directLinkMaintenanceRequest = $this->provideMinimalDirectLinkMaintenanceRequest();
        $directLinkMaintenanceRequest->validate();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function IsInvalidWhenFieldsAreMissing()
    {
        $directLinkMaintenanceRequest = new DirectLinkMaintenanceRequest(new FakeShaComposer);
        $directLinkMaintenanceRequest->validate();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function isInvalidWithNonPostFinanceUrl()
    {
        $directLinkMaintenanceRequest = $this->provideMinimalDirectLinkMaintenanceRequest();
        $directLinkMaintenanceRequest->setPostFinanceUri('http://example.com');
        $directLinkMaintenanceRequest->validate();
    }

    /**
     * @test
     */
    public function isValidWithPostFinanceUrl()
    {
        $directLinkMaintenanceRequest = $this->provideMinimalDirectLinkMaintenanceRequest();
        $directLinkMaintenanceRequest->setPostFinanceUri(DirectLinkMaintenanceRequest::PRODUCTION);
        $directLinkMaintenanceRequest->validate();
    }

    /**
     * @test
     */
    public function isValidWithIntegerAmount()
    {
        $directLinkMaintenanceRequest = $this->provideMinimalDirectLinkMaintenanceRequest();
        $directLinkMaintenanceRequest->setAmount(232);
        $directLinkMaintenanceRequest->validate();
    }

    /**
     * @test
     * @dataProvider provideBadParameters
     * @expectedException \InvalidArgumentException
     */
    public function BadParametersCauseExceptions($method, $value)
    {
        $directLinkMaintenanceRequest = new DirectLinkMaintenanceRequest(new FakeShaComposer);
        $directLinkMaintenanceRequest->$method($value);
    }

    public function provideBadParameters()
    {
        return array(
            array('setPassword', '12'),
            array('setUserid', '12'),
            array('setOperation', 'ABC'),
            array('setAmount', '232'),
            array('setAmount', 2.32),
        );
    }
}

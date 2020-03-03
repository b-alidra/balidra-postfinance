<?php

namespace PostFinance\Tests\FormGenerator;

use PostFinance\Ecommerce\EcommercePaymentRequest;
use PostFinance\FormGenerator\UrlGenerator;
use PostFinance\PaymentRequest;
use PostFinance\Tests\ShaComposer\FakeShaComposer;
use PostFinance\Tests\TestCase;

class UrlGeneratorTest extends TestCase
{

    /** @test */
    public function GeneratesAnUrl()
    {
        $expected = EcommercePaymentRequest::TEST . '?'.
            'pspid=123456789' . '&'.
            'orderid=987654321' . '&'.
            'currency=EUR' . '&'.
            'amount=100' . '&'.
            'cn=Louis+XIV' . '&'.
            'owneraddress=1%2C+Rue+du+Palais' . '&'.
            'ownertown=Versailles' . '&'.
            'ownerzip=2300' . '&'.
            'ownercty=FR' . '&'.
            'email=louis.xiv%40versailles.fr' . '&'.
            PaymentRequest::SHASIGN_FIELD . '=' . FakeShaComposer::FAKESHASTRING;

        $paymentRequest = $this->provideMinimalPaymentRequest();

        $urlGenerator = new UrlGenerator();
        $url = $urlGenerator->render($paymentRequest);

        $this->assertEquals(strtolower($expected), strtolower($url));
    }
}

<?php

/*
 * This file is part of the Marlon PostFinance package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PostFinance\Tests\FormGenerator;

use PostFinance\FormGenerator\SimpleFormGenerator;
use PostFinance\PaymentRequest;

class SimpleFormGeneratorTest extends \TestCase
{
	/** @test */
	public function GeneratesAForm()
	{
        $expected =
            '<form method="post" action="https://e-payment.postfinance.ch/ncol/test/orderstandard.asp" id="ogone" name="ogone">
                <input type="hidden" name="PSPID" value="123456789" />
                <input type="hidden" name="ORDERID" value="987654321" />
                <input type="hidden" name="CURRENCY" value="EUR" />
                <input type="hidden" name="AMOUNT" value="100" />
                <input type="hidden" name="CN" value="Louis XIV" />
                <input type="hidden" name="OWNERADDRESS" value="1, Rue du Palais" />
                <input type="hidden" name="OWNERTOWN" value="Versailles" />
                <input type="hidden" name="OWNERZIP" value="2300" />
                <input type="hidden" name="OWNERCTY" value="FR" />
                <input type="hidden" name="EMAIL" value="louis.xiv@versailles.fr" />
                <input type="hidden" name="'.PaymentRequest::SHASIGN_FIELD.'" value="foo" />
                <input type="submit" value="Submit" id="ogonesubmit" name="ogonesubmit" />
            </form>';

        $paymentRequest = $this->provideMinimalPaymentRequest();

        $formGenerator = new SimpleFormGenerator();

        $this->assertXmlStringEqualsXmlString($expected, $formGenerator->render($paymentRequest));
        $this->assertXmlStringEqualsXmlString($expected, $formGenerator->render($paymentRequest, 'ogone', true));
	}

    /** @test */
    public function BCCheck()
    {
        $expected =
            '<form method="post" action="https://e-payment.postfinance.ch/ncol/test/orderstandard.asp" id="ogoneform" name="ogoneform">
                <input type="hidden" name="PSPID" value="123456789" />
                <input type="hidden" name="ORDERID" value="987654321" />
                <input type="hidden" name="CURRENCY" value="EUR" />
                <input type="hidden" name="AMOUNT" value="100" />
                <input type="hidden" name="CN" value="Louis XIV" />
                <input type="hidden" name="OWNERADDRESS" value="1, Rue du Palais" />
                <input type="hidden" name="OWNERTOWN" value="Versailles" />
                <input type="hidden" name="OWNERZIP" value="2300" />
                <input type="hidden" name="OWNERCTY" value="FR" />
                <input type="hidden" name="EMAIL" value="louis.xiv@versailles.fr" />
                <input type="hidden" name="'.PaymentRequest::SHASIGN_FIELD.'" value="foo" />
            </form>';

        $paymentRequest = $this->provideMinimalPaymentRequest();

        $formGenerator = new SimpleFormGenerator();
        $formGenerator->setFormName('ogoneform');
        $formGenerator->showSubmitButton(false);
        $this->assertXmlStringEqualsXmlString($expected, $formGenerator->render($paymentRequest));
        $this->assertXmlStringEqualsXmlString($expected, $formGenerator->render($paymentRequest, 'ogoneform', false));
    }
}

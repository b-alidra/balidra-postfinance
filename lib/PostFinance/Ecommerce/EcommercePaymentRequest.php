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

namespace PostFinance\Ecommerce;

use PostFinance\AbstractPaymentRequest;
use PostFinance\ShaComposer\ShaComposer;

class EcommercePaymentRequest extends AbstractPaymentRequest {

    const TEST = "https://e-payment.postfinance.ch/ncol/test/orderstandard.asp";
    const PRODUCTION = "https://e-payment.postfinance.ch/ncol/prod/orderstandard.asp";

    public function __construct(ShaComposer $shaComposer)
    {
        $this->shaComposer = $shaComposer;
        $this->postFinanceUri = self::TEST;
    }

    public function getRequiredFields()
    {
        return array(
            'pspid', 'currency', 'amount', 'orderid'
        );
    }

    public function getValidPostFinanceUris()
    {
        return array(self::TEST, self::PRODUCTION);
    }

    public function setAlias(Alias $alias)
    {
        $this->parameters['aliasOperation'] = $alias->getAliasOperation();
        $this->parameters['aliasusage'] = $alias->getAliasUsage();
        $this->parameters['alias'] = $alias->getAlias();
    }
}

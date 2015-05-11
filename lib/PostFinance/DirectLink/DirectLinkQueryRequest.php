<?php
/*
 * @author Nicolas Clavaud <nicolas@lrqdo.fr>
 */

namespace PostFinance\DirectLink;

use PostFinance\AbstractDirectLinkRequest;
use PostFinance\ShaComposer\ShaComposer;

class DirectLinkQueryRequest extends AbstractDirectLinkRequest {

    const TEST = "https://secure.ogone.com/ncol/test/querydirect.asp";
    const PRODUCTION = "https://secure.ogone.com/ncol/prod/querydirect.asp";

    public function __construct(ShaComposer $shaComposer)
    {
        $this->shaComposer = $shaComposer;
        $this->ogoneUri = self::TEST;
    }

    public function setPayIdSub($payidsub)
    {
        $this->parameters['payidsub'] = $payidsub;
    }

    public function getRequiredFields()
    {
        return array(
            'pspid',
            'userid',
            'pswd',
        );
    }

    public function getValidPostFinanceUris()
    {
        return array(self::TEST, self::PRODUCTION);
    }

    public function validate()
    {
        parent::validate();

        foreach ($this->getRequiredFieldGroups() as $group) {
            $empty = true;

            foreach ($group as $field) {
                if (!empty($this->parameters[$field])) {
                    $empty = false;
                    break;
                }
            }

            if ($empty) {
                throw new \RuntimeException(
                    sprintf("At least one of these fields should not be empty: %s", implode(', ', $group))
                );
            }
        }
    }
}

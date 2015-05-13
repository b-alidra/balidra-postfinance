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

namespace PostFinance\FormGenerator;

use PostFinance\Ecommerce\EcommercePaymentRequest;

class SimpleFormGenerator implements FormGenerator
{
    /**
     * @deprecated
     * @var null
     */
    private $formName = null;

    /**
     * @deprecated
     * @var null
     */
    private $showSubmitButton = null;

    /**
     * Submit button title
     *
     * @var string
     **/
    protected $submitButtonTitle = 'Submit';

    /**
     * Timeout in milliseconds before the form auto submission
     * Set value <= 0 to disable auto submission
     *
     * @var integer
     */
    protected $autoSubmitTimeout = 0;

    /**
     * @param EcommercePaymentRequest $ecommercePaymentRequest
     * @param string $formName
     * @param bool $showSubmitButton
     * @return string HTML
     */
    public function render(EcommercePaymentRequest $ecommercePaymentRequest, $formName = 'ogone', $showSubmitButton = true)
    {
        $formName = null !== $this->formName?$this->formName:$formName;
        $showSubmitButton = null !== $this->showSubmitButton?$this->showSubmitButton:$showSubmitButton;

        ob_start();
        include __DIR__.'/template/simpleForm.php';
        return ob_get_clean();
    }

    /**
     * @deprecated Will be removed in next major released, directly integrated in render method.
     * @param bool $bool
     */
    public function showSubmitButton($bool = true)
    {
        $this->showSubmitButton = $bool;
    }

    /**
     * @deprecated Will be removed in next major released, directly integrated in render method.
     * @param $formName
     */
    public function setFormName($formName)
    {
        $this->formName = $formName;
    }

    /**
     * Set the submit button title
     *
     * @param string $title: The submit button title
     */
    public function setSubmitButtonTitle($title)
    {
        $this->submitButtonTitle = $title;
        return $this;
    }

    /**
     * Set the auto submit timeout
     *
     * @param integer $timeout
     */
    public function setAutoSubmitTimeout($timeout)
    {
        $this->autoSubmitTimeout= $timeout;
        return $this;
    }
}

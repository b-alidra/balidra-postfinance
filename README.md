# PostFinance PHP library #

This library allows you to easily implement an [PostFinance](https://www.postfinance.ch/) integration into your project.
It provides the necessary components to complete a correct payment flow with the [PostFinance](https://www.postfinance.ch/i) platform.

Requirements:

- PHP 5.3
- network connection between your webserver and the PostFinance platform

As always, this is work in progress. Please feel free to fork this project and let them pull requests coming!

Installation:
-------------
The library is [PSR-0 compliant](http://www.php-fig.org/psr/psr-0/fr/)
 and the simplest way to install it is via composer, simply add:

    {
        "require": {
            "ba/php-ticons": "dev-master"
        }
    }

into your composer.json, then run 'composer install' or 'composer update' as required.

## Overview ##

The library complies to the [PSR-0 standard](http://www.php-fig.org/psr/psr-0/fr/),
so it can be autoloaded using PSR-0 classloaders like the one in Symfony2. See autoload.php for an example.

- Create an EcommercePaymentRequest or CreateAliasRequest, containing all the info needed by PostFinance.
- Generate  a form
- Submit it to PostFinance (client side)
- Receive a PaymentResponse back from PostFinance (as a HTTP Request)

Both EcommercePaymentRequest, CreateAliasRequest and PaymentResponse are authenticated by comparing the SHA sign, which is a hash of the parameters and a secret passphrase. You can create the hash using a ShaComposer.

The library also allows:
- Fetching order information via PostFinance API using DirectLinkQueryRequest
- Executing maintenance request via PostFinance API using DirectLinkMaintenanceRequest

# SHA Composers #

PostFinance provides 2 methods to generate a SHA sign:

- "Main parameters only"

  ![Main parameters only](http://github.com/marlon-be/marlon-ogone/raw/master/documentation/images/ogone_security_legacy.png)

  Implementation using this library is trivial:

```php
  <?php
	use PostFinance\ShaComposer\LegacyShaComposer;
	$shaComposer = new LegacyShaComposer($passphrase);
```

- "Each parameter followed by the passphrase"

  ![Each parameter followed by the passphrase](http://github.com/marlon-be/marlon-ogone/raw/master/documentation/images/ogone_security_allparameters_sha1_utf8.png)

  Implementation using this library is trivial:

```php
  	<?php
	use PostFinance\ShaComposer\AllParametersShaComposer;
	$shaComposer = new AllParametersShaComposer($passphrase);
```

This library currently supports both the legacy method "Main parameters only" and the new method "Each parameter followed by the passphrase". Either can be used with SHA-1 (default), SHA-256 or SHA-512 encryption.

# EcommercePaymentRequest and FormGenerator #

```php
	<?php
	use PostFinance\Passphrase;
	use PostFinance\Ecommerce\EcommercePaymentRequest;
    use PostFinance\ShaComposer\AllParametersShaComposer;
	use PostFinance\FormGenerator;

	$passphrase = new Passphrase('my-sha-in-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaInParameterFilter); //optional

	$ecommercePaymentRequest = new EcommercePaymentRequest($shaComposer);

	// Optionally set PostFinance uri, defaults to TEST account
	//$ecommercePaymentRequest->setPostFinanceUri(EcommercePaymentRequest::PRODUCTION);

	// Set various params:
	$ecommercePaymentRequest->setOrderid('123456');
	$ecommercePaymentRequest->setAmount(150); // in cents
	$ecommercePaymentRequest->setCurrency('EUR');
	// ...

	$ecommercePaymentRequest->validate();

	$formGenerator = new SimpleFormGenerator;
	$html = $formGenerator->render($ecommercePaymentRequest);
	// Or use your own generator. Or pass $ecommercePaymentRequest to a view
```

# CreateAliasRequest #

```php
	<?php

	use PostFinance\Passphrase;
	use PostFinance\DirectLink\CreateAliasRequest;
    use PostFinance\ShaComposer\AllParametersShaComposer;
	use PostFinance\DirectLink\Alias;

	$passphrase = new Passphrase('my-sha-in-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaInParameterFilter); //optional

	$createAliasRequest = new CreateAliasRequest($shaComposer);

	// Optionally set PostFinance uri, defaults to TEST account
	// $createAliasRequest->setPostFinanceUri(CreateAliasRequest::PRODUCTION);

	// set required params
	$createAliasRequest->setPspid('123456');
	$createAliasRequest->setAccepturl('http://example.com/accept');
	$createAliasRequest->setExceptionurl('http://example.com/exception');

	// set optional alias, if empty, PostFinance creates one
	$alias = new Alias('customer_123');
	$createAliasRequest->setAlias($alias);

	$createAliasRequest->validate();

	// Now pass $createAliasRequest to a view to build a custom form, you have access to
	// $createAliasRequest->getPostFinanceUri(), $createAliasRequest->getParameters() and $createAliasRequest->getShaSign()
	// Be sure to add the required fields CN (Card holder's name), CARDNO (Card/account number), ED (Expiry date (MMYY)), CVC (Card Verification Code)
	// and the SHASIGN
```

# DirectLinkPaymentRequest #

```php
	<?php

	use PostFinance\DirectLink\DirectLinkPaymentRequest;
	use PostFinance\Passphrase;
	use PostFinance\ShaComposer\AllParametersShaComposer;
	use PostFinance\DirectLink\Alias;

	$passphrase = new Passphrase('my-sha-in-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaInParameterFilter); //optional

	$directLinkRequest = new DirectLinkPaymentRequest($shaComposer);
	$directLinkRequest->setOrderid('order_1234');

	$alias = new Alias('customer_123');
	$directLinkRequest->setAlias($alias);
	$directLinkRequest->setPspid('123456');
	$directLinkRequest->setUserId('ogone-api-user');
	$directLinkRequest->setPassword('ogone-api-password');
	$directLinkRequest->setAmount(100);
	$directLinkRequest->setCurrency('EUR');
	$directLinkRequest->validate();

	// now create a url to be posted to PostFinance
	// you have access to $directLinkRequest->toArray(), $directLinkRequest->getPostFinanceUri() and directLinkRequest->getShaSign()
```

# DirectLinkQueryRequest #

```php
	<?php

	use PostFinance\DirectLink\DirectLinkQueryRequest;
	use PostFinance\Passphrase;
	use PostFinance\ShaComposer\AllParametersShaComposer;
	use PostFinance\DirectLink\Alias;

	$passphrase = new Passphrase('my-sha-in-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaInParameterFilter); //optional

	$directLinkRequest = new DirectLinkQueryRequest($shaComposer);
	$directLinkRequest->setPspid('123456');
	$directLinkRequest->setUserId('ogone-api-user');
	$directLinkRequest->setPassword('ogone-api-password');
	$directLinkRequest->setPayId('order_1234');
	$directLinkRequest->validate();

	// now create a url to be posted to PostFinance
	// you have access to $directLinkRequest->toArray(), $directLinkRequest->getPostFinanceUri() and directLinkRequest->getShaSign()
```

# DirectLinkMaintenanceRequest #

```php
	<?php

	use PostFinance\DirectLink\DirectLinkMaintenanceRequest;
	use PostFinance\Passphrase;
	use PostFinance\ShaComposer\AllParametersShaComposer;
	use PostFinance\DirectLink\Alias;

	$passphrase = new Passphrase('my-sha-in-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaInParameterFilter); //optional

	$directLinkRequest = new DirectLinkMaintenanceRequest($shaComposer);
	$directLinkRequest->setPspid('123456');
	$directLinkRequest->setUserId('ogone-api-user');
	$directLinkRequest->setPassword('ogone-api-password');
	$directLinkRequest->setPayId('order_1234');
	$directLinkRequest->setOperation(DirectLinkMaintenanceRequest::OPERATION_AUTHORISATION_RENEW);
	$directLinkRequest->validate();

	// now create a url to be posted to PostFinance
	// you have access to $directLinkRequest->toArray(), $directLinkRequest->getPostFinanceUri() and directLinkRequest->getShaSign()
```

# EcommercePaymentResponse #

```php
  	<?php

	use PostFinance\Ecommerce\EcommercePaymentResponse;
	use PostFinance\ShaComposer\AllParametersShaComposer;

	// ...

	$ecommercePaymentResponse = new EcommercePaymentResponse($_REQUEST);

	$passphrase = new Passphrase('my-sha-out-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaOutParameterFilter); //optional

	if($ecommercePaymentResponse->isValid($shaComposer) && $ecommercePaymentResponse->isSuccessful()) {
		// handle payment confirmation
	}
	else {
		// perform logic when the validation fails
	}
```

# CreateAliasResponse #

```php
  	<?php

	use PostFinance\DirectLink\CreateAliasResponse;
	use PostFinance\ShaComposer\AllParametersShaComposer;

	// ...

	$createAliasResponse = new CreateAliasResponse($_REQUEST);

	$passphrase = new Passphrase('my-sha-out-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaOutParameterFilter); //optional

	if($createAliasResponse->isValid($shaComposer) && $createAliasResponse->isSuccessful()) {
		// Alias creation is succesful, get the Alias object
		$alias = $createAliasResponse->getAlias();
	}
	else {
		// validation failed, retry?
	}
```

# DirectLinkPaymentResponse #

As the DirectLink payment gets an instant feedback from the server (and no async response) we don't use the SHA validation.

```php
	<?php

	use PostFinance\DirectLink\DirectLinkPaymentResponse;

	$directLinkResponse = new DirectLinkPaymentResponse('ogone-direct-link-result-as-xml');

	if($directLinkResponse->isSuccessful()) {
    	// handle payment confirmation
	} else {
    	// perform logic when the validation fails
	}
```



# Parameter filters #
ParameterFilters are used to filter the provided parameters (no shit Sherlock).
Both ShaIn- and ShaOutParameterFilters are provided and are based on the parameter lists defined in the PostFinance documentation.
Parameter filtering is optional, but we recommend using them to enforce expected parameters.

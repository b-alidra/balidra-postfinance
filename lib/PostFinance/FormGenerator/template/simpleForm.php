<form method="post" action="<?php echo $ecommercePaymentRequest->getPostFinanceUri()?>" id="<?php echo $formName?>" name="<?php echo $formName?>">
<?php foreach($ecommercePaymentRequest->toArray() as $key => $value) :?>
	<?php if(false !== $value) :?>
	<input type="hidden" name="<?php echo $key?>" value="<?php echo htmlspecialchars($value) ?>"  />
	<?php endif?>
<?php endforeach?>
<input type="hidden" name="<?php echo PostFinance\PaymentRequest::SHASIGN_FIELD ?>" value="<?php echo $ecommercePaymentRequest->getShaSign()?>" />

<?php if($showSubmitButton) :?>
<input name="Postfinancesubmit" type="submit" value="<?php echo $this->submitButtonTitle; ?>" id="Postfinancesubmit" />
<?php endif?>
</form>

<?php if($this->autoSubmitTimeout > 0) :?>
<script type="text/javascript">
    setTimeout(function() {
        document.getElementById("<?php echo $formName; ?>").submit();
    }, <?php echo $this->autoSubmitTimeout; ?>);
</script>
<?php endif; ?>


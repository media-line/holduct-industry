<?php

defined('_JEXEC') or die;
?>


<div class="uk-md-phones uk-md-phones-<?php echo $moduleclass_sfx ?> uk-margin-large-top uk-margin-large-bottom" >
	<?php if ($textBefore != ''): ?>
	<div class="uk-mb-phones-before uk-margin-bottom uk-text-contrast uk-text-right">
		<?php echo $textBefore; ?>
	</div>
	<?php endif;?>
		<?php for ($i = 0; $i < $phoneCount; $i++):?>
			<a class="uk-md-phone-row uk-text-contrast uk-h2" href="tel:<?php echo $phoneList->country_code[$i].$phoneList->operator_code[$i].$phoneList->phone_number[$i];?>">
				<span class="uk-phone-icon uk-margin-right">
					<?php if ($phoneList->phone_icon[$i] != ''){ ?>
						<img src="<?php echo $phoneList->phone_icon[$i];?>" />
					<?php } else { ?>
						<i class="icon-telephone"></i>
					<?php } ?>
				</span>
				<span class="uk-country-code"><?php echo $phoneList->country_code[$i];?></span>
				<span class="uk-operator-code"><?php echo $phoneList->operator_code[$i];?></span>
				<span class="uk-phone-number"><?php echo $phoneList->phone_number[$i];?></span>
				<span class="uk-note"><?php echo $phoneList->note[$i];?></span>
			</a>
		<?php endfor;?>
	<?php if ($textAfter != ''): ?>
	<div class="uk-mb-phones-before uk-margin-top uk-text-contrast uk-text-right">
		<?php echo $textAfter; ?>
	</div>
	<?php endif;?>
</div>

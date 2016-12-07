<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_popular
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$fieldsInColumn = 2; //количество полей в первом столбце
$rightCol = false; //флаг для колонок
?>

<div class="mod_form_gr form_back_<?php echo $module->id; ?><?php echo ' '.$params->get('form-class'); ?>">
	<?php if ($params->get('modal_on')) { ?>

	<div class="modal_form_btn<?php echo ' '.$params->get('modal_btn_class'); ?>" data-toggle="modal" data-target="#modal_form<?php echo $module->id; ?>">
		<span><?php echo $params->get('modal_btn_text'); ?></span>
	</div>

	<div class="modal fade" id="modal_form<?php echo $module->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content modal-content-form">
				<div type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</div>
				<?php } ?>

				<form id="form_back_<?php echo $module->id; ?>" name="form" method="post" enctype="multipart/form-data" class="uk-margin-large-top uk-margin-bottom-remove" data-captcha="<?php echo $module->id; ?>">
					<?php if ($params->get('head')) { ?>
						<div class="uk-form-title uk-text-center uk-h1 uk-margin-large-bottom">
							<span><?php echo $params->get('head'); ?></span>
						</div>
					<?php } ?>
					<div class="uk-grid">
						<div class="uk-width-1-2"><!-- left col open -->
							<?php for ($i=0; $i < count($fields); $i++) { ?>
								<div class="uk-field-row">
								<?php if ($fields[$i]['type'] != 'separator') { ?>
									<?php if ($fields[$i]['title']) { ?>
										<div class="text">
											<?php echo $fields[$i]['title']; ?>
											<?php if ($fields[$i]['required']) { ?>
												<span class="uk-field-required">*</span>
											<?php } ?>
										</div>
									<?php } ?>
									<div class="field">
										<div class="uk-is-empty"><?php echo JText::_
											("MOD_FORM_GR_FIELD_IS_EMPTY"); ?></div>
										<div class="uk-no-valid-caption"><?php echo JText::_
											("MOD_FORM_GR_FIELD_NO_VALID_CAPTION"); ?></div>
										<?php switch ($fields[$i]['type']) {
											case 'text': ?>
												<input class="input uk-input" type="text" name="field<?php echo $i; ?>"
													   placeholder="<?php echo $fields[$i]['placeholder']; ?><?php if ($fields[$i]['required']&&$fields[$i]['placeholder']) { echo '*'; } ?>">
												<?php break;

											case 'textarea': ?>
												<textarea class="input uk-textarea" name="field<?php echo $i; ?>" placeholder="<?php echo $fields[$i]['placeholder']; ?><?php if ($fields[$i]['required']&&$fields[$i]['placeholder']) { echo '*'; } ?>"></textarea>
												<?php break;

											case 'email': ?>
												<input class="input uk-input" type="email" name="field<?php echo $i; ?>" placeholder="<?php echo $fields[$i]['placeholder']; ?><?php if ($fields[$i]['required']&&$fields[$i]['placeholder']) { echo '*'; } ?>">
												<?php break;

											case 'phone': ?>
												<input class="input uk-input input-phone" type="text" name="field<?php echo $i; ?>" placeholder="<?php echo $fields[$i]['placeholder']; ?><?php if ($fields[$i]['required']&&$fields[$i]['placeholder']) { echo '*'; } ?>">
												<?php break;
											case 'numeric': ?>
												<input class="input uk-input" type="text" name="field<?php echo $i; ?>" placeholder="<?php echo $fields[$i]['placeholder']; ?><?php if ($fields[$i]['required']&&$fields[$i]['placeholder']) { echo '*'; } ?>">
												<?php break;
										} ?>
									</div>
								<?php } else { ?>
									<div class="text-separator ">
										<?php echo $fields[$i]['title']; ?>
										<?php if ($fields[$i]['required']) { ?>
											<span class="uk-field-required">*</span>
										<?php } ?>
									</div>
								<?php } ?>
								</div>
								<?php
									if (($i >= $fieldsInColumn) && !$rightCol) {
									$rightCol = true;
								?>
									</div><!-- left col close -->
									<div class="uk-width-1-2"><!-- right col open -->
								<?php } ?>

							<?php } ?>

							<?php if ($params->get('file_on')) { ?>
								<div class="file">
									<label><input type="file" name="file"><span><?php echo $params->get('file_text'); ?></span></label>
									<div class="js-filename"></div>
								</div>
							<?php } ?>

							<div class="uk-margin-top uk-flex uk-flex-space-between uk-flex-middle">
								<?php if ($params->get('captcha_on')) { ?>
									<div class="captcha">
										<div id="g-recaptcha<?php echo $module->id; ?>"></div>
									</div>
								<?php } ?>

								<div class="send">
									<button class="btn_form<?php echo $module->id; ?> js-form-send uk-button" type="submit" name="submit"><span><?php echo $params->get('button_text'); ?></span></button>
								</div>
							</div>
						</div><!-- right col close -->
					</div>
					<input class="input capfield" type="text" name="capfield" style="height:1px; opacity:0; visibility: hidden;">
				</form>

				<?php if ($params->get('modal_on')) { ?>
			</div>
		</div>
	</div>
<?php } ?>

	<!-- Modal -->
	<div class="uk-modal fade answer" id="answer<?php echo $module->id; ?>" tabindex="-1" role="dialog">
		<div class="uk-modal-dialog" role="document">
			<div class="modal-content">
				<div type="button" class="uk-modal-close uk-close" data-dismiss="modal" aria-hidden="true"></div>
				<div><?php echo $params->get('thanks'); ?></div>
			</div>
		</div>
	</div>

	<input type="hidden" class="js-form-gr-json" value='<?php echo $json; ?>'>
	<input type="hidden" class="siteKey" value='<?php echo $params->get('captcha_key'); ?>'>
</div>
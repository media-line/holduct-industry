<?php
/**
* @package		ZOOlanders
* @author    	JOOlanders, SL http://www.zoolanders.com
* @copyright 	Copyright (C) JOOlanders, SL
* @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// check php version
if(version_compare('5.3', (string) phpversion(), '>')) {
	$response['success'] = false;
	$response['errors'][] = 'Sorry, minimum PHP version for this view is 5.3';

	echo json_encode($response);
	jexit();
}

// check data
if (empty($languages)) {
	$response['success'] = false;
	$response['errors'][] = 'There are no languages available';

	echo json_encode($response);
	jexit();
}

// declare inner function
$echoList = function($languages, $installable = false) { ?>
<ul class="uk-list">
	<?php foreach ($languages as $l) : ?>
	<li class="uk-grid uk-grid-preserve" data-lang-code="<?php echo $l['code']; ?>" data-lang-name="<?php echo $l['name']; ?>">

		<!-- language name -->
		<span class="uk-width-4-10">
			<?php echo $l['name']; ?>
		</span>

		<!-- progress bar -->
		<span class="uk-width-3-10">
			<?php
				$completed = (int)$l['stats']['completed'];
				$state = '';
				switch ($completed) {
					case ($completed < 10):
						$state = 'uk-progress-danger';
						break;
					case ($completed < 50):
						$state = 'uk-progress-warning';
						break;
					case 100:
						$state = 'uk-progress-success';
						break;
				}
			?>
			<div class="uk-progress <?php echo $state; ?>">
				<div class="uk-progress-bar" style="width: <?php echo $l['stats']['completed']; ?>">
					<?php if ($completed >= 40) echo $l['stats']['completed']; ?>
				</div>
			</div>
		</span>

		<!-- actions -->
		<span class="uk-width-3-10 uk-text-right">

			<!-- install/update btn -->
			<?php if ($installable) : ?>
			<button type="button" class="zx-x-install-lang-packs uk-button uk-button-primary uk-button-mini" title="<?php echo JText::sprintf('PLG_ZLFRAMEWORK_EXT_LNG_INSTALL', $l['name']); ?>" data-uk-tooltip>
				<i class="uk-icon-bolt"></i>
			</button>
			<?php endif; ?>
			
			<!-- download btn -->
			<?php if ($completed != 0) : ?>
			<a href=""  class="zx-x-download-lang-packs uk-button uk-button-mini" title="<?php echo JText::sprintf('PLG_ZLFRAMEWORK_EXT_LNG_DOWNLOAD', $l['name']); ?>" data-uk-tooltip>
				<i class="uk-icon-download"></i>
			</a>
			<?php else : ?>
			<button type="button" disabled class="uk-button uk-button-mini">
				<i class="uk-icon-download"></i>
			</button>
			<?php endif; ?>

			<!-- translate btn link -->
			<?php $url = "https://www.transifex.com/organization/joolanders/dashboard/all_resources/{$l['code']}/"; ?>
			<a href="<?php echo $url ?>" target="_blank" class="uk-button uk-button-mini" title="<?php echo JText::sprintf('PLG_ZLFRAMEWORK_EXT_LNG_HELP_TRANSLATING', $l['name']); ?>" data-uk-tooltip>
				<i class="uk-icon-pencil"></i>
			</a>
		</span>
	</li>
	<?php endforeach; ?>
</ul>
<?php };

$jlanguages = JFactory::getLanguage()->getKnownLanguages(JPATH_SITE);
unset($jlanguages['en-GB']); // skip english language

$installed = $available = array();
foreach ($languages as $l) {

	// skip untranslated ones
	if((int)$l['stats']['completed'] == 0) continue;

	// organize in installed and available
	$tag = $this->app->zl->transifex->getJoomlaLanguageCode($l['code']);
	if(isset($jlanguages[$tag])) $installed[] = $l; else $available[] = $l;
}

?>

<!-- title -->
<h3 class="uk-h3"><?php echo JText::sprintf('PLG_ZLFRAMEWORK_EXT_LNG_TITLE', 'ZOOlanders'); ?></h3>

<!-- installed languages -->
<?php if(!empty($installed)) echo $echoList($installed, true); ?>

<!-- available languages -->
<div class="zx-x-other-languages<?php if(!empty($installed)) echo ' uk-hidden'; ?>">
	<?php echo $echoList($available); ?>
</div>

<div class="uk-margin-large-top uk-clearfix">
	<!-- propose new language -->
	<span class="uk-float-left">
		<?php echo JText::sprintf('PLG_ZLFRAMEWORK_EXT_LNG_PROPOSE_NEW', 'https://www.transifex.com/projects/p/zoolanders/'); ?>
	</span>

	<!-- show available languages -->
	<?php if(!empty($installed)) : ?>
	<a href="javascript:void(0);" class="uk-float-right zx-x-show-other-languages" data-uk-toggle="{target:'.zx-x-other-languages, .zx-x-show-other-languages'}">
		<?php echo JText::_('PLG_ZLFRAMEWORK_EXT_LNG_SHOW_ALL_AVAILABLE'); ?>
	</a>
	<?php endif; ?>
</div>
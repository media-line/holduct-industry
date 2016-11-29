<?php

defined('_JEXEC') or die;

$markersList = json_decode($params->get('markers_list'));
$markersCount = count($markersList->left_indent);
if ($params->get('border') != '0'){
	$border = 'border: solid 1px '.$params->get('border').';';
}
if ($params->get('marker_bg') != '0'){
	$markerBackground = 'background: '.$params->get('marker_bg').';';
} else if($params->get('marker_image') != ''){
	$markerBackground = 'background-image: url('.$params->get('marker_image').');';
}
?>

<div class="uk-mod_staticmap uk-mod_staticmap<?php echo $moduleclass_sfx ?>">
	<div class="uk-container uk-container-center">
		<div class="uk-position-relative">
			<div class="uk-staticmap-image" style="background: url(<?php echo $params->get('map_image');?>) no-repeat center; width: <?php echo $params->get('map_width');?>; height: <?php echo $params->get('map_height');?>; <?php echo $border;?>">
			</div>

			<?php if($params->get('marker_image')){ ?>
				<?php for ($i = 0; $i < $markersCount; $i++){ ?>
					<div class="uk-position-absolute uk-staticmap-marker uk-staticmap-marker-image uk-staticmap-marker-<?php echo $markersList->size[$i];?>" style="left: <?php echo $markersList->left_indent[$i];?>; top: <?php echo $markersList->top_indent[$i];?>; <?php echo $markerBackground;?>">
					</div>
				<?php } ?>
			<?php } else {?>
				<?php for ($i = 0; $i < $markersCount; $i++){ ?>
					<div class="uk-position-absolute uk-staticmap-marker uk-staticmap-marker-<?php echo $markersList->size[$i];?>" style="left: <?php echo $markersList->left_indent[$i];?>; top: <?php echo $markersList->top_indent[$i];?>; width: <?php echo $params->get('marker_width');?>px; height: <?php echo $params->get('marker_height');?>px; <?php echo $markerBackground;?>">
						<i class="icon-map-marker"></i>
					</div>
				<?php } ?>
			<?php } ?>

			<div class="uk-staticmap-text uk-position-absolute uk-width-2-5">
				<div class="uk-staticmap-text-title uk-h1 uk-gotham-light uk-margin-large-bottom"><?php echo
					$params->get('map_title');?></div>
				<div class="uk-staticmap-text-decs uk-width-4-5"><?php echo $params->get('map_desc');?></div>
			</div>
		</div>
	</div>
</div>

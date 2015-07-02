<?php
// source: /data/web/virtuals/79013/virtual/luxfery/app/templates/@layout.latte

// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('5506134783', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block script
//
if (!function_exists($_b->blocks['script'][] = '_lba1fbd2b566_script')) { function _lba1fbd2b566_script($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?>		<script>
			var cellsX = <?php echo Latte\Runtime\Filters::escapeJs($config['cells_x']) ?>

			var cellsY = <?php echo Latte\Runtime\Filters::escapeJs($config['cells_y']) ?>

			var animationStep = <?php echo Latte\Runtime\Filters::escapeJs($config['animation_step']) ?>

		</script>
		<link rel="stylesheet" href="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/css/style.css">
		<script src="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/js/jquery-2.1.3.js"></script>
		<script src="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/js/patterns.js"></script>
		<script src="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/js/script.js"></script>
		<script src="<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($basePath), ENT_COMPAT) ?>/js/mobile.js"></script>
<?php
}}

//
// block grid
//
if (!function_exists($_b->blocks['grid'][] = '_lbe3af9a2d8f_grid')) { function _lbe3af9a2d8f_grid($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
;
}}

//
// end of blocks
//

// template extending

$_l->extends = empty($_g->extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL; $_g->extended = TRUE;

if ($_l->extends) { ob_start();}

// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIMacros::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1.0 initial-scale=1, maximum-scale=1">
	<title>Game of Life</title>
	
<?php if ($_l->extends) { ob_end_clean(); return $template->renderChildTemplate($_l->extends, get_defined_vars()); }
call_user_func(reset($_b->blocks['script']), $_b, get_defined_vars())  ?>

</head>
<body>
	<div class="control">
		<h1>Game of Life</h1>
		<a class="colorBlock" target="_blank" href="http://en.wikipedia.org/wiki/Conway%27s_Game_of_Life">what is this all about ?</a>
		<div class="clear"></div>
		<select class="colorPicker colorBlock" id="background">
			<option value="" disabled selected>background color</option>
			<option style="background-color: #F80000" value="F80000">red</option>
			<option style="background-color: #FF4500" value="FF4500">orange</option>
			<option style="background-color: #00FF00" value="00FF00">green</option>
			<option style="background-color: #9ACD32" value="9ACD32">yellow green</option>
			<option style="background-color: #FFFF00" value="FFFF00">yellow </option>
			<option style="background-color: #FF00FF" value="FF00FF">fuchsia</option>
			<option style="background-color: #66FFFF" value="66FFFF">aqua</option>
			<option style="background-color: #3300FF" value="3300FF">dark blue</option>
			<option style="background-color: #0000FF" value="0000FF">blue</option>
			<option style="background-color: #FFFFCC" value="FFFFCC">light yellow</option>
			<option style="background-color: #660099" value="660099">purple</option>
			<option style="background-color: #222222" value="222222">black</option>
		</select>
		<select class="colorPicker colorBlock" id="foreground">
			<option value="" disabled selected>cells color</option>
			<option style="background-color: #F80000" value="F80000">red</option>
			<option style="background-color: #FF4500" value="FF4500">orange</option>
			<option style="background-color: #00FF00" value="00FF00">green</option>
			<option style="background-color: #9ACD32" value="9ACD32">yellow green</option>
			<option style="background-color: #FFFF00" value="FFFF00">yellow </option>
			<option style="background-color: #FF00FF" value="FF00FF">fuchsia</option>
			<option style="background-color: #66FFFF" value="66FFFF">aqua</option>
			<option style="background-color: #3300FF" value="3300FF">dark blue</option>
			<option style="background-color: #0000FF" value="0000FF">blue</option>
			<option style="background-color: #FFFFCC" value="FFFFCC">light yellow</option>
			<option style="background-color: #660099" value="660099">purple</option>
			<option style="background-color: #FFC0CB" value="FFC0CB">light pink</option>
			<option style="background-color: #222222" value="222222">black</option>
		</select>
		<select id="pattern" class="colorBlock">
			<option value="" disabled selected>pattern</option>
			<option value="default0">one cell</option>
			<optgroup label="oscillators">
				<option value="default1">blinker</option>
				<option value="default2">toad</option>
				<option value="default3">beacon</option>
			</optgroup>
			<optgroup label="still lifes">
				<option value="default4">block</option>
				<option value="default5">beehive</option>
				<option value="default6">loaf</option>
				<option value="default7">boat</option>
			</optgroup>
			<optgroup label="spaceships">
				<option value="default8">glider</option>
				<option value="default9">lightweight spaceship</option>
			</optgroup>
			<optgroup label="methuselahs">
				<option value="default10">the r-pentomino</option>
				<option value="default11">diehard</option>
				<option value="default12">acorn</option>
			</optgroup>
		</select>
		<button class="clearGrid colorBlock right">clear</button>
		<button class="pause stop colorBlock right">pause</button>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
<?php call_user_func(reset($_b->blocks['grid']), $_b, get_defined_vars())  ?>
	<div class="author">created by: Matúš Buranovský, Adam Heinrich, Jan Husák</div>
</body>
</html>

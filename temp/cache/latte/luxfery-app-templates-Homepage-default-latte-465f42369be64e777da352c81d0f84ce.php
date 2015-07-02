<?php
// source: /data/web/virtuals/79013/virtual/luxfery/app/templates/Homepage/default.latte

// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('6197936498', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block grid
//
if (!function_exists($_b->blocks['grid'][] = '_lbaba4e0f4d6_grid')) { function _lbaba4e0f4d6_grid($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><div class="grid" style="width:<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::escapeCss($config['cells_x'] * 27), ENT_COMPAT) ?>px;">
<?php Tracy\Debugger::barDump(array('$config' => $config), "Template " . str_replace(dirname(dirname($template->getName())), "\xE2\x80\xA6", $template->getName())) ;for ($i = 0; $i < $config['cells_x']; $i ++) { for ($j = 0; $j < $config['cells_y']; $j ++) { ?>
			<div class="cell" number="<?php echo Latte\Runtime\Filters::escapeHtml($i * $config['cells_y'] + $j, ENT_COMPAT) ?>
" state="" style="background-color: #<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::escapeCss($config['background']), ENT_COMPAT) ?>"></div>
<?php } } ?>
	<div class="handle">
		<div class="largeHandle"></div>
		<div class="selection"></div>
	</div>
	<div class="clear"></div>
</div>
<?php
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
if ($_l->extends) { ob_end_clean(); return $template->renderChildTemplate($_l->extends, get_defined_vars()); }
call_user_func(reset($_b->blocks['grid']), $_b, get_defined_vars()) ; 
<?php
$jsnutils	= JSNTplUtils::getInstance();
$doc		= $this->_document;

// Count module instances
$doc->hasRight		= $jsnutils->countModules('right');
$doc->hasLeft		= $jsnutils->countModules('left');
$doc->hasPromo		= $jsnutils->countModules('promo');
$doc->hasPromoLeft	= $jsnutils->countModules('promo-left');
$doc->hasPromoRight	= $jsnutils->countModules('promo-right');
$doc->hasInnerLeft	= $jsnutils->countModules('innerleft');
$doc->hasInnerRight	= $jsnutils->countModules('innerright');

// Define template colors
$doc->templateColors = array('blue', 'red', 'green', 'violet', 'orange', 'grey');

if (isset($doc->sitetoolsColorsItems))
{
	$this->_document->templateColors = $doc->sitetoolsColorsItems;
}

// Apply K2 style
if ($jsnutils->checkK2())
{
	$doc->addStylesheet($doc->templateUrl . "/ext/k2/jsn_ext_k2.css");
}

// Start generating custom styles
$customCss	= '';

// Process TPLFW v2 parameter
if (isset($doc->customWidth))
{
	if ($doc->customWidth != 'responsive')
	{
		$customCss .= '
	#jsn-pos-topbar,
	#jsn-header-inner,
	#jsn-menu-inner,
	#jsn-body,
	#jsn-footer {
		width: ' . $doc->customWidth . ';
		min-width: ' . $doc->customWidth . ';
	}';
	}
}

// Setup slide menu width parameter
if ($doc->mainMenuWidth)
{
	$menuMargin = $doc->mainMenuWidth;

	$customCss .= '
		div.jsn-modulecontainer ul.menu-mainmenu ul,
		div.jsn-modulecontainer ul.menu-mainmenu ul li {
			width: ' . $doc->mainMenuWidth . 'px;
		}
		div.jsn-modulecontainer ul.menu-mainmenu > li:hover > ul {
			margin-left: -' . $menuMargin/2 . 'px;
			left: 80%;
		}
		div.jsn-modulecontainer ul.menu-mainmenu > li:hover > ul {
			left: 50%;
		}
	';
}

$doc->addStyleDeclaration(trim($customCss, "\n"));

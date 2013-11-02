<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(CModule::IncludeModule('fileman'))
{
	/** 
	 * Так как без JS- и CSS-файлов фоторамы компонент не имеет смысла, добавляем их тут
	 */
	$APPLICATION->SetAdditionalCSS('http://fotorama.s3.amazonaws.com/4.4.6/fotorama.css');

	if(!empty($arParams['SHOW_NAVIGATION_ON_IMAGE']) && $arParams['SHOW_NAVIGATION_ON_IMAGE'] === 'Y')
	{
		$APPLICATION->AddHeadString('<style>.fotorama__nav-wrap{position: absolute;bottom: 2px;left:0;right:0;}</style>');
	}

	$APPLICATION->AddHeadString('<script>!window.jQuery && document.write(unescape(\'%3Cscript src="//code.jquery.com/jquery-1.10.2.min.js"%3E%3C/script%3E\'))</script>');
	$APPLICATION->AddHeadScript('http://fotorama.s3.amazonaws.com/4.4.6/fotorama.js');
	
	if ($this->StartResultCache($arParams['CACHE_TIME'], $arParams['MEDIALIBRARY_COLLECTION']))
	{
		$arResult['IMAGES'] = $this->getImages($arParams);

		$this->IncludeComponentTemplate();
	}
}
else 
{
	$this->AbortResultCache();
	ShowError(GetMessage('FILEMAN_MODULE_NOT_INSTALLED'));
	return;
}
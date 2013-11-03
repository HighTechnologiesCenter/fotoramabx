<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(CModule::IncludeModule('fileman'))
{
	/** 
	 * Так как без JS- и CSS-файлов фоторамы компонент не имеет смысла, добавляем их тут
	 */
	$APPLICATION->SetAdditionalCSS('http://fotorama.s3.amazonaws.com/4.4.6/fotorama.css');
	$APPLICATION->AddHeadString('<script>!window.jQuery && document.write(unescape(\'%3Cscript src="//code.jquery.com/jquery-1.10.2.min.js"%3E%3C/script%3E\'))</script>',true);
	$APPLICATION->AddHeadScript('http://fotorama.s3.amazonaws.com/4.4.6/fotorama.js');
	
	if ($this->StartResultCache($arParams['CACHE_TIME'], $arParams['SOURCE_TYPE'] . $arParams['SOURCE_ID']))
	{
		/**
		 * В зависимости от того, что используется в качестве источника изображений
		 * вызовем метод из class.php
		 */
		switch($arParams['SOURCE_TYPE'])
		{
			case 'medialibrary_collection':
				$arResult['IMAGES'] = $this->getImagesFromMedialibraryCollection($arParams['SOURCE_ID']);
				break;
			case 'iblock_section':
				$arResult['IMAGES'] = $this->getImagesFromIblockSection($arParams['SOURCE_ID']);
				break;
		}

		$this->IncludeComponentTemplate();
	}
}
else 
{
	$this->AbortResultCache();
	ShowError(GetMessage('FILEMAN_MODULE_NOT_INSTALLED'));
	return;
}
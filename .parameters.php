<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$availableSources = array(); //массив для конечных идентификаторов источников изображений 
$sourceIdName = ''; //заголовок поля выбором ID источника изображений

$fullscreenModes = array(
	'false' => GetMessage('FULLSCREEN_DISABLED'),
	'true' => GetMessage('FULLSCREEN_ENABLED'),
	'native' => GetMessage('FULLSCREEN_NATIVE'),
);
$navigationStyles = array(
	'thumbs' => GetMessage('NAVIGATION_THUMBS'),
	'dots' => GetMessage('NAVIGATION_DOTS'),
	'false' => GetMessage('NAVIGATION_NONE'),
);
$navigationPositions = array(
	'bottom' => GetMessage('NAVIGATION_POSITION_BOTTOM'),
	'top' => GetMessage('NAVIGATION_POSITION_TOP'),
);
$sourceTypes = array( //типы источников изображений
	'medialibrary_collection' => GetMessage('MEDIALIBRARY_COLLECTION'), //коллекция медиабиблиотеки
	'iblock_section' => GetMessage('IBLOCK_SECTION'), //раздел инфоблока (используются изображения анонса и детальные изображения элементов) 
);

switch($arCurrentValues['SOURCE_TYPE'])
{
	case 'iblock_section':
		$sourceIdName = GetMessage('IBLOCK_SECTION');
		
		if(CModule::IncludeModule("iblock"))
		{			
			/**
			 * Найдем все разделы выбранного инфоблока (если он, конечно, выбран)
			 */
			if(!empty($arCurrentValues['IBLOCK_ID']) && $arCurrentValues['IBLOCK_ID'] > 0)
			{
				$dbIblockSections = CIBlockSection::GetList(
					array(
						'SECTION' => 'ASC',
						'SORT' => 'ASC',
					),
					array(
						'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
						'ACTIVE' => 'Y',
					),
					false,
					array(
						'ID', 
						'NAME'
					),
					false
				);
				
				while($iblockSectionInfo = $dbIblockSections->GetNext())
				{
					$availableSources[$iblockSectionInfo['ID']] = $iblockSectionInfo['NAME'];
				}
			}
		}
		else
		{
			ShowError(GetMessage('IBLOCK_MODULE_NOT_INSTALLED'));//TODO ошибки не показываются в форме редактирования параметров компонента
			return;
		}
		break;
	case 'medialibrary_collection':
	default:
		$sourceIdName = GetMessage('MEDIALIBRARY_COLLECTION');
		
		if(CModule::IncludeModule("fileman"))
		{
			CMedialib::Init(); //Классы медиабиблиотеки недоступны до ее инициализации
	
			//CMedialibCollection::GetList возвращает сразу массив с информацией о коллекциях 
			$medialibraryCollections = CMedialibCollection::GetList(
				array(
					'arFilter' => array(
						'ACTIVE' => 'Y'
					)
				)
			);
	
			foreach($medialibraryCollections as $medialibraryCollection)
			{
				$collectionId = $medialibraryCollection['ID'];
				$collectionName = $medialibraryCollection['NAME'];
	
				$availableSources[$collectionId] = $collectionName;
			}
		}
		else
		{
			ShowError(GetMessage('FILEMAN_MODULE_NOT_INSTALLED'));//TODO ошибки не показываются в форме редактирования параметров компонента
			return;
		}
		break;
}


$arComponentParameters = array(
	'GROUPS' => array(
		'FOTORAMA_EXTENDED_SETTINGS' => array(
			'NAME' => GetMessage('FOTORAMA_EXTENDED_SETTINGS'),
			'SORT' => 400,
		),		
	),
	'PARAMETERS' => array(
		'CACHE_TIME' => array(
			'DEFAULT' => 3600,
		),
	),
);
$arComponentParameters['PARAMETERS']['SOURCE_TYPE'] = array( //выбор источника изображений
	'PARENT' => 'BASE',
	'NAME' => GetMessage('SOURCE_TYPE'),
	'TYPE' => 'LIST',
	'ADDITIONAL_VALUES' => 'N',
	'VALUES' => $sourceTypes,
	'REFRESH' => 'Y',
	'MULTIPLE' => 'N',
);

if($arCurrentValues['SOURCE_TYPE'] === 'iblock_section')
{
	//TODO здесь надо бы проверять, подключен ли вообще модуль инфоблоков
	$iblocksList = array();
	$dbIblocks = CIBlock::GetList(
		array(
			'IBLOCK_TYPE' => 'ASC',
			'SORT' => 'ASC',
		),
		array(
			'ACTIVE' => 'Y',
		),
		false
	);

	while($iblockInfo = $dbIblocks->Fetch())
	{
		$iblocksList[$iblockInfo['ID']] = $iblockInfo['NAME'];
	}

	$arComponentParameters['PARAMETERS']['IBLOCK_ID'] = array(
		'PARENT' => 'BASE',
		'NAME' => GetMessage('IBLOCK'),
		'TYPE' => 'LIST',
		'ADDITIONAL_VALUES' => 'N',
		'VALUES' => $iblocksList,
		'REFRESH' => 'Y',
		'MULTIPLE' => 'N',
	);
}

$arComponentParameters['PARAMETERS']['SOURCE_ID'] = array( //выбор коллекции, из которой брать фотографии
	'PARENT' => 'BASE',
	'NAME' => $sourceIdName,
	'TYPE' => 'LIST',
	'ADDITIONAL_VALUES' => 'N',
	'VALUES' => $availableSources,
	'REFRESH' => 'N',
	'MULTIPLE' => 'N',
);
$arComponentParameters['PARAMETERS']['ALLOW_FULLSCREEN'] = array( //выбор режима поноэкранного просмотра
	'PARENT' => 'FOTORAMA_EXTENDED_SETTINGS',
	'NAME' => GetMessage('ALLOW_FULLSCREEN'),
	'TYPE' => 'LIST',
	'ADDITIONAL_VALUES' => 'N',
	'VALUES' => $fullscreenModes,
	'REFRESH' => 'N',
	'MULTIPLE' => 'N',
);
$arComponentParameters['PARAMETERS']['NAVIGATION_STYLE'] = array( //выбор стиля навигации (миниатюры, точки или никакой навигации)
	'PARENT' => 'FOTORAMA_EXTENDED_SETTINGS',
	'NAME' => GetMessage('NAVIGATION_STYLE'),
	'TYPE' => 'LIST',
	'ADDITIONAL_VALUES' => 'N',
	'VALUES' => $navigationStyles,
	'REFRESH' => 'N',
	'MULTIPLE' => 'N',
);
$arComponentParameters['PARAMETERS']['SHOW_CAPTION'] = array( //показывать подписи
	'PARENT' => 'FOTORAMA_EXTENDED_SETTINGS',
	'NAME' => GetMessage('SHOW_CAPTION'),
	'TYPE' => 'CHECKBOX',
);
$arComponentParameters['PARAMETERS']['SHUFFLE'] = array( //перемешивать ли изображения каждый раз перед выводом
	'PARENT' => 'FOTORAMA_EXTENDED_SETTINGS',
	'NAME' => GetMessage('SHUFFLE'),
	'TYPE' => 'CHECKBOX',
);
$arComponentParameters['PARAMETERS']['CHANGE_HASH'] = array( //изменять ли хэш в адресной строке
	'PARENT' => 'FOTORAMA_EXTENDED_SETTINGS',
	'NAME' => GetMessage('CHANGE_HASH'),
	'TYPE' => 'CHECKBOX',
);
$arComponentParameters['PARAMETERS']['LAZY_LOAD'] = array( //игнорировать браузеры с отключенным JS http://fotorama.io/customize/lazy-load/
	'PARENT' => 'FOTORAMA_EXTENDED_SETTINGS',
	'NAME' => GetMessage('LAZY_LOAD'),
	'TYPE' => 'CHECKBOX',
);
$arComponentParameters['PARAMETERS']['NAVIGATION_POSITION'] = array( //расположение навигации
	'PARENT' => 'FOTORAMA_EXTENDED_SETTINGS',
	'NAME' => GetMessage('NAVIGATION_POSITION'),
	'TYPE' => 'LIST',
	'ADDITIONAL_VALUES' => 'N',
	'VALUES' => $navigationPositions,
	'REFRESH' => 'N',
	'MULTIPLE' => 'N',
);
$arComponentParameters['PARAMETERS']['LOOP'] = array( //зациклить навигацию по изображениям
	'PARENT' => 'FOTORAMA_EXTENDED_SETTINGS',
	'NAME' => GetMessage('LOOP'),
	'TYPE' => 'CHECKBOX',
);

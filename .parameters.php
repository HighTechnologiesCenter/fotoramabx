<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$availableMedialibraryCollections = array(); //список доступных коллекций медиабиблиотеки
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
		
		$availableMedialibraryCollections[$collectionId] = $collectionName;
	}
}	

$arComponentParameters = array(
	'GROUPS' => array(
		'FOTORAMA_EXTENDED_SETTINGS' => array(
			'NAME' => GetMessage('FOTORAMA_EXTENDED_SETTINGS'),
			'SORT' => 400,
		),		
	),
	'PARAMETERS' => array(
		'MEDIALIBRARY_COLLECTION' => array( //выбор коллекции, из которой брать фотографии
			'PARENT' => 'BASE',
			'NAME' => GetMessage('MEDIALIBRARY_COLLECTION'),
			'TYPE' => 'LIST',
			'ADDITIONAL_VALUES' => 'N',
			'VALUES' => $availableMedialibraryCollections,
			'REFRESH' => 'N',
			'MULTIPLE' => 'N',
		),
		'ALLOW_FULLSCREEN' => array( //выбор режима поноэкранного просмотра
			'PARENT' => 'BASE',
			'NAME' => GetMessage('ALLOW_FULLSCREEN'),
			'TYPE' => 'LIST',
			'ADDITIONAL_VALUES' => 'N',
			'VALUES' => $fullscreenModes,
			'REFRESH' => 'N',
			'MULTIPLE' => 'N',
		),
		'NAVIGATION_STYLE' => array( //выбор стиля навигации (миниатюры, точки или никакой навигации)
			'PARENT' => 'BASE',
			'NAME' => GetMessage('NAVIGATION_STYLE'),
			'TYPE' => 'LIST',
			'ADDITIONAL_VALUES' => 'N',
			'VALUES' => $navigationStyles,
			'REFRESH' => 'N',
			'MULTIPLE' => 'N',
		),
		'SHUFFLE' => array( //перемешивать ли изображения каждый раз перед выводом
			'PARENT' => 'BASE',
			'NAME' => GetMessage('SHUFFLE'),
			'TYPE' => 'CHECKBOX',
		),
		'CHANGE_HASH' => array( //изменять ли хэш в адресной строке
			'PARENT' => 'FOTORAMA_EXTENDED_SETTINGS',
			'NAME' => GetMessage('CHANGE_HASH'),
			'TYPE' => 'CHECKBOX',
		),
		'LAZY_LOAD' => array( //игнорировать браузеры с отключенным JS http://fotorama.io/customize/lazy-load/
			'PARENT' => 'FOTORAMA_EXTENDED_SETTINGS',
			'NAME' => GetMessage('LAZY_LOAD'),
			'TYPE' => 'CHECKBOX',
		),
		'SHOW_NAVIGATION_ON_IMAGE' => array( //показываь навигацию поверх изображения
			'PARENT' => 'FOTORAMA_EXTENDED_SETTINGS',
			'NAME' => GetMessage('SHOW_NAVIGATION_ON_IMAGE'),
			'TYPE' => 'CHECKBOX',
		),
		'LOOP' => array( //зациклить навигацию по изображениям
			'PARENT' => 'FOTORAMA_EXTENDED_SETTINGS',
			'NAME' => GetMessage('LOOP'),
			'TYPE' => 'CHECKBOX',
		),
		'CACHE_TIME' => array(
			'DEFAULT' => 3600,
		),
	),
);
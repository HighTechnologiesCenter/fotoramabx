<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CFotoramaComponent extends CBitrixComponent
{
	/**
	 * Получает все изображения коллекции медиабиблиотеки
	 * @param $medialibraryCollectionId
	 * @return array
	 */
	public function getImagesFromMedialibraryCollection($medialibraryCollectionId)
	{
		$images = array();
		
		CMedialib::Init(); //Классы медиабиблиотеки недоступны до ее инициализации
		
		$items = CMedialibItem::GetList(array(
			'arCollections' => array(
				$medialibraryCollectionId,
			)
		));

		/**
		 * В CMedialibItem::GetList нет возможности фильтрации по типу элемента коллекции, 
		 * поэтому придется выбрать изображения вручную
		 */
		foreach($items as $item)
		{
			if($item['TYPE'] === 'image')
			{
				$image = array(
					'HEIGHT' => $item['HEIGHT'],
					'WIDTH' => $item['WIDTH'],
					'PATH' => $item['PATH'],
					'THUMB_PATH' => $item['THUMB_PATH'],
				);
				$images[] = $image;
			}
		}
		
		return $images;
	}

	/**
	 * Получает все изображения (анонса и детальные) элементов одного раздела инфоблока
	 * @param $sectionId
	 * @return array
	 */
	public function getImagesFromIblockSection($sectionId)
	{
		$images = array();

		if(CModule::IncludeModule("iblock"))
		{
			$iblockElements = CIBlockElement::GetList(
				array(
					'SORT' => 'ASC',
					'ID' => 'ASC',
					'HAS_DETAIL_PICTURE' => 'Y',
					'HAS_PREVIEW_PICTURE' => 'Y'
				),
				array(
					'SECTION_ID' => $sectionId,
					'ACTIVE' => 'Y',
				),
				false,
				false,
				array(
					'ID',
					'NAME',
					'PREVIEW_PICTURE',
					'DETAIL_PICTURE',
				)
			);

			while($iblockElement = $iblockElements->GetNext())
			{
				$path = CFile::GetPath($iblockElement['DETAIL_PICTURE']); //CFile::GetByID не возвращает полного пути до изображения
				$thumbPath = CFile::GetPath($iblockElement['PREVIEW_PICTURE']);
				$detailPictureInfo = CFile::GetByID($iblockElement['DETAIL_PICTURE'])->Fetch();
				
				$image = array(
					'HEIGHT' => $detailPictureInfo['HEIGHT'],
					'WIDTH' => $detailPictureInfo['WIDTH'],
					'PATH' => $path,
					'THUMB_PATH' => $thumbPath,
				);
				$images[] = $image;
			}
		}
		
		return $images;
	}
}
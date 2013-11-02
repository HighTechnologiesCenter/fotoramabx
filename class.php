<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CFotoramaMedialibraryComponent extends CBitrixComponent
{
	public function getImages($arParams)
	{
		CMedialib::Init();
		
		$images = array();
		
		$items = CMedialibItem::GetList(array(
			'arCollections' => array(
				$arParams['MEDIALIBRARY_COLLECTION'],
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
				$images[] = $item;
			}
		}
		
		return $images;
	}
}
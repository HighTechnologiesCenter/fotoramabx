<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(!empty($arResult['IMAGES']))
{
	?>
	<div class="fotorama" 
	     data-width="100%"
		<?
		/**
		 * Расчитываем соотношение сторон по первой картинке в списке
		 */
		?>
		 data-ratio="<?echo $arResult['IMAGES'][0]['WIDTH'] . '/' . $arResult['IMAGES'][0]['HEIGHT']?>"
	     <?if(!empty($arParams['ALLOW_FULLSCREEN'])){
			?>
			data-allowfullscreen="<?echo $arParams['ALLOW_FULLSCREEN']?>"  
			<?
		}?>
		<?if(!empty($arParams['NAVIGATION_STYLE'])){
			?>
			data-nav="<?echo $arParams['NAVIGATION_STYLE']?>"
			<?
		}?>
		<?if(!empty($arParams['SHUFFLE']) && $arParams['SHUFFLE'] === 'Y'){
			?>
			data-shuffle="true"
			<?
		}?>
		<?if(!empty($arParams['CHANGE_HASH']) && $arParams['CHANGE_HASH'] === 'Y'){
			?>
			data-hash="true"
			<?
		}?>
		<?if(!empty($arParams['LOOP']) && $arParams['LOOP'] === 'Y'){
			?>
			data-loop="true"
			<?
		}?>
		<?if(!empty($arParams['NAVIGATION_POSITION']) && $arParams['NAVIGATION_POSITION'] === 'top'){
			?>
			data-navposition="<?echo $arParams['NAVIGATION_POSITION']?>"
			<?
		}?>
		>
		<?
		foreach($arResult['IMAGES'] as $key => $image)
		{
			?>
			<a href="<?echo $image['PATH'];?>" id="fotorama-<?echo $key;?>" 
				<?if(!empty($arParams['SHOW_CAPTION']) && $arParams['SHOW_CAPTION'] === 'Y' && !empty($image['DESCRIPTION']))
				{
					?>
					data-caption="<?echo $image['DESCRIPTION']?>"
					<?
				}?>
				<?if(!empty($arParams['LAZY_LOAD']) && $arParams['LAZY_LOAD'] === 'Y'){
					?>
					data-thumb="<?echo $image['THUMB_PATH'];?>"
					<?
				}
				else
				{
					?>
					><img src="<?echo $image['THUMB_PATH'];?>" alt=""
					<?
				}
				?>></a>
			<?
		}
		?>
	</div>
	<?
}
else
{
	ShowError(GetMessage('NO_IMAGES'));
}
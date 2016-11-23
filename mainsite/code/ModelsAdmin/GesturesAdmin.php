<?php
/**
 * @file GesturesAdmin.php
 *
 * Left-hand-side tab : Discount orders
 * */

class GesturesAdmin extends ModelAdmin
{
	private static $managed_models = array('Gesture');
	private static $url_segment = 'gestures';
	private static $menu_title = 'Gestures';

	public function getEditForm($id = null, $fields = null)
    {
		$form = parent::getEditForm($id, $fields);

		$grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
		$grid->getConfig()
			->removeComponentsByType('GridFieldPaginator')
			->removeComponentsByType('GridFieldExportButton')
			->removeComponentsByType('GridFieldPrintButton')
			->addComponents(
				new GridFieldPaginatorWithShowAll(30)
			);
		return $form;
	}
}

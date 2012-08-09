<?
class CEventImport {
	public function getImporter() {
		return array(
			'class' => __CLASS__,
			'name' => 'Импортировать события из формата Outlook 2007',
			'id' => __CLASS__,
			'link' => '/bitrix/admin/eventimport_settings.php',
			'module' => 'bx_eventimport',
		);
	}
}
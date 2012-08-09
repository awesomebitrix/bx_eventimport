<?
class bx_eventimport extends CModule {

	public $MODULE_ID = 'bx_eventimport';
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME = 'EventImport';
	public $MODULE_DESCRIPTION = 'Imports data from .vcs (Outlook 2007), depends on bx_ichannels';
	public $MODULE_GROUP_RIGHTS = 'Y';

	public function __construct() {
		include(dirname(__FILE__) . '/version.php');
		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
	}

	public function DoInstall() {
		RegisterModule($this->MODULE_ID);

		CopyDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/bx_eventimport/install/admin', 
			$_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin'
		);

		CopyDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/bx_eventimport/install/js',
			$_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/bx_eventimport',
			true,
			true
		);

		RegisterModuleDependences(
			'bx_ichannels', 
			'getImporters', 
			'bx_eventimport', 
			'CEventImport', 
			'getImporter'
		);
	}

	public function DoUninstall() {
		COption::RemoveOption($this->MODULE_ID);
		UnregisterModule($this->MODULE_ID);

		DeleteDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/bx_eventimport/install/admin', 
			$_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin'
		);

		DeleteDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/bx_eventimport/install/js',
			$_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/bx_eventimport'
		);

		UnRegisterModuleDependences(
			'bx_ichannels', 
			'getImporters', 
			'bx_eventimport', 
			'CEventImport', 
			'getImporter'
		);
	}
}
<?
require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_admin_before.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_admin_after.php');

$APPLICATION->SetTitle('Настройки импорта событий');

$APPLICATION->SetAdditionalCSS('/bitrix/js/bx_eventimport/css/settings.css');

if (array_key_exists('form_id', $_POST) && $_POST['form_id'] == 'eventimport_settings') {
	
	COption::SetOptionString(
		'bx_eventimport', 
		'event_source',
		filter_input(INPUT_POST, 'event_source', FILTER_SANITIZE_STRING) 
	);

	$import_period = intval(filter_input(INPUT_POST, 'import_period', FILTER_SANITIZE_STRING));
	
	COption::SetOptionInt(
		'bx_eventimport',
		'import_period',
		$import_period
	);

	COption::SetOptionString(
		'bx_eventimport',
		'IBLOCK_TYPE_ID',
		filter_input(INPUT_POST, 'select-type', FILTER_SANITIZE_STRING)
	);

	COption::SetOptionString(
		'bx_eventimport',
		'IBLOCK_ID',
		filter_input(INPUT_POST, 'select-iblock', FILTER_SANITIZE_STRING)
	);

	COption::SetOptionString(
		'bx_eventimport',
		'IBLOCK_SECTION_ID',
		filter_input(INPUT_POST, 'select-section', FILTER_SANITIZE_STRING)
	);

	CAgent::RemoveAgent(
		'CEventImportAgent::ImportEvents();',
		'bx_eventimport'
	);

	CAgent::AddAgent(
		'CEventImportAgent::ImportEvents();',
		'bx_eventimport',
		'N',
		$import_period,
		'',
		'Y',
		'',
		'500'
	);

	header('Location: ichannels_manager.php');
}

$default = array();
foreach (array('IBLOCK_TYPE_ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID') as $option) {
	$default[$option] = COption::GetOptionString('bx_eventimport', $option, '');
}

$aTabs = array(
	array(
		'DIV' => 'tab1',
		'TAB' => 'Настройки импорта событий',
		'ICON' => '',
		'TITLE' => 'Настройки импорта событий',
	),
);

$tabControl = new CAdminTabControl('tabControl', $aTabs);

$tabControl->Begin();
$tabControl->BeginNextTab();
?>

<form action="" method="post">
	<input type="hidden" name="form_id" id="form_id" value="eventimport_settings" />

<tr>
	<td><label for="event_source">Источник импорта</label></td>
	<td><input type="text" name="event_source" id="event_source" class="strval strval-long" value="<?=COption::GetOptionString('bx_eventimport', 'event_source', '');?>" /></td>
</tr>

<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/ichannels_iblock_form.php'); ?>

<tr>
	<td><label for="import_period">Частота обновлений (в секундах)</label></td>
	<td><input type="text" name="import_period" id="import_period" class="intval" value="<?=COption::GetOptionInt('bx_eventimport', 'import_period', 86400);?>" /></td>
</tr>

<?
$tabControl->EndTab();
$tabControl->Buttons();
?>

<input type="submit" name="submit" id="submit" value="Сохранить" />

<?
$tabControl->End();
?>

</form>

<?
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/epilog_admin.php');
?>
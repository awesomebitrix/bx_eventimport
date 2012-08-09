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
	
	COption::SetOptionInt(
		'bx_eventimport',
		'import_period',
		intval(filter_input(INPUT_POST, 'import_period', FILTER_SANITIZE_STRING))
	);

	// here we do agent

	header('Location: ichannels_manager.php');
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
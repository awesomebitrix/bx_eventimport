<?
class CEventImportAgent {
	public static function ImportEvents() {
		global $USER;
		if (!is_object($USER)) $USER = new CUser;

		$source = COption::GetOptionString('bx_eventimport', 'event_source', '');
		if (!empty($source)) {
			$source_content = file_get_contents($source);
			$source_content = explode("\n", $source_content);

			$events = array();
			$event = array();
			$in_event = false;
			foreach ($source_content as $str) {
				if ($str == 'BEGIN:VEVENT') {
					$in_event = true;
					continue;
				}
				if ($in_event) {
					if ($str == 'END:VEVENT') {
						$events[] = $event;
						$event = null;
						$in_event = false;
						continue;
					} else {
						list($field, $value) = explode(':', $str, 2);
						switch ($field) {
							case 'DTSTART':
								$date = ConvertTimeStamp(strtotime($value));
								$event['DATE_ACTIVE_FROM'] = $date;
								$event['DATE_ACTIVE_TO'] = $date;
								break;
							case 'SUMMARY':
								$event['NAME'] = iconv('utf8', LANG_CHARSET, $value);
								break;
							case 'DESCRIPTION':
								$event['DETAIL_TEXT'] = iconv('utf8', LANG_CHARSET, $value);
								break;
						}
					}
				}
			}

			$IBLOCK_TYPE_ID = COption::GetOptionString('bx_eventimport', 'IBLOCK_TYPE_ID', '');
			$IBLOCK_ID = COption::GetOptionString('bx_eventimport', 'IBLOCK_ID', '');
			$IBLOCK_SECTION_ID = COption::GetOptionString('bx_eventimport', 'IBLOCK_SECTION_ID', '');

			CModule::IncludeModule('iblock');

			if (!empty($events) && !empty($IBLOCK_TYPE_ID) && !empty($IBLOCK_ID)) {
				foreach ($events as $event) {
					$rResult = CIBlockElement::GetList(
						array('SORT' => 'ASC'),
						array(
							'ACTIVE' => 'Y',
							'IBLOCK_TYPE_ID' => $IBLOCK_TYPE_ID,
							'IBLOCK_ID' => $IBLOCK_ID,
							'SECTION_ID' => $IBLOCK_SECTION_ID,
							'NAME' => $event['NAME'],
						),
						false,
						false,
						array('NAME')
					);
					if ($rResult->GetNext()) continue;
					$event['IBLOCK_TYPE_ID'] = $IBLOCK_TYPE_ID;
					$event['IBLOCK_ID'] = $IBLOCK_ID;
					$event['IBLOCK_SECTION_ID'] = $IBLOCK_SECTION_ID;

					$new = new CIBlockElement;
					$id = $new->Add($event);
				}
			}
		}
		return sprintf('%s();', __METHOD__);
	}
}
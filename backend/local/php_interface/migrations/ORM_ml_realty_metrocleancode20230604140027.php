<?php

namespace Sprint\Migration;


class ORM_ml_realty_metrocleancode20230604140027 extends Version
{
	protected $description = "Удаление из значений столбца code суффикса с Id города в таблице ml_realty_metro (для ID города есть отдельный столбец)";

	protected $moduleVersion = "4.1.1";

	private $table = 'ml_realty_metro';

	/**
	 * Сохраняем старые значения code в новую колонку code_old, удаляем из cold суффиксы
	 */
	public function up()
	{
		global $DB;

		try {
			if (!$this->isColumnExist('code_old')) {
				//создаём code_old
				$DB->Query('ALTER TABLE ' . $this->table . ' ADD code_old VARCHAR(100);');
			}

			//копируем code в code_old
			$DB->Query('UPDATE ' . $this->table . ' SET code_old = code WHERE 1=1;');

			//получаем записи у которых в code в конце есть суффикс
			$res = $DB->Query('select id, code from ' . $this->table . ' where code REGEXP ".*_[0-9]{1,}$"');
			while ($item = $res->fetch()) {
				$newCode = preg_replace('#_[0-9]{1,}$#i', '', $item['code']);
				$DB->Query('UPDATE ' . $this->table . ' SET code="' . $newCode . '" WHERE id=' . $item['id']);
			}
		} catch (\Exception $e) {
			echo('Error: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Копируем прежние значения кода обратно в code, удаляем колонку code_old
	 */
	public function down()
	{
		global $DB;
		try {
			if ($this->isColumnExist('code_old')) {
				$DB->Query('UPDATE ml_realty_metro SET code = code_old WHERE 1=1;');
				$DB->Query('ALTER TABLE ml_realty_metro DROP COLUMN code_old;');
			}
		} catch (\Exception $e) {
			echo('Error: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * @param $code
	 * @return bool
	 */
	protected function isColumnExist($code)
	{
		global $DB;
		$res = $DB->Query('SHOW COLUMNS FROM ' . $this->table . ' LIKE "' . $code . '"');
		if ($res->fetch()) {
			return true;
		}
		return false;
	}
}

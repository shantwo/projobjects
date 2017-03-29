<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

class City extends DbObject {
	/**
	 * @param int $id
	 * @return DbObject
	 */
	public static function get($id) {
		// TODO: Implement get() method.
	}

	/**
	 * @return DbObject[]
	 */
	public static function getAll() {
		// TODO: Implement getAll() method.
	}

	/**
	 * @return array
	 */
	public static function getAllForSelect() {
		$returnList = array();

		$sql = '
			SELECT cit_id, cit_name
			FROM city
			WHERE cit_id > 0
			ORDER BY cit_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['cit_id']] = $row['cit_name'];
			}
		}

		return $returnList;
	}

	/**
	 * @return bool
	 */
	public function saveDB() {
		// TODO: Implement saveDB() method.
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public static function deleteById($id) {
		// TODO: Implement deleteById() method.
	}

}
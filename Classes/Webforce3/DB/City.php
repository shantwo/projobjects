<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class City extends DbObject {

    protected $name;

    protected $iso;

    protected $country;

    /**
     * City constructor.
     * @param $name
     * @param $iso
     */
    public function __construct($id=0, $name='', $country=null ,$inserted=0 )
    {
        parent::__construct($id, $inserted);

        $this->name = $name;
        if (empty($country)) {
            $this->country = new Country();
        }
        else {
            $this->country = $country;
        }
    }


    /**
     * @param int $id
     * @return DbObject
	 */
    public static function get($id) {
        $sql = 'SELECT cit_id, cit_name, cit_inserted, country_cou_id
        FROM city
        WHERE cit_id = :id';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new City(
                    $row['cit_id'],
                    $row['cit_name'],
                    new Country($row['country_cou_id']),
                    $row['cit_inserted']);
                return $currentObject;
            }
        }
        return false;
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
        $sql = '
				DELETE FROM city 
				WHERE cit_id = :id
			';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        }
        else {
            return true;
        }
        return false;
	}

    public static function getAll()
    {
        // TODO: Implement getAll() method.
    }

    /**
     * @return null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



}
<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 28/07/2017
 * Time: 10:22
 */

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class Location extends DbObject
{
    /** @var Country */
    protected $country;

    protected $name;

    /**
     * Location constructor.
     * @param $name
     */


    public function __construct($id=0, $country=null, $name='', $inserted = '')
    {
        parent::__construct($id, $inserted);

        if (empty($country)) {
            $this->country = new Country();
        }
        else {
            $this->country = $country;
        }

        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }





    public static function get($id)
    {

        $sql = '
			SELECT loc_id, loc_name, loc_inserted, country_cou_id
			FROM location
			WHERE loc_id = :id
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new Location(
                    $row['loc_id'],
                    new Country($row['country_cou_id']),
                    $row['loc_name'],
                    $row['loc_inserted']);
                return $currentObject;
            }
        }
        return false;

    }

    public static function getAll()
    {

    }

    public static function getAllForSelect()
    {
        $returnList = array();

        $sql = '
			SELECT loc_id, loc_name
			FROM location
			WHERE loc_id > 0
			ORDER BY loc_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        }
        else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $returnList[$row['loc_id']] = $row['loc_name'];
            }
        }

        return $returnList;
    }

    public function saveDB()
    {

    }

    public static function deleteById($id)
    {

    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }



}
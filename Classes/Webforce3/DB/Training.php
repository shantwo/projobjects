<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 28/07/2017
 * Time: 10:31
 */

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class Training extends DbObject
{

    protected $name;

    /**
     * Location constructor.
     * @param $name
     */
    public function __construct($id=0, $name='', $inserted = '')
    {
        parent::__construct($id, $inserted);

        $this->name = $name;
    }


    public static function get($id)
    {

    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    public static function getAll()
    {

    }

    public static function getAllForSelect()
    {
        $returnList = array();

        $sql = '
			SELECT tra_id, tra_name
			FROM training
			WHERE tra_id > 0
			ORDER BY tra_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        }
        else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $returnList[$row['tra_id']] = $row['tra_name'];
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

}

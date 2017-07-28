<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class Session extends DbObject {

    /** @var string */
    protected $name;

    /** @var  Location */
    protected $location;

    /** @var  Training */
    protected $training;

    /** @var int */
    protected $startDate;

    /** @var  int */
    protected $endDate;

    /** @var  int */
    protected $number;


    public function __construct($id=0, $location=null, $training=null, $startDate=0, $endDate=0,$number = 0, $inserted='') {
        parent::__construct($id, $inserted);
        if (empty($location)) {
            $this->location = new Location();
        }
        else {
            $this->location = $location;
        }
        if (empty($training)) {
            $this->training = new Training();
        }
        else {
            $this->training = $training;
        }
        $this->startDate=$startDate;
        $this->endDate=$endDate;
        $this->number=$number;
        $this->inserted=$inserted;

    }

    /**
	 * @param int $id
	 * @return DbObject
	 */
	public static function get($id) {
        $sql = '
			SELECT ses_id, ses_start_date, ses_end_date, ses_number, ses_inserted, location_loc_id, training_tra_id
			FROM session
			WHERE ses_id = :id
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new Session(
                    $row['ses_id'],
                    new Location($row['location_loc_id']),
                    new Training($row['training_tra_id']),
                    //new Country($row['country_cou_id']),
                    $row['ses_start_date'],
                    $row['ses_end_date'],
                    $row['ses_number'],
                    $row['ses_inserted']
                    );
                return $currentObject;
            }
        }
        return false;
	}

	/**
	 * @return DbObject[]
	 */
	public static function getAll() {
		// TODO: Implement getAll() method.
	}

	public function getStartDate(){
	    if ($this->startDate !== 0){
            return $this->startDate;
        }
        else{
	        return '';
        }
    }

    public function getEndDate(){
        if ($this->endDate !== 0){
            return $this->endDate;
        }
        else{
            return '';
        }
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return Training
     */
    public function getTraining()
    {
        return $this->training;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }




	/**
	 * @return array
	 */
	public static function getAllForSelect() {
		$returnList = array();

		$sql = '
			SELECT ses_id, tra_name, ses_start_date, ses_end_date, ses_number, loc_name
			FROM session
			LEFT OUTER JOIN training ON training.tra_id = session.training_tra_id
			LEFT OUTER JOIN location ON location.loc_id = session.location_loc_id
			WHERE ses_id > 0
			ORDER BY ses_start_date ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['ses_id']] = '['.$row['ses_start_date'].' > '.$row['ses_end_date'].'] '.$row['tra_name'].' - '.$row['loc_name'];
			}
		}

		return $returnList;
	}

	/**
	 * @param int $sessionId
	 * @return DbObject[]
	 */
	public static function getFromSession($sessionId) {
		// TODO: Implement getFromTraining() method.
	}

	/**
	 * @return bool
	 */
	public function saveDB() {
        if ($this->id > 0) {
            $sql = '
				UPDATE session
				SET ses_start_date = :sdate,
				ses_end_date = :edate,
				ses_number = :snumber,
                location_loc_id = :locId,
                training_tra_id = :traId
				WHERE ses_id = :id
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':sdate', $this->startDate, \PDO::PARAM_INT);
            $stmt->bindValue(':edate', $this->endDate, \PDO::PARAM_INT);
            $stmt->bindValue(':snumber', $this->number, \PDO::PARAM_INT);
            $stmt->bindValue(':locId', $this->location->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(':traId', $this->training->getId(), \PDO::PARAM_INT);

            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            }
            else {
                return true;
            }
        }
        else {
            $sql = '
				INSERT INTO session (ses_start_date, ses_end_date, ses_number, location_loc_id, training_tra_id )
				VALUES (:sdate, :edate, :snumber, :locId, :traId)
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':sdate', $this->startDate, \PDO::PARAM_INT);
            $stmt->bindValue(':edate', $this->endDate, \PDO::PARAM_INT);
            $stmt->bindValue(':snumber', $this->number, \PDO::PARAM_INT);
            $stmt->bindValue(':locId', $this->location->id, \PDO::PARAM_INT);
            $stmt->bindValue(':traId', $this->training->id, \PDO::PARAM_INT);

            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            }
            else {
                $this->id = Config::getInstance()->getPDO()->lastInsertId();
                return true;
            }
        }

        return false;
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public static function deleteById($id) {
        $sql = '
				DELETE FROM session 
				WHERE ses_id = :id
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

}
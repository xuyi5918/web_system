<?php
/**
 *
 * @Date:       2018/11/12 21:06
 * @version:    ${Id}
 * @Author:     零上一度 <xuyi5918@live.cn>
 */
class CI_Mongodb
{
	public $host = '127.0.0.1';
	public $port = '27017';
	public $dbName = '';

	public $mongoDb = NULL;
	public $db = NULL;
	public $collection = NULL;

	public function __construct()
	{
	}


	public function connect()
	{		
		if(empty($this->mongoDb) || is_object($this->mongoDb))
		{
			$this->mongoDb = new MongoClient('mongodb://'.$this->host.':'.$this->port);
		}

		if(MongoClient::VERSION >= '0.9.0') {
			$this->db = $this->mongoDb->selectDB($dbName);
		}else{
			$this->db = $this->mongoDb->$dbName;
		}

	}

	/**
	 *
	 * @param $collectionName
	 */
	public function collection($collectionName)
	{
		$collectionName = trim($collectionName);

		if(empty($this->db) || ! is_object($this->db)) {
			throw new Exception('mongoDb class object empty!');
		}

		if(MongoClient::VERSION >= '0.9.0') {
			$this->collection = $this->db->selectCollection($collectionName);
		}else{
			$this->collection = $this->db->$collectionName;
		}

		return $this;
	}

	/**
	 * add mongodb data
	 */
	public function add($row)
	{
		$row = is_array($row) ? $row : array();
		if(empty($row)) {
			throw new MongoConnectionException('add data is empty!');
		}
		$option = array();
		return $this->collection->insert($row);
	}

	/**
	 * 
	 */
	public function addMulti($multiDocumet)
	{
		$multiDocumet = is_array($multiDocumet) ? $multiDocumet : array();
		foreach ($multiDocumet as $key => $documentRow) {
			$this->add($documentRow);
		}
		return TRUE;
	}

	/**
	 * 
	 */
	public function find($queryArr)
	{
		$queryArr = is_array($queryArr) ? $queryArr : array();
		if(empty($queryArr)) {
			throw new Exception('query mongodb colletion where empty!', 1);
		}

		$cursor = $this->collection->find($queryArr);
		$result = array();
		foreach ($cursor as $document) {
			$result[] = $document;
		}
		return $result;
	}

	/**
	 * @param $whereArr 
	 */
	public function delete($whereArr, $justOne = TRUE)
	{
		$whereArr = is_array($whereArr) ? $whereArr : array();
		if(empty($whereArr)) {
			throw new Exception("delete mongodb colletion where error !", 1);
		}

		$option = array();
		$option = array_merge($option, array('justOne'=> $justOne));
		return $this->collection->remove($whereArr, $option);
	}
	
}
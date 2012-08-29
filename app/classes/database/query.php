<?php



/**
 * Extends the query class to prevent any databases insert / delete / updates..
 * 
 * @extends Fuel\Core\Database_Query 
 */
class Database_Query extends Fuel\Core\Database_Query {
	
	
	/**
	 * if type is insert / delete / updates, throw a PlexOverException()
	 * and shutdown.
	 * 
	 * @access public
	 * @param mixed $sql
	 * @param mixed $type
	 * @return void
	 */
	public function __construct($sql, $type)
	{
		switch ($type)
		{
			case \DB::INSERT:
			case \DB::UPDATE:
			case \DB::DELETE:
			throw new PlexOverException("Database connection is Read Only !");
		}
		return parent::__construct($sql, $type);
	}

	
}
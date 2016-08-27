<?php
namespace DB;
/**
*   Db class update with PDO for POSR and other projects
**/

use PDO;
use DB\Exceptions\QueryException;

class DB{

    //database driver, default is mysql
    protected $driver = 'mysql';

    //database server host
    protected $host;

    //database name to be connect
    protected $database;

    //database port from which to connect
    protected $port = 3306;

    //username of database server
    protected $username = 'root';

    //password of database server
    protected $password = 'root';

    //tablet prefix
    protected $prefix = '';

    //pdo object
    protected static $pdo;

    //statement object
    protected $stmt;

    //result object
    protected $result;

    // default fetch style
    const FETCH_AS_OBJECT = PDO::FETCH_OBJ;

    //constant for fetch the result as array
    const FETCH_AS_ARRAY = PDO::FETCH_ASSOC;

    //last executed query
    protected $query;

    //stack for selects
    protected $selects = array();

    //stack for where
    protected $wheres = array();

    //table
    protected $table;

    //

    /**
    *   public constructor to accpet database connection
    **/
    public function __construct($host = null, $username = null, $password = null, $database = null, $prefix = '', $port = 3306){
        if(func_num_args() > 0){

            $this->host = $host;
            $this->database = $database;
            $this->username = $username;
            $this->password = $password;
            $this->prefix = $prefix;
            
            
        }
    }

    /**
    *   connect to database
    **/
    public static function connect(array $options){
        try {
                self::$pdo = new PDO($this->driver.':host='.$this->host.';dbname='.$this->database, $this->username, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, self::FETCH_AS_OBJECT);
            } catch(PDOException $e){
                throw $e;
            }
    }

    /**
    *   get the current pdo instance
    **/
    public function getPdo(){
        return $this->pdo;
    }

    /**
    *   set the table
    **/
    public function table($table, $alias = null){
        if(is_null($alias)){
            $this->table = $this->prefix . $table;
        }else{
            $this->table = $this->prefix . $table.' as '.$alias;
        }

        return $this;
    }

    /**
    *   get the current table
    **/
    public function getTable(){
        return $this->table;
    }

    /**
    *   build the query and return the result set
    **/
    public function get(){
        $this->query = 'SELECT * FROM '.$this->getTable();

        $this->stmt = $this->pdo->prepare($this->query);
        $this->stmt->execute();

        return $this->stmt;
    }

    /**
    *   get the result as array
    **/
    public function getArray(){
        $stmt = $this->get();

        return $stmt->fetchAll(self::FETCH_AS_ARRAY);
    }

    /**
    *   get the single row from database as an array
    **/
    public function getRowArray(){
        $stmt = $this->get();
        return $stmt->fetch(self::FETCH_AS_ARRAY);
    }

    /**
    *   get the result as object
    **/
    public function getObject(){
        return $this->get()->fetchAll();
    }

    /**
    *   get the single row from database as an object
    **/
    public function getRowObject(){
        return $this->get()->fetch();
    }

    

    /***********************************
    *
    *   methods for just backward compatibility only
    *
    ************************************/

    /**
    *   alias of getArray(), for compatibility only
    **/
    public function get_array(){
        return $this->getArray();
    }

    /**
    *   alias of getRowArray(), for compatibility only
    **/
    public function get_row_array(){
        return $this->getRowArray();
    }

    /**
    *   alias of getObject(), for compatibility only
    **/
    public function get_object(){
        return $this->getObject();
    }

    /**
    *   alias of getRowObject(), just for compatibility only
    **/
    public function get_row(){
        return $this->getRowObject();
    }

    /**
    *   alias of getRowObject(), just for compatibility only
    **/
    public function get_row_object(){
        return $this->getRowObject();
    }


}

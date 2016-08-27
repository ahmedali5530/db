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
    private $host;

    //database name to be connect
    private $database;

    //database port from which to connect
    private $port = 3306;

    //username of database server
    private $username = 'root';

    //password of database server
    private $password = 'root';

    //tablet prefix
    protected $prefix = '';

    //pdo object
    protected static $pdo;

    //statement object
    protected $stmt;

    //result object
    protected $result;

    //num rows
    protected $numRows = 0;

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

        return $this;

        // return static::connect(array(
        //     'driver' => $this->driver,
        //     'host' => $this->host,
        //     'database' => $this->database,
        //     'username' => $this->username,
        //     'password' => $this->password,
        // ));
    }

    /**
    *   connect to database
    **/
    public static function connect(array $options){
        try {
            self::$pdo = new PDO($options['driver'].':host='.$options['host'].';dbname='.$options['database'], $options['username'], $options['password']);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, self::FETCH_AS_OBJECT);

            return new static;
        } catch(PDOException $e){
            throw $e;
        }
    }

    /**
    *   set prefix
    **/
    public function setPrefix($prefix){
        $this->prefix = $prefix;
        return $this;
    }

    /**
    *   get prefix
    **/
    public function getPrefix(){
        return $this->prefix;
    }

    /**
    *   get table name prefixed with prefix
    **/
    public function getPrefixed(){

    }

    /**
    *   get the current pdo instance
    **/
    public function getPdo(){
        return self::$pdo;
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

        $this->stmt = $this->getPdo()->prepare($this->query);
        $this->stmt->execute();

        $this->numRows = $this->stmt->rowCount();

        return $this->stmt;
    }

    /**
    *   get number of rows returned by current query
    **/
    public function numRows(){
        return $this->numRows;
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

    public function __destruct(){
        self::$pdo = null;
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

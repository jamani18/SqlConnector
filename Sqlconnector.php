<?php


//Data to connect.
define("MysqlDomain","WRITE_HERE");
define("MysqlPort","WRITE_HERE");
define("MysqlUser","WRITE_HERE");
define("MysqlPass","WRITE_HERE");
define("MysqlDB","WRITE_HERE");
        

/**
* Connect with database for doing the operations. The connections its saved on GLOBALS to be faster on future connections.
* @param $forceDataBase set other database to connect.
* @return the connection for do operations.
*/       
function connect($forceDataBase = false) {
       try{
           if(!isset($GLOBALS['con'])){
            
            $GLOBALS['con'] =  new PDO("mysql:host=".MysqlDomain.";port=".MysqlPort.";dbname=".($forceDataBase?$forceDataBase:MysqlDB),
                MysqlUser,
                MysqlPass,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            
           }
            return $GLOBALS['con'];
           
       }
       catch(PDOException $e){
            echo "Error: ".$e->getMessage();
        }
       
}


/**
* Get the first result from SELECT sentence on array.
* @param $sql SELECT sentence
* @return the row on associative array.
*/       
function selectSimpleResult($sql){
    
 
    $con = conectar();    
    $stm = $con->prepare($sql);
    $stm->execute();
    $r = $stm->fetch(PDO::FETCH_ASSOC);
    
    $objSend = false;
    if($r && sizeof($r)>0){
        $objSend = $r;
    }
    
    $stm = null;
    $con = null;
    
    return $objSend;
    
}

/**
* Get the all results from SELECT sentence on associative array.
* @param $sql SELECT sentence
* @return the rows on associative array.
*/      
function selectMultipleResults($sql){
    
    $return = false;
    
    $con = conectar();    
    $stm = $con->prepare($sql);
    $stm->execute();
    $results = $stm->fetchAll(PDO::FETCH_ASSOC);
    
    if($results && sizeof($results)>0){
        $return = $results;
    }
    
    return $return;
    
}


/**
* Get a String SELECT sentence parsing array with key-value
* @param $arrayAttr array with 'column' to contents fields to retrive and 'table' to know where search.
* @return the SELECT sentence.
*/       
function getStringSelect($arrayAttr){
    return "SELECT ".$arrayAttr['column']." FROM ".$arrayAttr['table']." ";
}


/**
* Get the first result from SELECT sentence parsing the array result on an instance of class that is needed.
* @param $sql SELECT sentence
* @param $handlerFunction function that parse associative array to instace of class that is needed.
* @return the instace of class.
*/       
function selectSimple($sql,$handlerFunction){
    

    $con = conectar();    
    $stm = $con->prepare($sql);
    $stm->execute();
    $r = $stm->fetch(PDO::FETCH_ASSOC);
    
    
    $objSend = false;
    if($r && sizeof($r)>0){
        $objSend = $handlerFunction($r);
    }
    
    return $objSend;
    
}


/**
* Get the all results from SELECT sentence parsing the array of results on instances of class that is needed.
* @param $sql SELECT sentence
* @param $handlerFunction function that parse associative array to instace of class that is needed.
* @param $id Table field that will use the values to index the array
* @return array with instance of class.
*/    
function selectMultiple($sql,$handlerFunction,$index='id'){
   
    
    $con = conectar();    
    $stm = $con->prepare($sql);
    $stm->execute();
    $results = $stm->fetchAll(PDO::FETCH_ASSOC);
    
    $arraySend = false;
    if($results && sizeof($results)>0){
        $arraySend = array();
        foreach ($results as $key => $r) {
            if(!$index){
                $arraySend[] = $handlerFunction($r);
            }
            else{
                $arraySend[$r[$index]] = $handlerFunction($r);
            }
            
        }
    }
    
    return $arraySend;
    
}



/**
* Execute a INSERT/UPDATE sentence.
* @param $sql INSERT/UPDATE sentence
* @return if was executed.
*/    
function execSql($sql){

    $pass = false;
    $con = conectar();    
    $con->exec($sql);
    $pass = true;
    
    return $pass;
}
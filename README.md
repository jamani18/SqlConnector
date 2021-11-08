# SqlConnector

PHP - File with methods to simpliest connections with Mysql.

Features include methods to parse row to instance of needed class, get rows on array, insert and update on one line and more...

## Install

**Manual**

Just download php/SqlConnector file, and include or require php/SqlConnector on your PHP file:

```php
require_once 'SqlConnector.php';
```

## Requirements

**Server**

Only need a PHP server with version 7 minimum.

## Puesta en marcha

Open the SqlConnector.php file and modify the values of the connection data to the database so that it connects.

## Usage

We have different methods to communicate with the database:

**selectSimpleResults**

Gets the fields requested in a statement wrapped in an associative array. Just capture the first row.

Recommended if we know in advance that the query only returns one row.

```php
/**
* Get the first result from SELECT sentence on array.
* @param $sql SELECT sentence
* @return the row on associative array.
*/       
selectSimpleResult($sql);

//Example
$result = selectSimpleResult("SELECT name FROM client WHERE id='23'");
/*Inspect 
  //$result
  //array("name" => "John");
*/
```

**selectMultipleResults**

Gets the requested fields from all the rows in a statement wrapped in an associative array.

```php
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

//Example
$result = selectMultipleResult("SELECT name FROM client WHERE id>10");
/*Inspect 
  //$result
  //array(
  //array=>('name'=>'John'),
  //array=>('name'=>'Anne'),
  //array=>('name'=>'Robert'));,
*/
```

**execSql**

Execute an INSERT or UPDATE statement passing the statement as a parameter

```php
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
```


**selectSimple**

Performs a query and returns a class instance that corresponds to the table.

For its use, it is necessary to pass a function as the second parameter that is in charge of converting the associative array that results in the class instance.

An example of the handler would be the following:

```php
   
function convertRowToClientClass($r){
    return new Cliente($r['id'],$r['name'],$r['city']);
}
```

The following example uses the method:

```php
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

//Example
selectSimple("SELECT * FROM client WHERE id=456","convertRowToClientClass");
    //Inspect
        //Object Client -> {456,"John","Malaga")
```

**selectMultiple**

Performs a query and returns all results in class instances that correspond to the table.

See the selectSimple method for more details on how it works.

The following example uses the method:

```php

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

//Example
selectMultiple("SELECT * FROM client","convertRowToClientClass","id");
    //Inspect
        //array(
        //Object Client -> {456,"John","Malaga"),
        //Object Client -> {456,"Anne","Madrid"),
        //Object Client -> {456,"Michael","Barcelona"),
        //);
```


**getStringSelect**

Obtains an SQL statement formed by the fields of the array that is passed as a parameter.

The array must contain two keys: column and table. The use is detailed in code.

```php
/**
* Get a String SELECT sentence parsing array with key-value
* @param $arrayAttr array with 'column' to contents fields to retrive and 'table' to know where search.
* @return the SELECT sentence.
*/       
function getStringSelect($arrayAttr){
    return "SELECT ".$arrayAttr['column']." FROM ".$arrayAttr['table']." ";
}


//Example
$param = array('column'=>array('name','city'),'table'=>'client');
$result = getStringSelect($param);
/*Inspect 
  // SELECT name,city FROM client;
*/
```


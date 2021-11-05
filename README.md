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

Abrir el fichero SqlConnector.php y modificar los valores de los datos de conexión a la base de datos para que conecte.

## Usage

Tenemos diferentes métodos para comunicarnos con la base de datos:


**getStringSelect**

Obtiene una sentencia SQL formada por los campos del array que se pasa por parametro.

El array debe contener dos claves: column y table. En código se detalla el uso.

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

**selectSimpleResults**

Obtiene los campos solicitados en una sentencia envueltos en un array asociativo. Solo captura la primera fila.

Recomendado si sabemos con antelación que la consulta solo devuelve una fila.

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

Obtiene los campos solicitados de todas las filas en una sentencia envueltos en un array asociativo.

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




**Parameters**

The function of the parameters is as follows:

**async:** . boolean. Make the connection synchronously or asynchronously.

**serverMethod:** . String. Method to be called on the server.

**sendData:** . Object. Parameters to be sent to the server.

**callBack:** . Function. Method that will be executed when the response from the server arrives.

**onTimeoutCall:** . Function. Method to be executed if the server response times out.

**timeout:** . int.  Time in milliseconds that the server will wait for the response. Default is 10000



## Example

```js
//Send a POST request to server, calling exampleMethod and showing alert message with response. 
//If response time expired, will show an alert message with info.
sendAjaxPost(true,'exampleMethod',{name:'Juan'},function(response){
  alert('Message from server: '+response);
},
function(){
  alert('Expired waiting time');
},5000);
```

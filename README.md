ACM
===

<h1>ACM Page caching</h1>
<p>
cache_file.php is a page caching class. The concept behind page-caching is very simple to understand. The whole idea behind it is to take a generated page that performs many operations such as database calls, looping, etc and store the output somewhere like the file system. This way, the next time someone requests the same page we are able to grab and display the output without having to go through all the trouble of making the same database queries and calculations over and over again.
</p>
<h2>Steps:</h2>
<p>
<ol>
<li>Include cache_file.php at the top of your page</li>
<li>Call the function startCache() from acm class after instaciate it.</li>
<li>Call the function finishCache() from ascm class, at the bottom of your page</li>
<li>Create folder with name "cache" and give it writeable permissions</li>
</ol>
</p>

<h2>Usage example</h2>


```php
<?php
include "cache_file.php";
$cache = new acm();
$cache->startCache();
?>
<!DOCTYPE html>
<html>
<head>
<title>Page title</title>
</head>
<body>
Some database calls, looping, etc
</body>
</html>
<?php
$cache->finishCache();
```


<h1>ACM SQL caching</h1>

<p>
sql_cache.php is a SQL Result caching class. It caches the results from SQL queries in file for defined time, after this time expired, it's ignored and the new SQL Query is needed.
</p>

<h2>Usage example</h2>

```php
$sqlCache = new acm();
$conn = mysqli_connect("localhost","db_user","db_pass","db_name");
$sql = "SELECT * FROM table_name";
$key = md5($sql);
$query = mysqli_query($conn, $sql);
$results = array();

/* Get results from cache if exists */
$cache = $sqlCache->get($key);

/* If doesn't exists make request to DB */
if( $cache===false )
{
    while($row = mysqli_fetch_array($query))
    {
        $results[] = $row;
    }
    echo "FROM SQL: \n\n";
    print_r($results);
    
    /* Write results to cache for 1h 60*60=3600 */
    $sqlCache->set($key, $results, 3600);
}
else
{
    echo "FROM CACHE: \n\n";
    print_r($cache);
}
```

ACM
===

ACM Page caching

cache_file.php is a page caching class. The concept behind page-caching is very simple to understand. The whole idea behind it is to take a generated page that performs many operations such as database calls, looping, etc and store the output somewhere like the file system. This way, the next time someone requests the same page we are able to grab and display the output without having to go through all the trouble of making the same database queries and calculations over and over again.

Steps:
<ol>

<li>1. Include cache_file.php at the top of your page</li>
<li>2. Call the function startCache() from acm class after instaciate it.</li>
<li>3. Call the function finishCache() from ascm class, at the bottom of your page</li>
<li>4. Create folder with name "cache" and give it writeable permissions</li>
</ol>
Usage example

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


ACM SQL caching





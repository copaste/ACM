<?php

/**
* ACM File Based Caching / Cache SQL results
*
* @author Yordan Nikolov 
* @email monnydesign@hotmail.com
*/

class acm
{
    /**
     * Cache Save Dir
     *
     * @var string
     */
    protected   $cache_path = 'cache/';
    
    /**
     * Cache key
     *
     * @var string
     */
    protected   $key;
    
    /**
     * Cache data
     *
     * @var string
     */
    protected   $data;
    
    /**
     * Cache expire time in seconds
     *
     * @var int
     */
    protected   $expires;
    
    /**
     * Set cache data
     *
     * @param string $key
     * @param string $data
     * @param string $expires
     * @return bool
     */
    public function set($key, $data, $expires)
    {
        $this->key = $key;
        $this->data = $data;
        $this->expires = time() + $expires;
        
        return $this->save_cache();
    }
    
    /**
     * Get cached data
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        $filename = 'sql_' . $key . '.php';
        $read = '';
        if(! file_exists($this->cache_path . $filename) )
        {
            return false;
        }
        
        $data = file($this->cache_path . $filename);
        if( $data[1] > time() )
        {
            for($i=2; $i<=count($data)-1; $i++)
            {
                $read .= $data[$i];
            }
            return unserialize($read);
        }
        
        return false;
    }
    
    /**
     * Start time
     *
     * @return int
     */
    public function startTimer() 
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;
        return $starttime;
	} 
	
	/**
     * Return current time
     *
     * @return float
     */
	public function returnTime($starttime) 
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;
        $totaltime = ($endtime - $starttime);
        return $totaltime;
	}
    
	/**
     * Set cache directory
     *
     * @return float
     */
	public function setCacheDir($dir_path) 
    {
        $this->cache_path = $dir_path;
        return $this;
	}
    
    /**
     * Write cache data to file
     *
     * @return bool
     */
    protected function save_cache()
    {
        $filename = 'sql_' . $this->key . '.php';
        $data = serialize($this->data);
        
        if(! is_writable($this->cache_path) )
        {
            trigger_error('Unable to save file within ' . $this->cache_path . '. Please check directory permissions.', E_USER_ERROR);
        }
        
        if ($handle = @fopen($this->cache_path . $filename, 'wb'))
		{
			@flock($handle, LOCK_EX);

			// File header
			fwrite($handle, '<' . '?php exit; ?' . '>');
            fwrite($handle, "\n" . $this->expires . "\n");
        //    fwrite($handle, strlen($data) . "\n");
            fwrite($handle, $data);

			@flock($handle, LOCK_UN);
			fclose($handle);
            
            return true;
        }   
            
        return false;
    }
    
}

$n = new acm();
$time = $n->startTimer();
$conn = mysqli_connect("localhost","root","","copasten_ads");
$sql = "SELECT * FROM ads";// WHERE size='300x250'";
$q = mysqli_query($conn, $sql);
$r = array();
$cache = $n->get(md5($sql));

if( $cache===false )
{
    while($row = mysqli_fetch_array($q))
    {
        $r[] = $row;
    }
    echo "FROM SQL: \n\n";
    print_r($r);
    $n->set(md5($sql), $r, 60);
}
else
{
    echo "FROM CACHE: \n\n";
    print_r($cache);
}
echo $n->returnTime($time);






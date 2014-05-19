<?php

/**
* ACM File Based Caching
* 
* Cache file from output buffer of executed file 
*
* @version 1.0
* @author Yordan Nikolov 
* @email monnydesign@hotmail.com
*/

class cache_file
{

    /**
     * Cache content
     *
     * @var string
     */
    protected $content;
    
    /**
     * Cached file content
     *
     * @var string
     */
    protected $cache_data;
    
    /**
     * Cache filename
     *
     * @var string
     */
    protected $filename;
    
    /**
     * Cache Save directory
     *
     * @var string
     */
    protected $cache_path = 'cache/';
    
    /**
     * Cache file lifetime
     *
     * @var int
     */
    public $ttl = 3600;
    
    /**
     * Class constructor
     *
     * @param string $filename
     */
    public function __construct($filename = null)
    {
        $this->filename = $filename ? $filename : 'file_' . md5($_SERVER['REQUEST_URI']) . '.php';
    }
    
    /**
     * Start cache 
     *
     * The function must be call at the top of the file which has to be cached
     */
    public function startCache()
    {
        ob_start();
        
        if( file_exists($this->cache_path . $this->filename) && !$this->isExpired() )
        {
            if( file_exists($this->cache_path . $this->filename) )
            {
                $this->removeFile();
            }
        }
        
        $this->cache_data = $this->getCache();
        if( $this->cache_data!==false )
        {
            echo $this->cache_data;
            exit;
        }
    }
    
    /**
     * FinishCache
     *
     * The function must be call at the bottom of the file which has to be cached
     *
     * @return mixed
     */
    public function finishCache()
    {
        $this->content = ob_get_contents();
        if( $this->cache_data===false )
        {
            return $this->setCache();
        }
        ob_end_clean();
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
     * Checks if the cached file is expired
     *
     * @return bool
     */
    protected function isExpired()
    {
        $create_date = filemtime($this->cache_path . $this->filename);
      
        if( ($create_date+$this->ttl) > time() )
        {
            return true;
        }
        
        return false;
    }
    
    /**
     * getCache - Get cache file if exists
     *
     * @return mixed
     */
    protected function getCache()
    {
        if( file_exists($this->cache_path . $this->filename) )
        {
            return file_get_contents($this->cache_path . $this->filename);
        }
        return false;
    }
    
    /**
     * Save the cache in file
     *
     * @return bool
     */
    protected function setCache()
    {
        if(! is_writable($this->cache_path) )
        {
            trigger_error('Unable to save file within ' . $this->cache_path . '. Please check directory permissions.', E_USER_ERROR);
        }
        
        return file_put_contents($this->cache_path . $this->filename, $this->content);
    }
    
    /**
     *  Delete old cache
     *
     */
    protected function removeFile()
    {
        return unlink($this->cache_path . $this->filename);
    }
        
    protected function file_content_change()
    {
        return ( strlen($this->cache_data) == strlen($this->content) ) ? true:false;
    }
}
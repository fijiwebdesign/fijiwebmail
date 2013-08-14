<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace Fiji\Cache;

/**
 * Rudementary cache
 * @todo Implement
 */
class File
{
    
    protected $tmpDir = '/tmp';
    
    public function __construct()
    {
        $this->tmpDir = sys_get_temp_dir();
    }
    
    /**
     * Set value to cache
     */
    public function set($name, $value)
    {
        return file_put_contents($this->tmpDir . '/' . md5($name), serialize($value));
    }
    
    /**
     * Get value from cache
     */
    public function get($name)
    {
        return unserialize(file_get_contents($this->tmpDir . '/' . md5($name)));
    }
    
    /**
     * Check existance of value in cache
     */
    public function exists($name)
    {
        return file_exists($this->tmpDir . '/' . md5($name));
    }
    
    /**
     * Remove entry from cache
     */
    public function purge($name)
    {
        return unlink($this->tmpDir . '/' . md5($name));
    }
}
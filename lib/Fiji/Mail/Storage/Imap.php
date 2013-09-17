<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace Fiji\Mail\Storage;

use Zend\Mail\Storage\Imap as ZendImap;
use Zend\Mail\Storage\Exception;
use Fiji\Mail\Protocol;

/**
 * Extend Zend\Mail\Storage\Imap with our own
 */
class Imap extends ZendImap
{
    /**
     * Extend message class with our own
     */
    protected $messageClass = 'Fiji\Mail\Storage\Message';
    
    protected $Cache; 
    
    public $cacheOn = false;
    
    /**
     * Override the ZendImap constructor
     *
     * create instance with parameters
     * Supported parameters are
     *   - user username
     *   - host hostname or ip address of IMAP server [optional, default = 'localhost']
     *   - password password for user 'username' [optional, default = '']
     *   - port port for IMAP server [optional, default = 110]
     *   - ssl 'SSL' or 'TLS' for secure sockets
     *   - folder select this folder [optional, default = 'INBOX']
     *
     * @param  array $params mail reader specific parameters
     * @throws Exception\RuntimeException
     * @throws Exception\InvalidArgumentException
     * @throws \Zend\Mail\Protocol\Exception\RuntimeException
     */
    public function __construct($params)
    {
        if (is_array($params)) {
            $params = (object) $params;
        }

        $this->has['flags'] = true;

        if ($params instanceof Protocol\Imap) {
            $this->protocol = $params;
            try {
                $this->selectFolder('INBOX');
            } catch (Exception\ExceptionInterface $e) {
                throw new Exception\RuntimeException('cannot select INBOX, is this a valid transport?', 0, $e);
            }
            return;
        }

        if (!isset($params->user)) {
            throw new Exception\InvalidArgumentException('need at least user in params');
        }

        $host     = isset($params->host)     ? $params->host     : 'localhost';
        $password = isset($params->password) ? $params->password : '';
        $port     = isset($params->port)     ? $params->port     : null;
        $ssl      = isset($params->ssl)      ? $params->ssl      : false;

        $this->protocol = new Protocol\Imap();
        $this->protocol->connect($host, $port, $ssl);
        if (!$this->protocol->login($params->user, $password)) {
            throw new Exception\RuntimeException('cannot login, user or password wrong');
        }
        $this->selectFolder(isset($params->folder) ? $params->folder : 'INBOX');
    }
    
    /**
     * Cache our getSize
     */
    public function getSize($folder = '')
    {
        // unique cache id
        $cacheId = __CLASS__ . '::' .__FUNCTION__ . '(' . serialize(func_get_args()) . ')';

        return $this->callCache($cacheId, __FUNCTION__, array($folder));
    }
     
     /**
      * Cache a method call
      */
     public function callCache($id, $method, $args)
     {
        $value = false;
        if ($this->cacheOn && $this->Cache->exists($id)) {
            $value = $this->Cache->get($id);
        } else {
            $value = call_user_func_array(array('parent', $method), $args);
            if ($this->cacheOn) {
                $this->Cache->set($id, $value);
            }
        }
        return $value;
     }
     
     /**
     * do a search request
     *
     * @param array $params
     * @return array message ids
     */
     public function search(array $params)
     {
         return $this->protocol->search($params);
     }
     
     /**
     * Checks if a folder exists by name.
     * @param string $folder The name of the folder to check for.
     * @return boolean True if the folder exists, false otherwise.
     * @throws Zend_Mail_Storage_Exception if the current folder cannot be restored.
     */
    function folderExists($folder, $defaultFolder = 'INBOX') {
        $result    = true;
        $oldFolder = $this->getCurrentFolder();
        try {
            $this->selectFolder($folder);
        } catch (\Exception $e) {
            $result = false;
        }
        $this->selectFolder($oldFolder ? $oldFolder : $defaultFolder);
        return $result;
    }
    
    /**
     * unset flags for message
     *
     * @param  int   $id    number of message
     * @param  array $flags new flags for message
     * @throws Exception\RuntimeException
     */
    public function unsetFlags($id, $flags)
    {
        $currFlags = array_values($this->getFlags($id));
        $flags = array_diff($currFlags, $flags);
        
        if ($flags == $currFlags) {
            return false;
        }
        
        if (!$this->protocol->store($flags, $id)) {
            throw new Exception\RuntimeException('cannot set flags, have you tried to set the recent flag or special chars?');
        }
        return true;
    }
    
    /**
     * Add flags to a message
     *
     * @param  int   $id    number of message
     * @param  array $flags new flags for message
     * @throws Exception\RuntimeException
     */
    public function addFlags($id, $flags)
    {
        $currFlags = array_values($this->getFlags($id));
        $flags = array_unique(array_merge($currFlags, $flags));
        if (!$this->protocol->store($flags, $id)) {
            throw new Exception\RuntimeException('cannot set flags, have you tried to set the recent flag or special chars?');
        }
        return true;
    }
    
    /**
     * Get flags for a list of messages
     *
     * @param  int   $from    number of message
     * @param  int   $to      
     * @throws Exception\RuntimeException
     */
    public function getFlags($from, $to = null)
    {
        $data = $this->protocol->fetch('FLAGS', $from, $to);
        return $data;
    }

    public function getAllFlags()
    {
        // all flags for all messages in mailbox
        $flags = $this->getFlags(1, INF);
        // unique flags
        $flags = array_unique(iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($flags))));
        return $flags;
    }
    
    public function getAllLabels()
    {
        // all flags for all messages in mailbox
        $flags = $this->getAllFlags();
        
        foreach($flags as $i => $flag) {
            if (in_array($flag, self::$knownFlags)) {
                unset($flags[$i]);
            }
        }
        return $flags;
        
    }
    
}



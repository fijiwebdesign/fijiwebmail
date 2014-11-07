<?php

namespace config;

/**
 * Service Configuration for in memory data persistence
 *
 */
class Service extends \Fiji\App\Config
{
    public $dataProvider = 'service\\DataProvider\\RedBean\\RedBean';
    /**
     * Use in memory database for testing
     */
    public $dbtype = 'sqlite';
    public $path = ':memory:';
    public $database = 'fiji_webmail';
    public $tablePrefix = 'fiji_';

    public function __construct()
    {
        // so we can view db in dev because :memory" db isn't across session
        ///$this->path = (__DIR__ . '/../.db/test.db');
        //echo "db: " . $this->path . PHP_EOL;
        parent::__construct();
    }

    public function clearStorage()
    {
        if ($this->path !== ':memory:') {
            @unlink($this->path);
        }
    }

}
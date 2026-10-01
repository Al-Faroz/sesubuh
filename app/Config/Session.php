<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Session\Handlers\BaseHandler;
use CodeIgniter\Session\Handlers\FileHandler;
use CodeIgniter\Session\Handlers\DatabaseHandler; // Tambahkan ini

class Session extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Session Driver
     * --------------------------------------------------------------------------
     *
     * Kita ubah menggunakan DatabaseHandler agar tidak ada masalah permission folder.
     *
     * @var class-string<BaseHandler>
     */
    public string $driver = DatabaseHandler::class;

    /**
     * --------------------------------------------------------------------------
     * Session Cookie Name
     * --------------------------------------------------------------------------
     */
    public string $cookieName = 'ci_session';

    /**
     * --------------------------------------------------------------------------
     * Session Expiration
     * --------------------------------------------------------------------------
     */
    public int $expiration = 7200;

    /**
     * --------------------------------------------------------------------------
     * Session Save Path
     * --------------------------------------------------------------------------
     *
     * Karena drivernya 'Database', savePath ini berisi NAMA TABEL.
     * Pastikan tabel 'ci_sessions' sudah dibuat di phpMyAdmin.
     */
    public string $savePath = 'ci_sessions';

    /**
     * --------------------------------------------------------------------------
     * Session Match IP
     * --------------------------------------------------------------------------
     */
    public bool $matchIP = false;

    /**
     * --------------------------------------------------------------------------
     * Session Time to Update
     * --------------------------------------------------------------------------
     */
    public int $timeToUpdate = 300;

    /**
     * --------------------------------------------------------------------------
     * Session Regenerate Destroy
     * --------------------------------------------------------------------------
     */
    public bool $regenerateDestroy = false;

    /**
     * --------------------------------------------------------------------------
     * Session Database Group
     * --------------------------------------------------------------------------
     *
     * DB Group. Biarkan null untuk menggunakan koneksi 'default'.
     */
    public ?string $DBGroup = null;

    /**
     * --------------------------------------------------------------------------
     * Lock Retry Interval (microseconds)
     * --------------------------------------------------------------------------
     */
    public int $lockRetryInterval = 100_000;

    /**
     * --------------------------------------------------------------------------
     * Lock Max Retries
     * --------------------------------------------------------------------------
     */
    public int $lockMaxRetries = 300;
}
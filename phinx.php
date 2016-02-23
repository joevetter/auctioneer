<?php

# parse db credentials
$db_credentials = parse_ini_file(__DIR__ . "/bootstrap/.env");

return ['paths' =>
      [
        'migrations' => '%%PHINX_CONFIG_DIR%%/migrations'
      ],
    'environments' =>
      [
        'default_migration_table' => 'phinxlog',
        'default_database'        => 'development',
        'production' =>
          [
            'adapter' => $db_credentials['DB_DRIVER'],
            'host'    => $db_credentials['DB_HOST'],
            'name'    => $db_credentials['DB_DATABASE'],
            'user'    => $db_credentials['DB_USER'],
            'pass'    => $db_credentials['DB_PASS'],
            'port'    => $db_credentials['DB_PORT'],
            'charset' => $db_credentials['DB_CHARSET'],
          ],
        'development' =>
          [
            'adapter' => $db_credentials['DB_DRIVER'],
            'host'    => $db_credentials['DB_HOST'],
            'name'    => $db_credentials['DB_DATABASE'],
            'user'    => $db_credentials['DB_USER'],
            'pass'    => $db_credentials['DB_PASS'],
            'port'    => $db_credentials['DB_PORT'],
            'charset' => $db_credentials['DB_CHARSET'],
          ],
        'test' =>
          [
            'adapter' => $db_credentials['DB_DRIVER'],
            'host'    => $db_credentials['DB_HOST'],
            'name'    => $db_credentials['DB_DATABASE'],
            'user'    => $db_credentials['DB_USER'],
            'pass'    => $db_credentials['DB_PASS'],
            'port'    => $db_credentials['DB_PORT'],
            'charset' => $db_credentials['DB_CHARSET'],
          ],
      ],
  ];

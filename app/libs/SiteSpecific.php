<?php

class SiteSpecific
{
    public static function getDb() : mysqli
    {
        static $connection = null;

        if ($connection === null)
        {
            $connection = new mysqli(
                getenv('MYSQL_HOST'),
                getenv('MYSQL_USER'),
                getenv('MYSQL_PASSWORD'),
                getenv('MYSQL_DATABASE')
            );
        }

        return $connection;
    }


    public static function getMeiliClient() : \MeiliSearch\Client
    {
        $client = new \MeiliSearch\Client("meilisearch:7700", $_ENV['MEILIKEY']);
        return $client;
    }


    public static function getMimeType(string $filepath) : string
    {
        if (!file_exists($filepath))
        {
            throw new Exception("file does not exist.");
        }

        $finfo = new \finfo(FILEINFO_MIME);

        if (!$finfo)
        {
            throw new Exception("Failed to open fileinfo database.");
        }

        /* get mime-type for a specific file */
        return $finfo->file($filepath);
    }
}



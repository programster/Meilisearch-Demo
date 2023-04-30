<?php



class Init implements iRAP\Migrations\MigrationInterface
{
    public function up(\mysqli $mysqliConn)
    {
        $createTableQuery = "CREATE TABLE `documents` (
            uuid binary(16) NOT NULL,
            name varchar(255) UNIQUE NOT NULL,
            mimetype varchar(255) NOT NULL,
            hash char(64) UNIQUE NOT NULL,
            uploaded_at int NOT NULL,
            PRIMARY KEY (uuid)
        )";

        SiteSpecific::getDb()->query($createTableQuery) or die("Failed to create documents table.");

        $client = SiteSpecific::getMeiliClient();
        $index = $client->createIndex(DOCUMENT_INDEX); // If your index does not exist
    }


    public function down(\mysqli $mysqliConn)
    {
        $createTableQuery = "DROP TABLE `documents`";
        SiteSpecific::getDb()->query($createTableQuery) or die("Failed to drop documents table.");
    }
}


<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Document extends iRAP\MysqlObjects\AbstractUuidTableRowObject
{
    protected string $m_name;
    protected string $m_mimeType;
    protected string $m_hash;
    protected int $m_uploadedAt;


    public function __construct($row, $row_field_types=null)
    {
        $this->initializeFromArray($row, $row_field_types);
    }


    public static function createNew(string $name, string $filepath, ?string $uuid) : Document
    {
        $uuid = $uuid ?? \Ramsey\Uuid\Uuid::uuid4()->toString();
        $mimeType =  SiteSpecific::getMimeType($filepath);
        $hash = hash_file('sha256', $filepath);
        $uploadedAt = time();

        $document = new Document(array(
            'uuid' => $uuid,
            'mimetype' => $mimeType,
            'name' => $name,
            'hash' => $hash,
            'uploaded_at' => $uploadedAt
        ));

        $document->save();
        return $document;
    }


    protected function getAccessorFunctions()
    {
        return array(
            'name' => function() { return $this->m_name; },
            'mimetype' => function() { return $this->m_mimeType; },
            'hash' => function() { return $this->m_hash; },
            'uploaded_at' => function() { return $this->m_uploadedAt; },
        );
    }


    protected function getSetFunctions()
    {
        return array(
            'name' => function($x) { $this->m_name = $x; },
            'mimetype' => function($x) { $this->m_mimeType = $x; },
            'hash' => function($x) { $this->m_hash = $x; },
            'uploaded_at' => function($x) { $this->m_uploadedAt = $x; },
        );
    }


    public function getTableHandler(): \iRAP\MysqlObjects\TableInterface
    {
        return new DocumentTable();
    }


    # Accessors
    public function getUuid() : string { return $this->m_uuid; }
    public function getName() : string { return $this->m_name; }
    public function getMimeType() : string { return $this->m_mimeType; }
    public function getHash() : string { return $this->m_hash; }
    public function getUploadedAt() : int { return $this->m_uploadedAt; }
}


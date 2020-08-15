<?php


class DocumentTable extends iRAP\MysqlObjects\AbstractUuidTable
{
    public function getDb(): \mysqli { return SiteSpecific::getDb(); }
    public function getFieldsThatAllowNull() { return array(); }
    public function getFieldsThatHaveDefaults() { return array(); }
    public function getObjectClassName() { return Document::class; }
    public function getTableName() { return "documents"; }
    public function validateInputs(array $data): array { return $data; }


    public function doesFileExist(string $filepath) : bool
    {
        $hash = hash_file('sha256', $filepath);

        try
        {
            DocumentTable::findByHash($hash);
            $result = true;
        }
        catch (Exception $ex)
        {
            $result = false;
        }

        return $result;
    }


    public function findByHash(string $hash) : Document
    {
        $documents = $this->loadWhereAnd(['hash' => $hash]);

        if (count($documents) === 0)
        {
            throw new Exception("Failed to find document by hash");
        }

        return \Programster\CoreLibs\ArrayLib::getFirstElement($documents);
    }


    public function findByName(string $name)
    {
        $documents = $this->loadWhereAnd(['name' => $name]);

        if (count($documents) === 0)
        {
            throw new Exception("Failed to find document by hash");
        }

        return \Programster\CoreLibs\ArrayLib::getFirstElement($documents);
    }
}


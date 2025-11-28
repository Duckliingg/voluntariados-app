<?php
// app/Models/Mongo/BaseModel.php

require_once __DIR__ . '/../../Core/MongoDatabase.php';

class BaseModel {
    protected $collection;
    protected $collectionName;
    
    public function __construct($collectionName) {
        $this->collectionName = $collectionName;
        $this->collection = MongoDatabase::getCollection($collectionName);
    }
    
    protected function toObjectId($id) {
        if ($id instanceof MongoDB\BSON\ObjectId) {
            return $id;
        }
        try {
            return new MongoDB\BSON\ObjectId($id);
        } catch (Exception $e) {
            throw new Exception("ID inválido: " . $id);
        }
    }
    
    protected function toString($objectId) {
        return (string) $objectId;
    }
    
    protected function withTimestamps($data) {
        $now = new MongoDB\BSON\UTCDateTime();
        
        if (!isset($data['_id'])) {
            $data['created_at'] = $now;
        }
        
        $data['updated_at'] = $now;
        return $data;
    }
}
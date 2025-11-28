<?php
use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;

class InsigniaRepository
{
    private $collection;

    public function __construct()
    {
        $client = new Client($_ENV['MONGO_URI'] ?? 'mongodb://localhost:27017');
        $this->collection = $client->voluntariados->insignias;
    }

    public function getByEstudiante(int $estudianteId): array
    {
        $doc = $this->collection->findOne(['estudiante_id' => $estudianteId]);
        return $doc ? iterator_to_array($doc['insignias'] ?? []) : [];
    }

    public function save(int $estudianteId, array $insignias): void
    {
        $this->collection->replaceOne(
            ['estudiante_id' => $estudianteId],
            [
                'estudiante_id' => $estudianteId,
                'insignias'     => $insignias,
                'updated_at'    => new UTCDateTime(),
            ],
            ['upsert' => true]
        );
    }
}
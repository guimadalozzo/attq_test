<?php

namespace App\Http\Responses\Transformers;

use App\Models\Document;
use League\Fractal\TransformerAbstract;

class DocumentsTransformer extends TransformerAbstract
{
    public function transform(Document $document)
    {
        return [
            'id' => (string) $document->id,
            'title' => $document->title,
            'file_name' => $document->file_name,
            'created_at' => $document->created_at ?? null,
            'updated_at' => $document->updated_at ?? null,
            'links' => [
                'self' => '/documents/' . $document->id,
            ]
        ];
    }
}

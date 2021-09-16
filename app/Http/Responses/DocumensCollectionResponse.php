<?php

namespace App\Http\Responses;

use App\Http\Responses\Transformers\DocumentsTransformer;
use App\Models\Document;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;

class DocumensCollectionResponse extends Response
{
    /**
     * @param Document[]|\Illuminate\Support\Collection $medias
     * @param LengthAwarePaginator $paginator
     */
    public function __construct($medias, $paginator)
    {
        $fractal = (new Manager())->setSerializer(new JsonApiSerializer());
        $resource = new Collection($medias, new DocumentsTransformer(), 'Documents');
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return parent::__construct($fractal->createData($resource)->toArray());
    }
}

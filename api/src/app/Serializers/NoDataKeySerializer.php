<?php

namespace App\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class NoDataKeySerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        return ($resourceKey && $resourceKey !== 'data') ? array($resourceKey => $data) : $data;
    }

    public function item($resourceKey, array $data)
    {
        return ($resourceKey && $resourceKey !== 'data') ? array($resourceKey => $data) : $data;
    }
}

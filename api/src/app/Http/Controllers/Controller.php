<?php

namespace App\Http\Controllers;

use App\Http\Response\FractalResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use League\Fractal\TransformerAbstract;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version=L5_SWAGGER_DOCS_VERSION,
 *         title=L5_SWAGGER_CONST_TITLE,
 *         description=L5_SWAGGER_CONST_DESCRIPTION,
 *     )
 * )
 */
/**
 *  @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST_V1,
 *      description="L5 Swagger OpenApi dynamic host server"
 *  )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var FractalResponse
     */
    private $fractal;

    public function __construct(FractalResponse $fractal)
    {
        $this->fractal = $fractal;
        $this->fractal->parseIncludes();
    }

    /**
     * @param $data
     * @param TransformerAbstract $transformer
     * @param null $resourceKey
     * @return array
     */
    public function item($data, TransformerAbstract $transformer, $resourceKey = null)
    {
        return $this->fractal->item($data, $transformer, $resourceKey);
    }

    /**
     * @param $data
     * @param TransformerAbstract $transformer
     * @param null $resourceKey
     * @return array
     */
    public function collection($data, TransformerAbstract $transformer, $resourceKey = null)
    {
        return $this->fractal->collection($data, $transformer, $resourceKey);
    }
}

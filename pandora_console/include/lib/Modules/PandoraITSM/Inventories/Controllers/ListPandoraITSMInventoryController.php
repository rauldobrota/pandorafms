<?php

namespace PandoraFMS\Modules\PandoraITSM\Inventories\Controllers;

use PandoraFMS\Modules\PandoraITSM\Inventories\Actions\ListPandoraITSMInventoryAction;
use PandoraFMS\Modules\PandoraITSM\Inventories\Entities\PandoraITSMInventoryFilter;
use PandoraFMS\Modules\Shared\Controllers\Controller;
use PandoraFMS\Modules\Shared\Services\ValidateAclSystem;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ListPandoraITSMInventoryController extends Controller
{


    public function __construct(
        private ListPandoraITSMInventoryAction $listPandoraITSMInventoryAction,
        private ValidateAclSystem $acl,
    ) {
    }


    /**
     * @OA\Post(
     *   security={{ "bearerAuth": {}}},
     *   tags={"PandoraITSM"},
     *   path="/pandoraITSM/inventory/list",
     *   summary="List pandoraITSMInventories",
     * @OA\Parameter(ref="#/components/parameters/parameterPage"),
     * @OA\Parameter(ref="#/components/parameters/parameterSizePage"),
     * @OA\Parameter(ref="#/components/parameters/parameterSortField"),
     * @OA\Parameter(ref="#/components/parameters/parameterSortDirection"),
     * @OA\RequestBody(ref="#/components/requestBodies/requestBodyPandoraITSMInventoryFilter"),
     * @OA\Response(
     *     response="200",
     *     description="List PandoraITSM Inventories Object",
     *     content={
     * @OA\MediaType(
     *         mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(
     *             property="paginationData",
     *             type="object",
     *             ref="#/components/schemas/paginationData",
     *             description="Page object",
     *           ),
     * @OA\Property(
     *             property="data",
     *             type="array",
     * @OA\Items(
     *               ref="#/components/schemas/PandoraITSMInventory",
     *               description="Array of pandoraITSMInventory objects"
     *             )
     *           ),
     *         ),
     *       )
     *     }
     *   ),
     * @OA\Response(response=400,                                                               ref="#/components/responses/BadRequest"),
     * @OA\Response(response=401,                                                               ref="#/components/responses/Unauthorized"),
     * @OA\Response(response=403,                                                               ref="#/components/responses/Forbidden"),
     * @OA\Response(response=404,                                                               ref="#/components/responses/NotFound"),
     * @OA\Response(response=500,                                                               ref="#/components/responses/InternalServerError")
     *  )
     */
    public function __invoke(Request $request, Response $response): Response
    {
        // @var PandoraITSMInventoryFilter $pandoraITSMInventoryFilter.
        $pandoraITSMInventoryFilter = $this->fromRequest($request, PandoraITSMInventoryFilter::class);

        $this->acl->validate(0, 'AR', ' tried to read agents for pandoraITSMInventories');

        $result = $this->listPandoraITSMInventoryAction->__invoke($pandoraITSMInventoryFilter);
        return $this->getResponse($response, $result);
    }


}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class CandidatoController extends Controller
{
    protected $action;

    public function __construct()
    {
        //$this->middleware('auth:api');
        //return response()->json([ 'valid' => auth()->check() ]);

        $this->action = $this->allowedAction();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     * @OA\Post (
     *     path="/api/lead",
     *     operationId="createLead",
     *     tags={"Lead"},
     *     summary="Add Lead",
     *     description="Add Lead",
     *     @OA\RequestBody(
     *          description="Lead object that needs to be created",
     *          required=true,
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name"
     *                 ),
     *                 @OA\Property(
     *                      property="source",
     *                      type="string",
     *                      description="Source"
     *                 ),
     *                 @OA\Property(
     *                      property="owner",
     *                      type="int",
     *                      description="Owner"
     *                 ),
     *             )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(Request $request)
    {

        if($this->action)
            return response()->json($this->action,
                Response::HTTP_UNAUTHORIZED);

        if(!$this->isManager())
        {
            return response()->json([
                "meta" => [
                    "success" => false,
                    "errors" => ['No lead found']
                ]
            ], Response::HTTP_UNAUTHORIZED);

        }

        //validamos los datos
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:100',
            'source' => 'required|string|max:100',
            'owner'    => 'required'
        ]);

        if($validator->fails())
            return response()->json([
                "meta" => [
                    "success" => false,
                    "errors" => $validator->errors()->toArray()
                ]
            ], Response::HTTP_BAD_REQUEST);
        //$validator->validate();


        $candidato = new Candidato();

        $candidato->name   = $request->name;
        $candidato->source = $request->source;
        $candidato->owner   = $request->owner;
        $candidato->created_by = auth()->user()->getAuthIdentifier();

        $candidato->save();

        return response()->json(["meta" => [
                                        "success" => true,
                                        "errors" => []
                                    ],
                                "data" => [
                                    "id" => $candidato->_id,
                                    "name" => $candidato->name,
                                    "source" => $candidato->source,
                                    "owner" => $candidato->owner,
                                    "created_at" => $candidato->created_at,
                                    "created_by" => $candidato->created_by
                                ]
                ], Response::HTTP_CREATED);

    }

    /**
     * Display a listing of the resource.
     * Show all.
     * @return Response
     *
     * @OA\Get(
     *     path="/api/leads",
     *     operationId="getLeadsList",
     *     tags={"Lead"},
     *     summary="Show all leads",
     *     description="Return all leads",
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     * )
     */
    public function list()
    {
        if($this->action)
            return response()->json($this->action,
                Response::HTTP_UNAUTHORIZED);

        if($this->isManager())  $candidatos = Candidato::all();
        else $candidatos = Candidato::all()->where('owner', auth()->user()->getAuthIdentifier());

            return response()->json([
                            "meta" => [
                                "success" => true,
                                "role" => $this->isManager(),
                                "errors" => []
                            ],
                            "data" => $candidatos
                        ], Response::HTTP_OK);
    }

    /**
     * Display the specified leed.
     * @param  string  $id
     * @return Response
     * @OA\Get(
     *     path="/api/lead/{id}",
     *     operationId="searchLeadById",
     *     tags={"Lead"},
     *     summary="Show by ID",
     *     description="Retorn lead",
     *     @OA\Parameter(
     *         description="ID Lead",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="6372930455a84eb00e0e5852", summary="ID Lead")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     ),
     * )
     */
    public function searchById($id)
    {
        if($this->action)
            return response()->json($this->action,
                Response::HTTP_UNAUTHORIZED);

        try{
            $candidato = Candidato::findOrFail($id);

            return response()->json(["meta" => [
                "success" => true,
                "errors" => []
            ],
                "data" => [
                    "id" => $candidato->_id,
                    "name" => $candidato->name,
                    "source" => $candidato->source,
                    "owner" => $candidato->owner,
                    "created_at" => $candidato->created_at,
                    "created_by" => $candidato->created_by
                ]
            ], Response::HTTP_OK);
        }catch (ModelNotFoundException $e)
        {
            return response()->json([
                "meta" => [
                    "success" => false,
                    "errors" => ['No lead found']
                ]
            ], Response::HTTP_NOT_FOUND);
        }

    }

    protected function allowedAction()
    {
        if(!auth()->check()){
            return [
                "meta" => [
                    "success" => false,
                    "errors" => ['Token expired']
                ]
            ];
        }

        return false;
    }

    protected function isManager()
    {
        return auth()->user()->role  === 'manager' ? TRUE : FALSE;

    }
}

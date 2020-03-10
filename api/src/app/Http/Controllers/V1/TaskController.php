<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiException;
use App\Exceptions\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Response\FractalResponse;
use App\Task;
use App\Transformers\StatusResponseTransformer;
use App\Transformers\TaskTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * @var User
     */
    protected User $user;


    public function __construct(FractalResponse $fractal)
    {
        parent::__construct($fractal);
        if(auth()->user()) {
            $this->user = auth()->user();
        }
    }

    /**
     * @OA\Get(
     *     path="/tasks",
     *     operationId="index",
     *     tags={"Tasks"},
     *     description="Get Tasks",
     *     @OA\Response(
     *         response="200",
     *         description="Tasks",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Task")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: Unauthorized.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Error: Forbidden.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Error: Bad request.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     security={
     *         {"api_token": {}}
     *     }
     * )
     */
    public function index()
    {
        $tasks = Task::special($this->user->id, $this->user->admin)->get();

        return $this->collection($tasks, new TaskTransformer());
    }

    /**
     *
     * Get Task By Id
     *
     * @OA\Get(
     *     path="/tasks/{id}",
     *     operationId="show",
     *     tags={"Tasks"},
     *     description="Get Task By Id",
     *     @OA\Parameter(
     *        name="id",
     *        required=true,
     *        in="path",
     *        description="Task Id",
     *        @OA\Schema(
     *          type="integer"
     *        )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Return the task information.",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: Unauthorized.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Error: Forbidden.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: Data Not Found.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     security={
     *         {"api_token": {}}
     *     }
     * )
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $task = Task::special($this->user->id, $this->user->admin)->findOrFail($id);

        return response()
            ->json($this->item($task, new TaskTransformer()));
    }

    /**
     *
     * Delete task
     *
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     operationId="destroy",
     *     tags={"Tasks"},
     *     description="Delete Task",
     *     @OA\Parameter(
     *        name="id",
     *        required=true,
     *        in="path",
     *        description="Task Id",
     *        @OA\Schema(
     *          type="integer"
     *        )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Return the status.",
     *         @OA\JsonContent(ref="#/components/schemas/Status")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: Unauthorized.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Error: Forbidden.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: Data Not Found.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     security={
     *         {"api_token": {}}
     *     }
     * )
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function destroy($id)
    {
        if (!$this->user->admin) {
            throw new ApiException(Error::FORBIDDEN, Response::HTTP_FORBIDDEN);
        }

        Task::find($id)->delete();

        return response()
            ->json($this->item(true, new StatusResponseTransformer()));
    }

    /**
     *
     * Create a Task
     *
     * @OA\Post(
     *     path="/tasks",
     *     operationId="store",
     *     tags={"Tasks"},
     *     description="Create a Task",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Task Title",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Task Description",
     *                 ),
     *                 example={
     *                     "title": "Title",
     *                     "description": "Description",
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Return the task information.",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: Unauthorized.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Error: Forbidden.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: Data Not Found.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Error: Validation.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     security={
     *         {"api_token": {}}
     *     }
     * )
     * @param CreateTaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function store(CreateTaskRequest $request)
    {
        if ($this->user->admin) {
            throw new ApiException(Error::FORBIDDEN, Response::HTTP_FORBIDDEN);
        }

        $task = Task::create([
            'user_id' => $this->user->id,
            'title' => $request->title,
            'description' => $request->description
        ]);

        return response()
            ->json($this->item($task, new TaskTransformer()));
    }

    /**
     *
     * Update a Task
     *
     * @OA\Put(
     *     path="/tasks/{id}",
     *     operationId="update",
     *     tags={"Tasks"},
     *     description="Update a Task",
     *     @OA\Parameter(
     *        name="id",
     *        required=true,
     *        in="path",
     *        description="Task Id",
     *        @OA\Schema(
     *          type="integer"
     *        )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Task Title",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Task Description",
     *                 ),
     *                 example={
     *                     "title": "Title",
     *                     "description": "Description",
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Return the task information.",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: Unauthorized.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Error: Forbidden.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: Data Not Found.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Error: Validation.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     security={
     *         {"api_token": {}}
     *     }
     * )
     * @param UpdateTaskRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        if ($this->user->admin) {
            throw new ApiException(Error::FORBIDDEN, Response::HTTP_FORBIDDEN);
        }

        $task = Task::updateOrCreate(
            ['id' => $id],
            ['title' => $request->title, 'description' => $request->description]
        );

        return response()
            ->json($this->item($task, new TaskTransformer()));
    }
}

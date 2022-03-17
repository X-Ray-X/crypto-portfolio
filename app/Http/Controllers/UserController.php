<?php

namespace App\Http\Controllers;

use App\Exceptions\RepositoryOperationException;
use App\Helpers\HttpResponse;
use App\Repositories\UserRepositoryInterface;
use App\Rules\PhoneNumber;
use App\Transformers\CreatedTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @param Factory $validatorFactory
     * @return JsonResponse
     */
    public function create(Request $request, Factory $validatorFactory): JsonResponse
    {
        $validator = $validatorFactory->make(
            $request->all(),
            [
                'username' => ['required', 'alpha_dash', 'unique:users'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', Password::min(8)->numbers()->symbols()],
                'phoneNumber' => ['sometimes', 'required', new PhoneNumber()],
                'firstName' => ['sometimes', 'required', 'string'],
                'lastName' => ['sometimes', 'required', 'string'],
            ],
        );

        if ($validator->fails()) {
            return HttpResponse::withArray(Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors()->messages());
        }

        try {
            $user = $this->userRepository->create($request->toArray());
        } catch (\Exception $exception) {
            return HttpResponse::withArray(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['error' => $exception->getMessage()]
            );
        }

        return HttpResponse::withArray(Response::HTTP_CREATED, (new CreatedTransformer())->transform($user));
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function get(string $id): JsonResponse
    {
        try {
            $user = $this->userRepository->getUserById($id);
        } catch (ModelNotFoundException $exception) {
            return HttpResponse::withArray(
                Response::HTTP_NOT_FOUND,
                ['error' => sprintf('The user with ID: %s does not exist.', $id)]
            );
        }

        return HttpResponse::withArray(Response::HTTP_OK, (new UserTransformer())->transform($user));
    }

    /**
     * @param Request $request
     * @param string $id
     * @param Factory $validatorFactory
     * @return JsonResponse
     */
    public function update(Request $request, string $id, Factory $validatorFactory): JsonResponse
    {
        $validator = $validatorFactory->make(
            $request->all(),
            [
                'email' => ['sometimes', 'required', 'email', 'unique:users'],
                'password' => ['sometimes', 'required', Password::min(8)->numbers()->symbols()],
                'phoneNumber' => ['sometimes', 'required', new PhoneNumber()],
                'firstName' => ['sometimes', 'required', 'string'],
                'lastName' => ['sometimes', 'required', 'string'],
            ],
        );

        try {
            if (!$this->userRepository->update($id, $validator->validated())) {
                return HttpResponse::withArray(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    ['error' => 'The system could not process your request properly. Please try again later.']
                );
            }

            return HttpResponse::withArray(Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return HttpResponse::withArray(Response::HTTP_NOT_FOUND, ['error' => sprintf('The user with ID: %s does not exist.', $id)]);
        } catch (ValidationException $exception) {
            return HttpResponse::withArray(Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors()->messages());
        }
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function delete(string $id): JsonResponse
    {
        try {
            if (!$this->userRepository->delete($id)) {
                return HttpResponse::withArray(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    ['error' => 'The system could not process your request properly. Please try again later.']
                );
            }

            return HttpResponse::withoutBody(Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $exception) {
            return HttpResponse::withArray(Response::HTTP_NOT_FOUND, ['error' => sprintf('The user with ID: %s does not exist.', $id)]);
        }
    }

    public function createApiKey(Request $request): JsonResponse
    {
        $this->validate($request, [
            'username' => ['required'],
            'password' => ['required'],
        ]);

        try {
            $user = $this->userRepository->getUserByUsername($request['username']);
        } catch (ModelNotFoundException $exception) {
            return HttpResponse::withArray(Response::HTTP_NOT_FOUND, ['error' => 'User account does not exist.']);
        }

        if ($request['password'] === Crypt::decryptString($user->password)) {
            try {
                $apiKey = $this->userRepository->createUserApiKey($user);
            } catch (RepositoryOperationException $exception) {
                return HttpResponse::withArray(Response::HTTP_INTERNAL_SERVER_ERROR, ['error' => $exception->getMessage()]);
            }

            return HttpResponse::withArray(Response::HTTP_OK, [
                'message' => 'Please save this API Key and use it as your api-key authorization header for future requests.',
                'apiKey' => $apiKey,
            ]);
        }

        return HttpResponse::withArray(Response::HTTP_UNAUTHORIZED, ['error' => 'The user credentials do not match.']);
    }
}

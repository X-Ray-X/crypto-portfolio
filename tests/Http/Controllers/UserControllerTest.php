<?php

namespace Http\Controllers;

use App\Http\Controllers\UserController;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Transformers\CreatedTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends \TestCase
{
    protected MockInterface|LegacyMockInterface $userRepoMock;
    protected UserController $controller;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->userRepoMock = Mockery::mock(UserRepositoryInterface::class);
        $this->controller = new UserController($this->userRepoMock);
    }

    /**
     * @covers UserController::create
     *
     * @dataProvider userProvider
     *
     * @param $requestData
     * @return void
     */
    public function testCreate($requestData): void
    {
        $user = new User([
            'id' => Str::uuid()->toString(),
            'username' => $requestData['username'],
            'email' => $requestData['email'],
            'password' => $requestData['password'],
            'phoneNumber' => $requestData['phoneNumber'],
            'firstName' => $requestData['firstName'],
            'lastName' => $requestData['lastName'],
        ]);

        $this->userRepoMock->expects('create')->once()->with($requestData)->andReturn($user);

        $validatorFactoryMock = Mockery::mock(Factory::class);
        $validatorMock = Mockery::mock(Validator::class);

        $validatorFactoryMock->expects('make')->once()->withAnyArgs()->andReturn($validatorMock);
        $validatorMock->expects('fails')->once()->andReturnFalse();

        $request = new Request([], $requestData);
        $request->setMethod(Request::METHOD_POST);

        $response = $this->controller->create($request, $validatorFactoryMock);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals((new CreatedTransformer())->transform($user), $response->getOriginalContent());
    }

    /**
     * @return \string[][][]
     */
    public function userProvider(): array
    {
        return [
            [
                [
                  'username' => 'test-user',
                  'email' => 'test@example.com',
                  'password' => 'P@ssw0rd!',
                  'phoneNumber' => '+31642531978',
                  'firstName' => 'John',
                  'lastName' => 'Doe',
                ],
            ],
        ];
    }

    /**
     * @covers UserController::get
     *
     * @return void
     */
    public function testGet(): void
    {
        $id = Str::uuid()->toString();

        $user = new User([
            'id' => $id,
            'username' => 'test-user',
            'email' => 'test@example.com',
        ]);

        $this->userRepoMock->expects('getUserById')->once()->with($id)->andReturn($user);

        $response = $this->controller->get($id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals((new UserTransformer())->transform($user), $response->getOriginalContent());
    }

    /**
     * @covers UserController::update
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $id = Str::uuid()->toString();

        $request = new Request([], [
            'firstName' => 'John',
            'lastName' => 'Doe',
        ]);
        $request->setMethod(Request::METHOD_PUT);

        $validatorFactoryMock = Mockery::mock(Factory::class);
        $validatorMock = Mockery::mock(Validator::class);

        $validatorFactoryMock->expects('make')->once()->withAnyArgs()->andReturn($validatorMock);
        $validatorMock->expects('validated')->once()->andReturn($request->toArray());

        $this->userRepoMock->expects('update')->once()->withArgs([$id, $request->toArray()])->andReturnTrue();

        $response = $this->controller->update($request, $id, $validatorFactoryMock);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @covers UserController::delete
     * @return void
     */
    public function testDelete(): void
    {
        $id = Str::uuid()->toString();

        $this->userRepoMock->expects('delete')->once()->with($id)->andReturnTrue();

        $response = $this->controller->delete($id);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
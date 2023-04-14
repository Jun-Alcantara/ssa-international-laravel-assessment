<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_return_a_paginated_list_of_users(): void
    {
        $totalUsers = 20;
        $perPage = 10;
        User::factory($totalUsers)->create();

        $userService = new UserService(new User, new Request);
        $userList = $userService->list($perPage);

        $this->assertInstanceOf(LengthAwarePaginator::class, $userList);
        $this->assertEquals($totalUsers, $userList->total());
        $this->assertEquals($perPage, $userList->perPage());
    }

    public function test_it_can_store_a_user_to_database(): void
    {
        $firstname = fake()->firstName();
        $lastname = fake()->lastName();
        $photoUrl = "https://ui-avatars.com/api/?name={$firstname}+{$lastname}";

        $attributes = [
            'prefixname' => collect(['Mr.', 'Mrs.', 'Ms'])->random(),
            'firstname' => $firstname,
            'middlename' => fake()->lastName(),
            'lastname' => $lastname,
            'suffixname' => collect(['JR', 'SR', NULL])->random(),
            'username' => strtolower("{$firstname}{$lastname}"),
            'email' => strtolower("{$firstname}{$lastname}@example.com"),
            'password' => 'password',
            'photo' => $photoUrl,
            'type' => 'user'
        ];

        $userService = new UserService(new User, new Request);
        $userService->store($attributes);

        unset($attributes['password']);

        $this->assertDatabaseHas('users', $attributes);
    }

    public function test_it_can_find_and_return_an_existing_user(): void
    {
        $user = User::factory()->create();

        $userService = new UserService(new User, new Request);
        $resultUser = $userService->find($user->id);

        $this->assertInstanceOf(User::class, $resultUser);
        $this->assertEquals($resultUser->id, $user->id);
    }

    public function test_it_can_update_an_existing_user(): void
    {
        $user = User::factory()->create();

        $updatedUserData = [
            'prefixname' => 'Ms',
            'firstname' => 'Updated Firstname',
            'middlename' => 'Updated Middlename',
            'lastname' => 'Updated Lastname',
            'suffixname' => 'Jr',
            'username' => 'updatedusername000',
            'email' => 'updatedemail@example.com',
            'photo' => 'updatedphotourl.com',
            'type' => 'newtype'
        ];

        $userService = new UserService(new User, new Request);
        $updateResult = $userService->update($user->id, $updatedUserData);
        $updatedUser = User::find($user->id);

        // dd($updatedUser->toArray());

        $this->assertTrue($updateResult);
        $this->assertEquals($updatedUserData['prefixname'], $updatedUser->prefixname);
        $this->assertEquals($updatedUserData['firstname'], $updatedUser->firstname);
        $this->assertEquals($updatedUserData['middlename'], $updatedUser->middlename);
        $this->assertEquals($updatedUserData['lastname'], $updatedUser->lastname);
        $this->assertEquals($updatedUserData['suffixname'], $updatedUser->suffixname);
        $this->assertEquals($updatedUserData['username'], $updatedUser->username);
        $this->assertEquals($updatedUserData['email'], $updatedUser->email);
        $this->assertEquals($updatedUserData['photo'], $updatedUser->photo);
        $this->assertEquals($updatedUserData['type'], $updatedUser->type);
    }

    public function test_it_can_soft_delete_an_existing_user()
    {
        $user = User::factory()->create();

        $userService = new UserService(new User, new Request);
        $userService->deactivate($user->id);

        $deletedUser = User::onlyTrashed()->find($user->id);
        $this->assertNotEmpty($deletedUser);
        $this->assertEquals($user->id, $deletedUser->id);
        $this->assertNotNull($deletedUser->deleted_at);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_trashed_users()
    {
        User::factory(25)->create();

        $toDeleteCount = 10;
        $perPage = 10;
        $toDeleteUsers = User::inRandomOrder()->take($toDeleteCount)->get();
        $expectedDeletedIds = $toDeleteUsers->pluck('id')->toArray();
        User::whereIn('id', $expectedDeletedIds)->delete();

        $userService = new UserService(new User, new Request);
        $trashedUsers = $userService->listTrashed($perPage);
        $trashedUserIds = $trashedUsers->pluck('id')->toArray();

        $this->assertInstanceOf(LengthAwarePaginator::class, $trashedUsers);
        $this->assertEquals($toDeleteCount, $trashedUsers->total());
        $this->assertEquals($perPage, $trashedUsers->perPage());
        $this->assertEqualsCanonicalizing($expectedDeletedIds, $trashedUserIds);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_restore_a_soft_deleted_user()
    {
        $user = User::factory()->create();

        $userService = new UserService(new User, new Request);
        $userService->deactivate($user->id);

        $deletedUser = User::onlyTrashed()
            ->find($user->id);

        $this->assertInstanceOf(User::class, $deletedUser);
        $this->assertNotNull($deletedUser->deleted_at);

        $userService->restore($deletedUser->id);
        $restoredUser = $userService->find($deletedUser->id);

        $this->assertInstanceOf(User::class, $restoredUser);
        $this->assertNull($restoredUser->deleted_at);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {
        $user = User::factory()->create();

        $userService = new UserService(new User, new Request);
        $userService->deactivate($user->id);
        $userService->delete($user->id);
        $deletedUser = $userService->find($user->id);

        $this->assertNull($deletedUser);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_upload_photo()
    {
        $file = UploadedFile::fake()->image('photo.jpg');

        $userService = new UserService(new User, new Request);
        $path = $userService->upload($file);

        Storage::disk('public')->assertExists($path);
    }
}

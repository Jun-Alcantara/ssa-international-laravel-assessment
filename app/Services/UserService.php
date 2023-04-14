<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

class UserService implements UserServiceInterface
{
    /**
     * The model instance.
     *
     * @var App\User
     */
    protected $model;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Constructor to bind model to a repository.
     *
     * @param \App\User                $model
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(User $model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
    }

    /**
     * Define the validation rules for the model.
     *
     * @param  int $id
     * @return array
     */
    public function rules($request)
    {
        $rules = [
            "prefixname" => "string|nullable",
            "firstname" => "required",
            "middlename" => "string|nullable",
            "lastname" => "required",
            "suffixname" => "string|nullable",
            "username" => ['required', 'max:255', Rule::unique('users')],
            "email" => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            "photo" => "max:10240",
        ];

        if (strtoupper($request->method()) === "POST") {
            $rules['password'] = "required|confirmed|min:8";
        }

        if (strtoupper($request->method()) === "PUT") {
            $user = $request->user;

            $rules['username'] = ['required', 'max:255', Rule::unique('users')->ignore($request->user->id)];
            $rules['email'] = ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)];

            if ($request->filled('old_password')) {
                $rules['old_password'] = "required|min:8";
                $rules['password'] = "required|confirmed|min:8";
            }
        }

        return $rules;
    }

    /**
     * Retrieve all resources and paginate.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list($perPage = 10)
    {
        return User::paginate($perPage);
    }

    /**
     * Create model resource.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $attributes)
    {
        return User::create([
            'prefixname' => $attributes['prefixname'],
            'firstname' => $attributes['firstname'],
            'middlename' => $attributes['middlename'],
            'lastname' => $attributes['lastname'],
            'suffixname' => $attributes['suffixname'],
            'username' => $attributes['username'],
            'email' => $attributes['email'],
            'password' => $this->hash($attributes['password']),
            'photo' => $attributes['photo'],
            'type' => 'user'
        ]);
    }

    /**
     * Retrieve model resource details.
     * Abort to 404 if not found.
     *
     * @param  integer $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id):? Model
    {
        return User::find($id);
    }

    /**
     * Update model resource.
     *
     * @param  integer $id
     * @param  array   $attributes
     * @return boolean
     */
    public function update(int $id, array $attributes): bool
    {
        $user = User::findOrFail($id);

        $updatedUserDetails = [
            'prefixname' => $attributes['prefixname'] ?? $user->prefixname,
            'firstname' => $attributes['firstname'] ?? $user->firstname,
            'middlename' => $attributes['middlename'] ?? $user->middlename,
            'lastname' => $attributes['lastname'] ?? $user->lastname,
            'suffixname' => $attributes['suffixname'] ?? $user->suffixname,
            'username' => $attributes['username'] ?? $user->username,
            'email' => $attributes['email'] ?? $user->email,
            'photo' => $attributes['photo'] ?? $user->photo,
            'type' => $attributes['type'] ?? $user->type,
        ];

        if (isset($attributes['password'])) {
            $updatedUserDetails['password'] = $this->hash($attributes['password']);
        }

        return $user->update($updatedUserDetails);
    }

    /**
     * Soft delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    /**
     * Include only soft deleted records in the results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listTrashed($perPage = 10)
    {
        return User::onlyTrashed()
            ->paginate($perPage);
    }

    /**
     * Restore model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function restore($id): void
    {
        User::onlyTrashed()
            ->findOrFail($id)
            ->restore();
    }

    /**
     * Permanently delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function delete($id): void
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
    }

    /**
     * Generate random hash key.
     *
     * @param  string $key
     * @return string
     */
    public function hash(string $key): string
    {
        return bcrypt($key);
    }

    /**
     * Upload the given file.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    public function upload(UploadedFile $file)
    {
        $dir = "";
        $filename = Str::random(40);
        $ext = $file->clientExtension();
        $path = "{$dir}{$filename}.{$ext}";

        $file->storeAs('public', $path);

        return $path;
    }
}
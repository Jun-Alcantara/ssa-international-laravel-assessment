@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-6 offset-3 mt-3 px-3 py-3 bg-white">

      <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" value="PUT">

        <div class="row mb-4">
          <div class="col-10">
            <a href="{{ route('users.index') }}">
              <i class="fa fa-arrow-left"></i> All users
            </a>
            <h1 class="h1">Edit User</h1>
            <span class="text-danger">*</span> are required fields
          </div>
        </div>

        <div class="row mb-4">
          <div class="col">
            <div class="form-floating mb-3">
              <select name="prefixname" class="form-select" id="prefixname" aria-label="Prefix Name">
                <option selected>-- Select Prefix Name --</option>
                <option value="Mr." {{ old('prefixname', $user->prefixname) === "Mr." ? "selected" : "" }}>Mr</option>
                <option value="Mrs." {{ old('prefixname', $user->prefixname) === "Mrs." ? "selected" : "" }}>Mrs</option>
                <option value="Ms" {{ old('prefixname', $user->prefixname) === "Ms" ? "selected" : "" }}>Ms</option>
              </select>
              <label for="prefixname">Prefix Name: {{ old('prefixname', $user->prefixname) === "Mr" ? "selected" : $user->prefixname }}</label>
            </div>

            <div class="form-floating mb-3 has-validation">
              <input type="text"
                id="firstname"
                class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}"
                name="firstname"
                placeholder="First Name"
                value="{{ old('firstname', $user->firstname) }}"
              >
              <label for="firstname">
                First Name: <span class="text-danger">*</span>
              </label>
              @if($errors->has('firstname'))
                <div id="firstname-invalid-feedback" class="invalid-feedback">
                  {{ $errors->first('firstname') }}
                </div>
              @endif
            </div>

            <div class="form-floating has-validation mb-3">
              <input type="text"
                id="middlename"
                class="form-control {{ $errors->has('middlename') ? 'is-invalid' : '' }}"
                name="middlename"
                placeholder="Middle Name"
                value="{{ old('middlename', $user->middlename) }}"
              >
              <label for="middlename">Middle Name:</label>
              @if($errors->has('middlename'))
                <div id="middlename-invalid-feedback" class="invalid-feedback">
                  {{ $errors->first('middlename') }}
                </div>
              @endif
            </div>

            <div class="form-floating mb-3">
              <input type="text"
                id="lastname"
                class="form-control {{ $errors->has('lastname') ? 'is-invalid' : '' }}"
                name="lastname"
                placeholder="Last Name"
                value="{{ old('lastname', $user->lastname) }}"
              >
              <label for="lastname">
                Last Name: <span class="text-danger">*</span>
              </label>
              @if($errors->has('lastname'))
                <div id="lastname-invalid-feedback" class="invalid-feedback">
                  {{ $errors->first('lastname') }}
                </div>
              @endif
            </div>

            <div class="form-floating mb-3">
              <input type="text"
                class="form-control"
                id="sufixname"
                name="sufixname"
                placeholder="Suffix Name"
                value="{{ old('sufixname', $user->suffixname) }}"
              >
              <label for="sufixname">Suffix Name:</label>
            </div>

            <div class="form-floating mb-3">
              <input type="text"
                id="username"
                class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                name="username"
                placeholder="Username"
                value="{{ old('username', $user->username) }}"
              >
              <label for="username">
                Username: <span class="text-danger">*</span>
              </label>
              @if($errors->has('username'))
                <div id="username-invalid-feedback" class="invalid-feedback">
                  {{ $errors->first('username') }}
                </div>
              @endif
            </div>

            <div class="form-floating mb-3">
              <input type="email"
                id="email_address"
                class="form-control {{ $errors->has('email_address') ? 'is-invalid' : '' }}"
                name="email_address"
                placeholder="name@example.com"
                value="{{ old('email_address', $user->email) }}"
              >
              <label for="email_address">
                Email address: <span class="text-danger">*</span>
              </label>
              @if($errors->has('email_address'))
                <div id="email_address-invalid-feedback" class="invalid-feedback">
                  {{ $errors->first('email_address') }}
                </div>
              @endif
            </div>

            <div class="form-group mb-3">
              <label for="password">Photo:</label>
              @if($user->photo)
                <div class="form-group">
                  <img src="{{ $user->avatar }}" alt="Avatar" class="profile-avatar">
                </div>
              @endif
              <input type="file"
                id="photo"
                class="form-control"
                name="photo"
                placeholder="Photo"
              >
              @if($errors->has('photo'))
                <span class="text-danger">{{ $errors->first('photo') }}</span>
              @endif
            </div>

            @if(auth()->user()->id === $user->id)
              <hr>
              <div class="form-group">
                <h5>Update Your Password:</h5>
              </div>
              <div class="form-floating mb-3">
                <input type="password"
                  id="old_password"
                  class="form-control {{ $errors->has('old_password') ? 'is-invalid' : '' }}"
                  name="old_password"
                  placeholder="Old Password"
                >
                <label for="old_password">
                  Old Password: <span class="text-danger">*</span>
                </label>
                @if($errors->has('old_password'))
                  <div id="old_password-invalid-feedback" class="invalid-feedback">
                    {{ $errors->first('old_password') }}
                  </div>
                @endif
              </div>

              <div class="form-floating mb-3">
                <input type="password"
                  id="password"
                  class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                  name="password"
                  placeholder="Password"
                >
                <label for="password">
                  New Password: <span class="text-danger">*</span>
                </label>
                @if($errors->has('password'))
                  <div id="password-invalid-feedback" class="invalid-feedback">
                    {{ $errors->first('password') }}
                  </div>
                @endif
              </div>

              <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Password">
                <label for="password_confirmation">Confirm New Password:</label>
              </div>
            @endif
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-2 offset-10">
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-save"></i> Save User
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-6 offset-3 mt-3  px-3 py-3 bg-white">

      <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mb-4">
          <div class="col-10">
            <h1 class="h1">Create User</h1>
            <span class="text-danger">*</span> are required fields
          </div>
        </div>

        <div class="row mb-4">
          <div class="col">
            <div class="form-floating mb-3">
              <select name="prefixname" class="form-select" id="prefixname" aria-label="Prefix Name">
                <option selected>-- Select Prefix Name --</option>
                <option value="Mr" {{ old('prefixname') === "Mr" ? "selected" : "" }}>Mr</option>
                <option value="Mrs" {{ old('prefixname') === "Mrs" ? "selected" : "" }}>Mrs</option>
                <option value="Ms" {{ old('prefixname') === "Ms" ? "selected" : "" }}>Ms</option>
              </select>
              <label for="prefixname">Prefix Name:</label>
            </div>

            <div class="form-floating mb-3 has-validation">
              <input type="text"
                id="firstname"
                class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}"
                name="firstname"
                placeholder="First Name"
                value="{{ old('firstname') }}"
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
                value="{{ old('middlename') }}"
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
                value="{{ old('lastname') }}"
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
                value="{{ old('sufixname') }}"
              >
              <label for="sufixname">Suffix Name:</label>
            </div>

            <div class="form-floating mb-3">
              <input type="text"
                id="username"
                class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                name="username"
                placeholder="Username"
                value="{{ old('username') }}"
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
                value="{{ old('email_address') }}"
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

            <div class="form-floating mb-3">
              <input type="password"
                id="password"
                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                name="password"
                placeholder="Password"
              >
              <label for="password">
                Password: <span class="text-danger">*</span>
              </label>
              @if($errors->has('password'))
                <div id="password-invalid-feedback" class="invalid-feedback">
                  {{ $errors->first('password') }}
                </div>
              @endif
            </div>

            <div class="form-floating mb-3">
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Password">
              <label for="password_confirmation">Confirm Password:</label>
            </div>

            <div class="form-group mb-3">
              <label for="password">Photo:</label>
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
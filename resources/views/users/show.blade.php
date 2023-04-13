@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-6 offset-3 mt-3 px-3 py-3 bg-white">

      <div class="row">
        <div class="col">
          <a href="{{ route('users.index') }}" class="text-decoration-none">
            <i class="fa fa-arrow-left"></i> All Users
          </a>
        </div>
      </div>

      <div class="row">
        <div class="col-2 p-3">
          <img src="{{ $user->avatar }}" alt="User Avatar" class="img-thumbnail">
        </div>
        <div class="col-10 p-3">
          <div>
            <i class="fa fa-user"></i> {{ $user->prefixname }} {{ $user->fullname }} {{ $user->sufixname }} ({{ $user->username }})
          </div>
          <div>
            <i class="fa fa-envelope"></i> {{ $user->email }}
          </div>
          <div class="mt-3">
            <a href="{{ route('users.edit', $user) }}">
              <i class="fa fa-edit"></i> Edit
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection
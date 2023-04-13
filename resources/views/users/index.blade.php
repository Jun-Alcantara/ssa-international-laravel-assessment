@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-6 offset-3 mt-3 px-3 py-3 bg-white">
      <div class="row mb-4">
        <div class="col-10">
          <h1 class="h1">All Users</h1>
        </div>
        <div class="col-2">
          <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fa fa-user-plus"></i> Create User
          </a>
        </div>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Photo</th>
            <th scope="col">Username</th>
            <th scope="col">Fullname</th>
            <th scope="col">Email</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
            <tr>
              <td>
                <img src="{{ $user->avatar }}" alt="User Avatar" class="list-avatar">
              </td>
              <td class="align-middle">
                <a href="{{ route('users.show', $user) }}">
                  {{ $user->username }}
                </a>
              </td>
              <td class="align-middle">{{ $user->fullname }}</td>
              <td class="align-middle">{{ $user->email }}</td>
              <td class="align-middle">
                <div class="dropdown">
                  <a class="cursor-pointer" id="user-itme-actions" data-bs-toggle="dropdown">
                    <i class="fa fa-ellipsis-h"></i>
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="user-itme-actions">
                    <li>
                      <a href="{{ route('users.show', $user) }}" class="text-primary dropdown-item">
                        <i class="fa fa-eye"></i> View Details
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('users.edit', $user) }}" class="text-primary dropdown-item">
                        <i class="fa fa-edit"></i> Edit Details
                      </a>
                    </li>
                    <li>
                      <a href="#"
                        class="text-danger dropdown-item confirm-delete"
                        data-deleteaction="{{ route('users.destroy', $user->id) }}"
                        data-username="{{ $user->username }}"
                      >
                        <i class="fa fa-trash"
                          data-deleteaction="{{ route('users.destroy', $user->id) }}"
                          data-username="{{ $user->username }}"
                        ></i> Delete User
                      </a>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      {{ $users->links() }}
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const deleteButtons = document.querySelectorAll('.confirm-delete')

      deleteButtons.forEach((button) => {
        button.addEventListener('click', event => {
          event.preventDefault()

          const deleteaction = event.target.getAttribute('data-deleteaction')
          const username = event.target.getAttribute('data-username')

          Swal.fire({
            title: "Delete Confirmation",
            text: `Are you sure you want to delete ${username}'s record?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sure, delete it'
          }).then((result) => {
            if (result.isConfirmed) {
              const deleteForm = document.createElement('form')
              deleteForm.method = "POST"
              deleteForm.action = `${deleteaction}`

              const methodField = document.createElement('input');
              methodField.name = "_method"
              methodField.value = "DELETE"

              deleteForm.appendChild(methodField)

              const csrfField = document.createElement('input');
              csrfField.name = "_token"
              csrfField.value = "{{ csrf_token() }}"

              deleteForm.appendChild(csrfField)

              document.body.appendChild(deleteForm)
              deleteForm.submit()

            }
          })
        })
      })
    }, false)
  </script>
@endpush
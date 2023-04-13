@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-6 offset-3 mt-3 px-3 py-3 bg-white">
      <div class="row mb-4">
        <div class="col-10">
          <h1 class="h1">Deleted Users</h1>
        </div>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Photo</th>
            <th scope="col">Username</th>
            <th scope="col">Fullname</th>
            <th scope="col">Email</th>
            <th scope="col">Date Delete</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
            <tr>
              <td>
                <img src="{{ $user->avatar }}" alt="User Avatar" class="list-avatar">
              </td>
              <td class="align-middle">{{ $user->username }}</td>
              <td class="align-middle">{{ $user->fullname }}</td>
              <td class="align-middle">{{ $user->email }}</td>
              <td class="align-middle">{{ $user->deleted_at->format('F d, Y H:i A') }}</td>
              <td class="align-middle">
                <div class="dropdown">
                  <a class="cursor-pointer" id="user-itme-actions" data-bs-toggle="dropdown">
                    <i class="fa fa-ellipsis-h"></i>
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="user-itme-actions">
                    <li>
                      <a href="#"
                        class="text-primary dropdown-item restore-button"
                        data-restoreaction="{{ route('users.restore', $user) }}"
                        data-username="{{ $user->username }}"
                      >
                        <i class="fa fa-refresh"
                          data-restoreaction="{{ route('users.restore', $user) }}"
                          data-username="{{ $user->username }}"
                        ></i> Restore User
                      </a>
                    </li>
                    <li>
                      <a href="#"
                        class="text-danger dropdown-item confirm-delete"
                        data-deleteaction="{{ route('users.delete', $user->id) }}"
                        data-username="{{ $user->username }}"
                      >
                        <i class="fa fa-trash"
                          data-deleteaction="{{ route('users.delete', $user->id) }}"
                          data-username="{{ $user->username }}"
                        ></i> Delete Permanently
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
            title: "Delete Record Permanently?",
            text: `Are you sure you want to delete ${username}'s record permanently?`,
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

      const restoreButtons = document.querySelectorAll('.restore-button')

      restoreButtons.forEach((rButton) => {
        rButton.addEventListener('click', event => {
          event.preventDefault()

          const restoreaction = event.target.getAttribute('data-restoreaction')
          const username = event.target.getAttribute('data-username')

          Swal.fire({
            title: "Restore Record Confirmation?",
            text: `Are you sure you want to restore ${username}'s record?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Let\'s restore it'
          }).then((result) => {
            if (result.isConfirmed) {
              const restoreForm = document.createElement('form')
              restoreForm.method = "POST"
              restoreForm.action = `${restoreaction}`

              const restoreMethodField = document.createElement('input');
              restoreMethodField.name = "_method"
              restoreMethodField.value = "PATCH"

              restoreForm.appendChild(restoreMethodField)

              const restoreCsrfField = document.createElement('input');
              restoreCsrfField.name = "_token"
              restoreCsrfField.value = "{{ csrf_token() }}"

              restoreForm.appendChild(restoreCsrfField)

              document.body.appendChild(restoreForm)
              restoreForm.submit()

            }
          })
        })
      })
    }, false)
  </script>
@endpush
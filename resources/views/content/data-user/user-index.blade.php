@extends('layouts/contentNavbarLayout')

@section('title', 'Manajemen User')

@section('page-script')
    @vite('resources/assets/js/form-basic-inputs.js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/assets/js/delete-sweetalert.js')
    @vite('resources/assets/js/success-message.js')
@endsection

@section('content')
<body @if (session('success')) data-success="{{ session('success') }}" @endif
      @if (session('error')) data-error="{{ session('error') }}" @endif>

<div class="card">
  <div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Manajemen User</h5>
      <a href="javascript:void(0)" class="btn rounded-pill btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
        <span class="tf-icons bx bx-plus bx-18px me-2"></span>Tambah User
      </a>
    </div>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahUserLabel">Tambah User Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="modal-body">
              <div class="mb-3 row">
                <label class="col-md-2 col-form-label">Nama</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" name="name" required />
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-2 col-form-label">Email</label>
                <div class="col-md-10">
                  <input type="email" class="form-control" name="email" required />
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-2 col-form-label">Password</label>
                <div class="col-md-10">
                  <input type="password" class="form-control" name="password" required />
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-2 col-form-label">Role</label>
                <div class="col-md-10">
                  <select name="role" class="form-select" required>
                    <option value="admin">Admin</option>
                    <option value="superadmin">Superadmin</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $index => $user)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ ucfirst($user->role) }}</td>
            <td class="text-center align-middle">
              <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                <i class="bx bx-edit-alt"></i>
              </a>
              <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline delete-form" data-id="{{ $user->id }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-icon btn-danger btn-delete" title="Hapus">
                  <i class="bx bx-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center">Tidak ada data user.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

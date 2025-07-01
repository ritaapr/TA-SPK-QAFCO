@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Data User')

@section('page-script')
    @vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-header mb-0">Edit Data User</h5>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary me-3" title="Kembali ke daftar"
                        style="font-size: 1.2rem;">&times;</a>
                </div>

                <div class="card-body demo-vertical-spacing demo-only-element">
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nama -->
                        <div class="mb-3 row">
                            <label for="name" class="col-md-2 col-form-label">Nama</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $user->name) }}" placeholder="Masukkan Nama" required />
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3 row">
                            <label for="email" class="col-md-2 col-form-label">Email</label>
                            <div class="col-md-10">
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" placeholder="Masukkan Email" required />
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="mb-3 row">
                            <label for="role" class="col-md-2 col-form-label">Role</label>
                            <div class="col-md-10">
                                <select name="role" id="role" class="form-select" required>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="superadmin" {{ $user->role === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                </select>
                            </div>
                        </div>

                        <!-- Password Baru (Opsional) -->
                        <div class="mb-3 row">
                            <label for="password" class="col-md-2 col-form-label">Password Baru</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Kosongkan jika tidak ingin mengganti password" />
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

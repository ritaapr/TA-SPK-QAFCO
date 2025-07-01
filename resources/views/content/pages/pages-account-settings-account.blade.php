@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
@vite(['resources/assets/js/pages-account-settings-account.js'])
@endsection
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
  <ul class="mb-0">
    @foreach($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif

@section('content')
<div class="row">
  <div class="col-md-12">

    <div class="card mb-6">

      <div class="card-body pt-4">
        <form id="formAccountSettings" method="POST" action="{{ route('account.settings.update') }}">
          @csrf
          <div class="row mb-4">
            <label class="col-sm-2 col-form-label" for="firstName">Nama</label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-user"></i></span>
                <input class="form-control" type="text" id="firstName" name="name" value="{{ old('name', $user->name) }}" autofocus />
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <label class="col-sm-2 col-form-label" for="email">Email</label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" />
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <label class="col-sm-2 col-form-label" for="password">Password</label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                <input class="form-control" type="password" id="password" name="password" placeholder="Masukkan password baru" />
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <label class="col-sm-2 col-form-label" for="password_confirmation">Konfirmasi Password</label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-lock"></i></span>
                <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" placeholder="Masukkan password sekali lagi"/>
              </div>
            </div>
          </div>

          <div class="row justify-content-end mt-4">
            <div class="col-sm-10 offset-sm-2">
              <button type="submit" class="btn btn-primary me-2">Simpan perubahan</button>
              <a href="{{ route('dashboard-analytics') }}" class="btn btn-outline-secondary">Batal</a>

            </div>
          </div>


        </form>

      </div>

    </div>
  </div>
  @endsection
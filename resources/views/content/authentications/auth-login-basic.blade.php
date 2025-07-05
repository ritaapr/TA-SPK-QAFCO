@extends('layouts/blankLayout')

@section('title', 'Login Basic - Pages')

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Login -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    @include('_partials.macros', [
                                        'width' => 60,
                                        'withbg' => 'var(--bs-primary)',
                                    ])
                                </span>
                            </a>
                        </div>
                        <!-- /Logo -->

                        {{-- Alert jika login gagal --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="formAuthentication" class="mb-6" action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="form-label">Email atau Username</label>
                                <input type="text" class="form-control" id="email" name="email-username"
                                    placeholder="Masukkan email atau username" required>
                            </div>
                            <div class="mb-4 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="************" required />
                                    <span class="input-group-text cursor-pointer" onclick="togglePassword()">
                                        <i class="bx bx-hide" id="toggleIcon"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
@endsection

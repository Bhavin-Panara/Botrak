@extends('layouts.authenticate')

@section('title', 'Login')

@section('content')
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <a
                href="{{ route('login.show') }}"
                class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover"
                >
                <h1 class="mb-0"><b>Bo</b>Trak</h1>
            </a>
            <p class="mb-0 text-center"><small>nainit.virtueinfo@gmail.com / 6vXNHHQUmNwr9nc</small></p>
        </div>
        <div class="card-body login-card-body">

            @if (session('success'))
                <div class="alert alert-success fade-message" role="alert">{{ session('success') }}</div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger fade-message" role="alert">{{ session('error') }}</div>
            @endif

            <!-- <p class="login-box-msg">Sign in to start your session</p> -->
            <form action="{{ route('login.prosses') }}" method="post">
                @csrf

                <div class="input-group mb-1">
                    <div class="form-floating">
                        <input id="loginEmail email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" autocomplete="email" placeholder="" required autofocus/>
                        <label for="loginEmail">Email</label>
                    </div>
                    <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                </div>
                @error('email')
                    <div class="form-text text-danger pb-3">{{ $message }}</div>
                @enderror

                <div class="input-group mb-1">
                    <div class="form-floating">
                        <input id="loginPassword password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="" required/>
                        <label for="loginPassword">Password</label>
                    </div>
                    <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                </div>
                @error('password')
                    <div class="form-text text-danger pb-3">{{ $message }}</div>
                @enderror

                <!--begin::Row-->
                <div class="row">
                    <!-- <div class="col-8 d-inline-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                            <label class="form-check-label" for="flexCheckDefault"> Remember Me </label>
                        </div>
                    </div> -->
                    <!-- /.col -->
                    <div class="col-12 pt-3">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Sign In</button>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!--end::Row-->
            </form>
            <!-- <div class="social-auth-links text-center mb-3 d-grid gap-2">
                <p>- OR -</p>
                <a href="#" class="btn btn-primary">
                <i class="bi bi-facebook me-2"></i> Sign in using Facebook
                </a>
                <a href="#" class="btn btn-danger">
                <i class="bi bi-google me-2"></i> Sign in using Google+
                </a>
            </div> -->
            <!-- /.social-auth-links -->
            @if (Route::has('password.request'))
                <p class="mb-1"><a href="{{ route('password.request') }}">I forgot my password</a></p>
            @endif
            <!-- <p class="mb-0">
                <a href="register.html" class="text-center"> Register a new membership </a>
            </p> -->
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title')
Login
@endsection


@section('body-class')
bg-gradient-primary
@endsection

@section('body-container')
<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                @if($errors->any())
                                <div class="card mb-4 py-3 border-bottom-danger" id="error-message">
                                    <div class="card-body">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @endif
                                @if (session('success'))
                                <div class="card mb-4 py-3 border-bottom-success" id="success-message">
                                    <div class="card-body">
                                        {{ session('success') }}
                                    </div>
                                </div>
                                @endif

                                <script>
                                    // Auto-hide success message after 5 seconds
                                    setTimeout(function() {
                                        $('#success-message').fadeOut('slow');
                                    }, 3000);

                                    // Auto-hide error message after 5 seconds
                                    setTimeout(function() {
                                        $('#error-message').fadeOut('slow');
                                    }, 3000);
                                </script>

                                <div class="text-center">
                                    <h1 class="mb-4">Welcome Back!</h1>
                                </div>
                                <form action="{{ route('employees.login') }}" method="POST" class="user">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="exampleInputEmail" value="{{ old('txtId') }}" aria-describedby="emailHelp" name="txtId" aria-describedby="emailHelp" placeholder="Enter Employee ID...">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="exampleInputPassword" value="{{ old('txtPassword') }}" name="txtPassword" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Login">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
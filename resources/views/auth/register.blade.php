<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
    <meta name="author" content="RentEasy">
    <title>{{ env('APP_NAME') }}</title>
    <link href="{{ asset('ui-kit/css/modern.css') }}" rel="stylesheet">
    <script src="{{ asset('ui-kit/js/settings.js') }}"></script>
    <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('ui-kit/assets/images/icon-dark.ico') }}" />
</head>

<body class="theme-blue">
<div class="splash active">
    <div class="splash-icon"></div>
</div>

<main class="main h-100 w-100">
    <div class="container h-100">
        <div class="row h-100">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">

                    <div class="text-center mt-4">
                        <h1 class="h2">Create a Dealer Account</h1>
                        <p class="lead">
                            Step 1: Company Information
                        </p>
                        <p>Tell us about your company</p>
                        @include('layouts.flash-message')
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <form action="{{ route('home.register-step-one') }}" method="POST">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="company_name">Company Name</label>
                                            <input id="company_name" class="form-control @error('company_name') is-invalid @enderror" type="text" name="company_name" value="{{ old('company_name') }}" required/>
                                            @error('company_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="company_msisdn">Enter Phone/ Msisdn</label>
                                            <input id="company_msisdn" class="form-control @error('company_msisdn') is-invalid @enderror" type="text" name="company_msisdn" value="{{ old('company_msisdn') }}" required/>
                                            @error('company_msisdn')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="company_email">Enter Email</label>
                                            <input id="company_email" class="form-control @error('company_email') is-invalid @enderror" type="email" name="company_email" value="{{ old('company_email') }}" required/>
                                            @error('company_email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="no_of_employees">No Of Employees</label>
                                            <input id="no_of_employees" class="form-control @error('no_of_employees') is-invalid @enderror" type="number" min="1" name="no_of_employees" value="{{ old('no_of_employees') }}" required/>
                                            @error('no_of_employees')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="address">Address e.g. Nairobi, Kenya</label>
                                            <input id="address" class="form-control @error('address') is-invalid @enderror" type="text" name="address" value="{{ old('address') }}" required/>
                                            @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <div class="custom-control custom-checkbox align-items-center">
                                            <input id="customControlInline" type="checkbox" class="custom-control-input" value="remember-me" required>
                                            <label class="custom-control-label text-small" for="customControlInline">I agree with the
                                                <a href="javascript: void(0);">Terms & Conditions</a>
                                                and
                                                <a href="javascript: void(0);">Privacy Policy</a>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block">Proceed To Step 2</button>
                                    </div>
                                    <div class="text-center mt-3">
                                        <small>
                                            <a href="{{ route('login') }}">{{ __('Already Registered? Sign In Now') }}</a>
                                        </small>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<svg width="0" height="0" style="position:absolute">
    <defs>
        <symbol viewBox="0 0 512 512" id="ion-ios-pulse-strong">
            <path
                d="M448 273.001c-21.27 0-39.296 13.999-45.596 32.999h-38.857l-28.361-85.417a15.999 15.999 0 0 0-15.183-10.956c-.112 0-.224 0-.335.004a15.997 15.997 0 0 0-15.049 11.588l-44.484 155.262-52.353-314.108C206.535 54.893 200.333 48 192 48s-13.693 5.776-15.525 13.135L115.496 306H16v31.999h112c7.348 0 13.75-5.003 15.525-12.134l45.368-182.177 51.324 307.94c1.229 7.377 7.397 11.92 14.864 12.344.308.018.614.028.919.028 7.097 0 13.406-3.701 15.381-10.594l49.744-173.617 15.689 47.252A16.001 16.001 0 0 0 352 337.999h51.108C409.973 355.999 427.477 369 448 369c26.511 0 48-22.492 48-49 0-26.509-21.489-46.999-48-46.999z">
            </path>
        </symbol>
    </defs>
</svg>
<script src="{{ asset('ui-kit/js/app.js') }}"></script>

</body>

</html>

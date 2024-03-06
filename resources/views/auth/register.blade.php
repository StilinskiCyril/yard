@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Sign Up Now') }}</div>

                    <div class="card-body">
                        <div class="text-center mt-4">
                            <h1>Create a Dealer Account</h1>
                            <p>Step 1: Company Information</p>
                            <p>Tell us about your company</p>
                            @include('layouts.flash-message')
                        </div>

                        <form method="POST" action="{{ route('home.register-step-one') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="company_name" class="col-md-4 col-form-label text-md-end">{{ __('Company Name') }}</label>

                                <div class="col-md-6">
                                    <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" required autocomplete="company_name" autofocus>

                                    @error('company_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="company_msisdn" class="col-md-4 col-form-label text-md-end">{{ __('Company Msisdn/ Phone Number') }}</label>

                                <div class="col-md-6">
                                    <input id="company_msisdn" type="text" class="form-control @error('company_msisdn') is-invalid @enderror" name="company_msisdn" value="{{ old('company_msisdn') }}" required autocomplete="company_msisdn" autofocus>

                                    @error('company_msisdn')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="company_email" class="col-md-4 col-form-label text-md-end">{{ __('Company Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="company_email" type="email" class="form-control @error('company_email') is-invalid @enderror" name="company_email" value="{{ old('company_email') }}" required autocomplete="company_email">

                                    @error('company_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('No Of Employees') }}</label>

                                <div class="col-md-6">
                                    <input id="no_of_employees" type="number" min="1" class="form-control @error('no_of_employees') is-invalid @enderror" name="no_of_employees" value="{{ old('no_of_employees') }}" required autocomplete="no_of_employees">

                                    @error('no_of_employees')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('Address e.g. Nairobi, Kenya') }}</label>

                                <div class="col-md-6">
                                    <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required autocomplete="address">

                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Proceed To Step 2') }}
                                    </button>
                                    <a class="btn btn-link" href="{{ route('login') }}">
                                        {{ __('Already Registered? Sign In Now') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

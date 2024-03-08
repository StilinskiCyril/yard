<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use App\Rules\ValidateMsisdn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class HomeController extends Controller
{
    // Show the application dashboard.
    public function showDashboard(Request $request)
    {
        $greetings = genGreetings();
        if ($request->user()->hasRole('admin')){
            return Inertia::render('Admin/Dashboard',[
                'greetings' => $greetings
            ]);
        } elseif($request->user()->hasRole('company-admin')) {
            return Inertia::render('CompanyAdmin/Dashboard', [
                'greetings' => $greetings
            ]);
        } elseif($request->user()->hasRole('company-user')) {
            return Inertia::render('CompanyUser/Dashboard', [
                'greetings' => $greetings
            ]);
        } else {
            return Inertia::render('CustomerSupport/Dashboard', [
                'greetings' => $greetings
            ]);
        }
    }

    // Register step one
    public function registerStepOne(Request $request)
    {
        $request->validate([
            'company_name' => ['required', 'string', 'max:50', 'unique:companies,name'],
            'company_msisdn' => ['required', 'string', new ValidateMsisdn(true, 'Company', 'KE', false, 'msisdn', 'company_msisdn')],
            'company_email' => ['required', 'email', 'max:50', 'unique:companies,email'],
            'no_of_employees' => ['required', 'numeric'],
            'address' => ['required', 'string', 'max:50']
        ]);

        session(['company_wizard_data.step1' => $request->all()]);

        // redirect to step 2
        return redirect()->route('home.show-registration-form-step-two');
    }

    // Registration form step two
    public function showRegistrationStepTwo()
    {
        $companyInfo = session('company_wizard_data.step1');

        if (!isset($companyInfo['company_name'])) {
            session()->flash('error', 'Company name is required.');
            return redirect()->route('register');
        } elseif (!isset($companyInfo['company_msisdn'])) {
            session()->flash('error', 'Company Phone/ MSISDN is required.');
            return redirect()->route('register');
        } elseif (!isset($companyInfo['company_email'])) {
            session()->flash('error', 'Company email is required.');
            return redirect()->route('register');
        } elseif (!isset($companyInfo['no_of_employees'])) {
            session()->flash('error', 'Number of employees is required.');
            return redirect()->route('register');
        } elseif (!isset($companyInfo['address'])) {
            session()->flash('error', 'Company address is required.');
            return redirect()->route('register');
        }
        return view('auth.register2');
    }

    // Register step two
    public function registerStepTwo(Request $request)
    {
        $companyInfo = session('company_wizard_data.step1');

        $request->merge([
            'company_name' => $companyInfo['company_name'],
            'company_msisdn' => $companyInfo['company_msisdn'],
            'company_email' => $companyInfo['company_email'],
            'no_of_employees' => $companyInfo['no_of_employees'],
            'address' => $companyInfo['address']
        ]);

        $request->validate([
            // company representative info
            'name' => ['required', 'string', 'max:50'],
            'msisdn' => ['required', 'string', new ValidateMsisdn(true, 'User')],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'company_position' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],

            // company info
            'company_name' => ['required', 'string', 'max:50', 'unique:companies,name'],
            'company_msisdn' => ['required', 'string', new ValidateMsisdn(true, 'Company', 'KE', false, 'msisdn', 'company_msisdn')],
            'company_email' => ['required', 'email', 'unique:companies,email'],
            'no_of_employees' => ['required', 'numeric'],
            'address' => ['required', 'string', 'max:50']
        ]);

        $company = Company::create([
            'name' => $request->input('company_name'),
            'msisdn' => $request->input('company_msisdn'),
            'email' => $request->input('company_email'),
            'no_of_employees' => $request->input('no_of_employees'),
            'address' => $request->input('address')
        ]);

        $companyRep = User::create([
            'company_id' => $company->id,
            'name' => $request->input('name'),
            'msisdn' => $request->input('msisdn'),
            'email' => $request->input('email'),
            'company_position' => $request->input('company_position'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Assign company to company rep
        CompanyUser::create([
            'company_id' => $company->id,
            'user_id' => $companyRep->id,
        ]);

        $companyRep->assignRole('company-admin');

        // Login company rep
        auth()->login($companyRep, true);

        // TODO send email

        // Redirect to app dashboard
        return redirect()->route('home.show-dashboard');
    }
}

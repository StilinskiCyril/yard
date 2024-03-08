<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\County;
use App\Models\User;
use App\Rules\ValidateMsisdn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function load(Request $request)
    {
        if ($request->input('paginate')){
            return Company::filter($request)->paginate(50);
        } else {
            return Company::filter($request)->get();
        }
    }

    // Show profile
    public function showProfile(Request $request)
    {
        $company = Company::where('id', $request->user()->company_id)->first();
        if (!$company){
            session()->flash('error', 'Company not found');
            return redirect()->back();
        }
        return Inertia::render('CompanyAdmin/Profile', [
            'company' => $company,
            'counties' => County::all()
        ]);
    }

    // Load profile
    public function loadProfile(Company $company)
    {
        return $company;
    }

    public function updateProfile(Request $request, Company $company)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('companies')->ignore($company->id)],
            'msisdn' => ['required', 'string', new ValidateMsisdn(false, 'Company'), Rule::unique('companies', 'msisdn')->ignore($company->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('companies')->ignore($company->id)],
            'noOfEmployees' => ['required', 'numeric'],
            'address' => ['required', 'string', 'max:50'],
            'websiteUrl' => ['required', 'url', 'max:50'],
            'countyId' => ['required', 'numeric'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:1024'],
            'kycDoc' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:1024'],
            'about' => ['required', 'string', 'max:1000']
        ]);

        if ($request->hasFile('logo')){
            $logoUrl = Storage::put('companies/logo', $request->file('logo'));
            if (!is_null($company->logo_url) && Storage::exists($company->logo_url)) {
                Storage::delete($company->logo_url);
            }
        } else {
            $logoUrl = $company->logo_url;
        }

        if ($request->hasFile('kycDoc')){
            $kycDocUrl = Storage::put('companies/kyc', $request->file('kycDoc'));
            if (!is_null($company->kyc_doc_url) && Storage::exists($company->kyc_doc_url)) {
                Storage::delete($company->kyc_doc_url);
            }
        } else {
            $kycDocUrl = $company->kyc_doc_url;
        }

        $company->update([
            'name' => $request->name,
            'msisdn' => $request->msisdn,
            'email' => $request->email,
            'no_of_employees' => $request->noOfEmployees,
            'address' => $request->address,
            'website_url' => $request->websiteUrl,
            'county_id' => $request->countyId,
            'logo_url' => $logoUrl,
            'kyc_doc_url' => $kycDocUrl,
            'about' => $request->about
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record updated'
        ]);
    }

    // Show users
    public function showUsers(Request $request)
    {
        $company = Company::where('id', $request->user()->company_id)->first();
        if (!$company){
            session()->flash('error', 'Company not found');
            return redirect()->back();
        }
        return Inertia::render('CompanyAdmin/Users', [
            'company' => $company
        ]);
    }

    // Load users
    public function loadUsers(Request $request, Company $company)
    {
        if ($request->input('paginate')){
            $users = $company->users()->filter($request)->paginate(50);
        } else {
            $users = $company->users()->filter($request)->get();
        }

        $users->getCollection()->transform(function($user) {
            return [
                'id' => $user->id,
                'uuid' => $user->uuid,
                'name' => $user->name,
                'msisdn' => $user->msisdn,
                'email' => $user->email,
                'company_position' => $user->company_position,
                'status' => $user->status,
                'two_factor_auth' => $user->two_factor_auth,
                'roles' => $user->getRoleNames()
            ];
        });
        return $users;
    }

    // Create user
    public function createUser(Request $request, Company $company)
    {
        $request->validate([
            'msisdn' => ['required', 'string', new ValidateMsisdn(false, 'User')],
            'email' => ['required', 'email']
        ]);

        // Check if user exists
        $existingUser = User::where('msisdn', $request->msisdn)->orWhere('email', $request->email)->first();
        if ($existingUser){
            // Assign company to user
            if ($company->companyUsers()->where('user_id', $existingUser->id)->exists()){
                return response()->json([
                    'status' => false,
                    'message' => 'User already assigned to your company'
                ], 422);
            }

            $company->companyUsers()->create([
                'user_id' => $existingUser->id
            ]);

            // TODO send sms

            return response()->json([
                'status' => true,
                'message' => 'User assigned to company your successfully'
            ]);
        } else {
            $request->validate([
                'name' => ['required', 'string'],
                'msisdn' => ['required', 'string', new ValidateMsisdn(true)],
                'email' => ['required', 'email', 'unique:users'],
                'companyPosition' => ['required', 'string'],
                'twoFactorAuth' => ['nullable', 'boolean'],
                'password' => ['required', 'string', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised(), 'confirmed']
            ]);

            // Create user
            $user = User::create([
                'name' => $request->name,
                'msisdn' => $request->msisdn,
                'email' => $request->email,
                'company_position' => $request->companyPosition,
                'two_factor_auth' => (bool)$request->twoFactorAuth,
                'password' => Hash::make($request->password)
            ]);

            // Assign role
            $user->assignRole('company-user');

            // Assign company to user
            $company->companyUsers()->create([
                'user_id' => $user->id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User created and assigned to your company successfully'
            ]);
        }
    }

    // Update user
    public function updateUser(Request $request, User $user)
    {
        // Check if user is assigned to another company
        if ($user->companyUsers()->count() > 1){
            return response()->json([
                'status' => false,
                'message' => 'User account is also assigned to another company hence cannot be updated'
            ], 422);
        }


        $request->validate([
            'name' => ['required', 'string'],
            'msisdn' => ['required', 'string', new ValidateMsisdn(false), Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'companyPosition' => ['required', 'string'],
            'twoFactorAuth' => ['nullable', 'boolean']
        ]);

        $user->update([
            'name' => $request->name,
            'msisdn' => $request->msisdn,
            'email' => $request->email,
            'company_position' => $request->companyPosition,
            'two_factor_auth' => (bool)$request->twoFactorAuth
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User updated'
        ]);
    }

    // Remove user
    public function removeUser(Company $company, User $user)
    {
        $company->companyUsers()->where('user_id', $user->id)->forceDelete();

        return response()->json([
            'status' => true,
            'message' => 'User removed from company'
        ]);
    }

    // Show profile
    public function showWallet(Request $request)
    {
        $company = Company::where('id', $request->user()->company_id)->first();
        if (!$company){
            session()->flash('error', 'Company not found');
            return redirect()->back();
        }
        return Inertia::render('CompanyAdmin/Wallet', [
            'company' => $company
        ]);
    }

    // Load wallet transactions
    public function loadWalletTransactions(Request $request, Company $company)
    {
        return $company->walletTransactions()->filter($request)->paginate(50);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}

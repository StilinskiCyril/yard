<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\County;
use App\Rules\ValidateMsisdn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
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

    // show company profile
    public function showProfile(Request $request)
    {
        $company = Company::where('id', $request->user()->company_id)->first();
        if (!$company){
            session()->flash('error', 'Company not found');
            return redirect()->back();
        }
        return Inertia::render('Company/Profile', [
            'company' => $company,
            'counties' => County::all()
        ]);
    }

    // Load company profile
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}

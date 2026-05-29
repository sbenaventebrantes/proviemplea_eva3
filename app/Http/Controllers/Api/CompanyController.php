<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $paginator = Company::orderByDesc('id')->paginate(10);
        $items = collect($paginator->items())->map(fn (Company $company) => $this->transformCompany($company))->all();

        return response()->json([
            'data' => $items,
            'pagination' => [
                'page' => $paginator->currentPage(),
                'size' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'companyName' => ['required', 'string', 'max:150'],
            'taxId' => ['required', 'string', 'max:20', 'unique:companies,tax_id'],
            'sector' => ['required', 'string', 'max:100'],
            'contactName' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:companies,email'],
            'phoneNumber' => ['nullable', 'string', 'max:30'],
        ]);

        $company = Company::create([
            'company_name' => $data['companyName'],
            'tax_id' => $data['taxId'],
            'sector' => $data['sector'],
            'contact_name' => $data['contactName'] ?? null,
            'email' => $data['email'],
            'phone_number' => $data['phoneNumber'] ?? null,
            'validation_status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Registro creado correctamente',
            'data' => $this->transformCompany($company),
        ], 201);
    }

    public function show(Company $company)
    {
        return response()->json(['data' => $this->transformCompany($company)]);
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'companyName' => ['required', 'string', 'max:150'],
            'taxId' => ['required', 'string', 'max:20', 'unique:companies,tax_id,'.$company->id],
            'sector' => ['required', 'string', 'max:100'],
            'contactName' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:companies,email,'.$company->id],
            'phoneNumber' => ['nullable', 'string', 'max:30'],
        ]);

        $company->update([
            'company_name' => $data['companyName'],
            'tax_id' => $data['taxId'],
            'sector' => $data['sector'],
            'contact_name' => $data['contactName'] ?? null,
            'email' => $data['email'],
            'phone_number' => $data['phoneNumber'] ?? null,
        ]);

        return response()->json([
            'message' => 'Registro actualizado correctamente',
            'data' => $this->transformCompany($company->fresh()),
        ]);
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json([
            'message' => 'Registro eliminado correctamente',
        ]);
    }

    private function transformCompany(Company $company): array
    {
        return [
            'id' => $company->id,
            'companyName' => $company->company_name,
            'taxId' => $company->tax_id,
            'sector' => $company->sector,
            'contactName' => $company->contact_name,
            'email' => $company->email,
            'phoneNumber' => $company->phone_number,
            'validationStatus' => $company->validation_status,
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Person;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function index()
    {
        $paginator = Contact::orderByDesc('id')->paginate(10);
        $items = collect($paginator->items())->map(fn (Contact $contact) => $this->transformContact($contact))->all();

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
            'type' => ['required', 'in:person,company'],
            'referenceId' => ['required', 'integer'],
            'fullName' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phoneNumber' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:500'],
        ]);

        if ($data['type'] === 'person' && ! Person::whereKey($data['referenceId'])->exists()) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        if ($data['type'] === 'company' && ! Company::whereKey($data['referenceId'])->exists()) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $contact = Contact::create([
            'type' => $data['type'],
            'reference_id' => $data['referenceId'],
            'full_name' => $data['fullName'],
            'email' => $data['email'],
            'phone_number' => $data['phoneNumber'] ?? null,
            'message' => $data['message'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Registro creado correctamente',
            'data' => $this->transformContact($contact),
        ], 201);
    }

    public function updateStatus(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,attended,closed'],
        ]);

        $contact->update(['status' => $data['status']]);

        return response()->json([
            'message' => 'Registro actualizado correctamente',
            'data' => $this->transformContact($contact->fresh()),
        ]);
    }

    public function statistics()
    {
        return response()->json([
            'data' => [
                'persons' => Person::count(),
                'companies' => Company::count(),
                'contacts' => Contact::count(),
                'pendingContacts' => Contact::where('status', 'pending')->count(),
            ],
        ]);
    }

    private function transformContact(Contact $contact): array
    {
        return [
            'id' => $contact->id,
            'type' => $contact->type,
            'referenceId' => $contact->reference_id,
            'fullName' => $contact->full_name,
            'email' => $contact->email,
            'phoneNumber' => $contact->phone_number,
            'message' => $contact->message,
            'status' => $contact->status,
        ];
    }
}

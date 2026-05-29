<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function index()
    {
        $paginator = Person::orderByDesc('id')->paginate(10);
        $items = collect($paginator->items())->map(fn (Person $person) => $this->transformPerson($person))->all();

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
            'firstName' => ['required', 'string', 'max:100'],
            'lastName' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:persons,email'],
            'phoneNumber' => ['nullable', 'string', 'max:30'],
            'taxId' => ['required', 'string', 'max:50', 'unique:persons,tax_id'],
        ]);

        $person = Person::create([
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'email' => $data['email'],
            'phone_number' => $data['phoneNumber'] ?? null,
            'tax_id' => $data['taxId'],
            'validation_status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Registro creado correctamente',
            'data' => $this->transformPerson($person),
        ], 201);
    }

    public function show(Person $person)
    {
        return response()->json(['data' => $this->transformPerson($person)]);
    }

    public function update(Request $request, Person $person)
    {
        $data = $request->validate([
            'firstName' => ['required', 'string', 'max:100'],
            'lastName' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:persons,email,'.$person->id],
            'phoneNumber' => ['nullable', 'string', 'max:30'],
            'taxId' => ['required', 'string', 'max:50', 'unique:persons,tax_id,'.$person->id],
        ]);

        $person->update([
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'email' => $data['email'],
            'phone_number' => $data['phoneNumber'] ?? null,
            'tax_id' => $data['taxId'],
        ]);

        return response()->json([
            'message' => 'Registro actualizado correctamente',
            'data' => $this->transformPerson($person->fresh()),
        ]);
    }

    public function destroy(Person $person)
    {
        $person->delete();

        return response()->json([
            'message' => 'Registro eliminado correctamente',
        ]);
    }

    private function transformPerson(Person $person): array
    {
        return [
            'id' => $person->id,
            'firstName' => $person->first_name,
            'lastName' => $person->last_name,
            'email' => $person->email,
            'phoneNumber' => $person->phone_number,
            'taxId' => $person->tax_id,
            'validationStatus' => $person->validation_status,
        ];
    }
}

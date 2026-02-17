<?php

namespace App\Http\Controllers;

use App\Contracts\LocationServiceInterface;
use App\Mappers\DTO\Requests\LocationRequestDTO;
use Illuminate\Http\Request;

class LocationRegistrationController extends Controller
{
    private LocationServiceInterface $locationService;

    public function __construct(
        LocationServiceInterface $locationService
    ) {
        $this->locationService = $locationService;
    }

    public function getView()
    {
        return view('module.locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'headquartersName' => 'required|string|max:255',
            'postalCode' => 'required|integer',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $locationDTO = new LocationRequestDTO(
            $validated['headquartersName'],
            (int)$validated['postalCode'],
            $validated['state'],
            $validated['city'],
            $validated['address']
        );

        
        $result = $this->locationService->createLocation(
            $locationDTO
        );

        
        if ($result->isFailure()) {
            return back()
                ->withErrors($result->getError())
                ->withInput();
        }

        return redirect()
            ->route('location.store')
            ->with(
                'success',
                'Ubicación registrada correctamente'
            );
    }
}

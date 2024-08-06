<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['country', 'state', 'city'])->get();
        return view('frontend.users.index', compact('users'));
    }
    public function edit(User $user)
    {
        $countries = Country::all();
        $states = State::where('country_id', $user->country_id)->get();
        $city = City::where('state_id', $user->state_id)->get();
        return view('frontend.users.edit', compact('user', 'countries', 'states', 'city'));
    }

    public function update(Request $request, User $user)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                'string',
                'max:50',
                Rule::unique('users')->ignore($user->id),
            ],
            'mobile' => 'required|string|max:100',
            'password' => 'required',
            'file' => 'nullable|max:10240',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->country_id = $request->country_id;
        $user->state_id = $request->state_id;
        $user->city_id = $request->city_id;
        $user->password = Hash::make($request->password);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('public/images');
            $user->image = basename($path);
        }

        $user->save();

        return redirect('/')->with('success', 'User created successfully.');
    }
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|string|max:50|unique:users',
            'mobile' => 'required|string|max:100',
            'password' => 'required',
            'file' => 'nullable|max:10240',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->country_id = $request->country_id;
        $user->state_id = $request->state_id;
        $user->city_id = $request->city_id;
        $user->password = Hash::make($request->password);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('public/images');
            $user->image = basename($path);
            $user->avatar = basename($path);
        }

        $user->save();

        return redirect('/')->with('success', 'User created successfully.');
    }

    public function create()
    {
        $countries = Country::all();
        return view('frontend.users.create', compact('countries'));
    }
    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)->get();
        return response()->json($states);
    }

    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)->get();
        return response()->json($cities);
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/');
    }
}

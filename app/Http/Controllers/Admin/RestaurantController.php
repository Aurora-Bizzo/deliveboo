<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Http\Requests\StoreRestaurantRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::id();
        $restaurants = Restaurant::where('user_id', $user)->get();
        
        if (count($restaurants) < 1){

            return view('admin.restaurants.index', compact('restaurants'));

        } else {

            return view('admin.restaurants.show' , compact('restaurants'));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.restaurants.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRestaurantRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRestaurantRequest $request)
    {
        $user = Auth::user();
        $form_data = $request->all();
        $newRestaurant = new Restaurant();
        $newRestaurant->user_id = $user->id;
        $newRestaurant->fill($form_data);
        if($request->hasFile('photo')){

            $path = Storage::put('restaurant_photo', $request->photo);

            $form_data['photo'] = $path;

            $newRestaurant->photo = $form_data['photo'];
        }
        $newRestaurant->save();
        
        return redirect()->route('admin.restaurants.index');
        }   

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant)
    {
        
        return view('admin.restaurants.show', compact('restaurant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function edit(Restaurant $restaurant)
    {
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRestaurantRequest  $request
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
    {
        $user = Auth::user();
        $form_data = $request->all();
        $restaurant->user_id = $user->id;
        $restaurant->fill($form_data);
        $restaurant->save();
        return redirect()->route('admin.restaurants.show', compact('restaurant'));//->with('message', "{$restaurant->name} è stato aggiornato correttamente");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        //
    }
}
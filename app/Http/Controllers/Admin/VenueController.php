<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class VenueController extends Controller
{
    function venues(){
        $venue = DB::table('venues')->get();
       
        return view('admin.pages.master.venues.index',compact('venue'));
    }
    
    function addVenue(Request $request){
        $request->validate([
            'venue_name'=>'required|string|max:255',
            'address'=>'required|string|max:500',
        ]);
        
        $venueData = [
            'venue_name' => $request->venue_name,
            'address' => $request->address,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        if(DB::table('venues')->insert($venueData)){
            return redirect()->back()->with('success','Venue added Successfully');
        }
        
        return redirect()->back()->with('error','Venue is empty');
    }
    
    function updateVenue(Request $request){
        $request->validate([
            'id'=>'required|exists:venues,venue_id',
            'venue_name'=>'required|string|max:255',
            'address'=>'required|string|max:500',
        ]);
        
        $venueData = [
            'venue_name' => $request->venue_name,
            'address' => $request->address,
            'updated_at' => now()
        ];
        
        if(DB::table('venues')->where('venue_id', $request->id)->update($venueData)){
            return redirect()->back()->with('success','Venue was updated successfully');
        }
        
        return redirect()->back()->with('error','Venue updation issue');
    }
    
    function destroy($id){
        if(DB::table('venues')->where('venue_id', $id)->delete()){
            return redirect('master/venues')->with('success','Venue was Delete successfully');
        }
        
        return redirect()->back()->with('error','You selected wrong item');
    }
}

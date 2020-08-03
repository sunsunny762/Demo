<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Auth; 
use App\Http\Controllers\Controller;  
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {  
        $user = Auth::user();  
        return view('admin.modules.profile.addedit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = User::find(Auth::id());
        $request = $this->stripHtmlTags($request, User::$notStripTags);
        $id = Auth::id();
        $rules = [ 
            'first_name' => 'required' 
        ];
        $this->validate($request, $rules);
        $data = $request->all();   
        $user->fill($data);
        $user->save(); 
        return redirect()->route('dashboard')->with("success", __('user.update_profile_success', ['first_name' => $user->first_name, 'last_name' => $user->last_name]));         
    } 
}

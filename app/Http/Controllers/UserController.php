<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Versions;
use App\Models\OrganizationAssets;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('user.index', compact('users'));
    }
    
    public function asset()
    {
        $assetIds = Versions::where('item_type', 'OrganizationAsset')->where('whodunnit', Auth::user()->id)->orderBy('item_id', 'asc')->pluck('item_id')->unique()->values();
        
        $assets = OrganizationAssets::whereIn('id', $assetIds)->orderBy('id', 'desc')->get();

        return view('asset.index', compact('assets'));
    }
}
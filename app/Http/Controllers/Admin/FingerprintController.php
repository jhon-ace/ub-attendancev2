<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Admin\Fingerprint;


class FingerprintController extends Controller
{
     public function index()
    {
        // Example: Create a biometric
       

        return view('admin.fingerprint.index'); // Standardize casing for the view path
    }

    // Display the activate fingerprint view
    public function activate_fingerprint()
    {
        $fingerprints = Fingerprint::all(); // Plural naming for collections
        return view('admin.fingerprint.activate', compact('fingerprints')); // Standardize casing for the view path
    }

    // Update fingerprint status
    public function set_fingerprint(Request $request, $id)
    {
        $request->validate([
            'set_status' => 'required|integer', // Added 'required' for better validation
        ]);

        $fingerprint = Fingerprint::findOrFail($id);
        $fingerprint->fingerprint_status = $request->set_status;
        $fingerprint->save();

        return redirect()
            ->route('admin_staff.fingerprint')
            ->with('success', 'Successfully updated fingerprint availability.');
    }
}
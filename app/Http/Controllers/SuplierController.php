<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Suplier;

class SuplierController extends Controller
{
    public function index()
    {
        $supliers = Suplier::orderBy('created_at', 'DESC')->paginate(10);
        return view('suplier.index', compact('supliers'));
    }

    public function create()
    {
        return view('suplier.add');
    }

    public function save(Request $request)
    {
    //VALIDASI DATA
    $this->validate($request, [
        'name' => 'required|string',
        'phone' => 'required|max:13', //maximum karakter 13 digit
        'address' => 'required|string',
        'ket_produk' => 'required|string',
        'email' => 'required|email|string|unique:supliers,email' // format yag diterima harus email
    ]);

    try {
        $supliers = Suplier::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'ket_produk' => $request->ket_produk,
            'email' => $request->email
        ]);
        return redirect('/suplier')->with(['success' => 'Data telah disimpan']);
    } catch (\Exception $e) {
        return redirect()->back()->with(['error' => $e->getMessage()]);
    }
    }

    public function edit($id)
    {
    $supliers = Suplier::find($id);
    return view('suplier.edit', compact('supliers'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'phone' => 'required|max:13',
            'address' => 'required|string',
            'ket_produk' => 'required|string',
            'email' => 'required|email|string|exists:supliers,email'
        ]);

        try {
            $supliers = Suplier::find($id);
            $supliers->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'ket_produk' => $request->ket_produk,
                
            ]);
            return redirect('/suplier')->with(['success' => 'Data telah diperbaharui']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $supliers = Suplier::find($id);
        $supliers->delete();
        return redirect()->back()->with(['success' => '<strong>' . $supliers->name . '</strong> Telah dihapus']);
    }
}

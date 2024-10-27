<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'image'   => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'name'    => 'required|min:3',
            'alamat' => 'required|min:10',
            'phone'   => 'required|numeric'
        ]);

        $image = $request->file('image');
        $imageName = $image->hashName();
        $image->storeAs('customers', $imageName, 'public');

        Customer::create([
            'image'   => $imageName,
            'nama'    => $request->nama,
            'alamat' => $request->alamat,
            'phone'   => $request->phone
        ]);

        return redirect()->route('customers.index')->with(['success' => 'Data Customer Berhasil Disimpan!']);
    }

    public function show(string $id): View
    {
        $customer = Customer::findOrFail($id);
        return view('customers.show', compact('customer'));
    }

    public function edit(string $id): View
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'image'   => 'image|mimes:jpeg,jpg,png|max:2048',
            'nama'    => 'required|min:3',
            'alamat' => 'required|min:10',
            'phone'   => 'required|numeric'
        ]);

        $customer = Customer::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->storeAs('customers', $imageName, 'public');

            // Delete old image
            Storage::delete('public/customers/' . $customer->image);

            // Update customer with new image
            $customer->update([
                'image'   => $imageName,
                'name'    => $request->name,
                'alamat' => $request->alamat,
                'phone'   => $request->phone
            ]);
        } else {
            // Update without new image
            $customer->update([
                'name'    => $request->name,
                'alamat' => $request->alamat,
                'phone'   => $request->phone
            ]);
        }

        return redirect()->route('customers.index')->with(['success' => 'Data Customer Berhasil Diubah!']);
    }

    public function destroy($id): RedirectResponse
    {
        $customer = Customer::findOrFail($id);

        Storage::delete('public/customers/' . $customer->image);

        $customer->delete();

        return redirect()->route('customers.index')->with(['success' => 'Data Customer Berhasil Dihapus!']);
    }
}

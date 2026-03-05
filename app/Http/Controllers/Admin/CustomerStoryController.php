<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerStoryController extends Controller
{
    public function index()
    {
        $stories = CustomerStory::latest()->get();
        return view('admin.customer-stories.index', compact('stories'));
    }

    public function create()
    {
        return view('admin.customer-stories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'highlight' => 'required|string|max:255',
            'highlight_color' => 'nullable|string|max:20',
            'subheading' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('customer-stories', 'public');
        }

        CustomerStory::create($data);

        return redirect()->route('customer-stories.index')->with('success', 'Story added successfully!');
    }

    public function edit(CustomerStory $customerStory)
    {
        return view('admin.customer-stories.edit', compact('customerStory'));
    }

    public function update(Request $request, CustomerStory $customerStory)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'highlight' => 'required|string|max:255',
            'highlight_color' => 'nullable|string|max:20',
            'subheading' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($customerStory->image);
            $data['image'] = $request->file('image')->store('customer-stories', 'public');
        }

        $customerStory->update($data);

        return redirect()->route('customer-stories.index')->with('success', 'Story updated successfully!');
    }

    public function destroy(CustomerStory $customerStory)
    {
        Storage::disk('public')->delete($customerStory->image);
        $customerStory->delete();

        return redirect()->route('customer-stories.index')->with('success', 'Story deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Signatory;
use Illuminate\Http\Request;

class SignatoryController extends Controller
{
    public function index()
    {
        $signatories = Signatory::where('user_id', \Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.signatories.index', compact('signatories'));
    }

    // فرم ایجاد Signatory جدید
    public function create()
    {
        return view('signatories.create');
    }

    // ذخیره Signatory جدید
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:signatories,email',
            'phone' => 'nullable|string|max:50',
            'type' => 'required|in:علمی,دولتی,فنی و حرفه ای,پارک علم,سایر',
            'level' => 'required|integer|min:1|max:10',
        ]);

        $data['user_id'] = Auth::id();

        Signatory::create($data);

        return redirect()->route('signatories.index')
            ->with('success', 'Signatory created successfully.');
    }

    // نمایش جزئیات Signatory
    public function show(Signatory $signatory)
    {
        $this->authorizeUser($signatory);

        return view('signatories.show', compact('signatory'));
    }

    // فرم ویرایش Signatory
    public function edit(Signatory $signatory)
    {
        $this->authorizeUser($signatory);

        return view('signatories.edit', compact('signatory'));
    }

    // بروزرسانی Signatory
    public function update(Request $request, Signatory $signatory)
    {
        $this->authorizeUser($signatory);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:signatories,email,' . $signatory->id,
            'phone' => 'nullable|string|max:50',
            'type' => 'required|in:علمی,دولتی,فنی و حرفه ای,پارک علم,سایر',
            'level' => 'required|integer|min:1|max:10',
        ]);

        $signatory->update($data);

        return redirect()->route('signatories.index')
            ->with('success', 'Signatory updated successfully.');
    }

    // حذف Signatory
    public function destroy(Signatory $signatory)
    {
        $this->authorizeUser($signatory);

        $signatory->delete();

        return redirect()->route('signatories.index')
            ->with('success', 'Signatory deleted successfully.');
    }

    // متد خصوصی برای اطمینان از مالکیت Signatory
    private function authorizeUser(Signatory $signatory)
    {
        if ($signatory->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}

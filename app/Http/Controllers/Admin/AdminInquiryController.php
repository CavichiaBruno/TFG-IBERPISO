<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class AdminInquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inquiry::with(['property', 'user'])->latest();

        $status = $request->get('estado', 'todas');
        if ($status !== 'todas') {
            $query->where('status', $status);
        }

        $inquiries   = $query->paginate(20)->withQueryString();
        $unreadCount = Inquiry::unread()->count();

        return view('admin.inquiries.index', compact('inquiries', 'unreadCount'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $inquiry = Inquiry::findOrFail($id);

        $request->validate(['status' => 'required|in:pending,read,answered']);

        $inquiry->update([
            'status'  => $request->status,
            'is_read' => $request->status !== 'pending',
        ]);

        return response()->json(['success' => true, 'status' => $inquiry->status]);
    }

    public function destroy(int $id)
    {
        Inquiry::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function send(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:120',
            'phone'   => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'note'    => 'nullable|string',
        ]);

        $content = $validated['content'] ?? ($validated['note'] ?? '');
        if (empty(trim($content))) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => true, 'message' => __('Please enter your message.')], 422);
            }
            return back()->withInput()->with('error_msg', __('Please enter your message.'));
        }

        Contact::create([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'phone'   => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? 'Liên hệ từ website',
            'content' => $content,
            'status'  => 'unread',
        ]);

        $msg = __('Your message has been sent successfully!');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'error'   => false,
                'message' => $msg,
            ]);
        }

        return back()->with('success_msg', $msg);
    }
}


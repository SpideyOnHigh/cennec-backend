<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmailTestService;

class EmailTestController extends Controller
{
    protected $emailTestService;

    public function __construct(EmailTestService $emailTestService)
    {
        $this->emailTestService = $emailTestService;
    }

    public function index()
    {
        $authUser = auth()->user();
        if ($authUser->can("view-email-test")) {
            return view('backend.email-test.index');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'email' => 'required|email',
            'template' => 'required|in:simple,rich'
        ]);
        // Send the email
        try {
            $this->emailTestService->sendEmail($request->all());
            return response()->json([
                'template' => $request->template, // Include the template
                'success' => 'Email sent successfully!' // Success message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while sending the email!'
            ], 500);
        }
    }
}

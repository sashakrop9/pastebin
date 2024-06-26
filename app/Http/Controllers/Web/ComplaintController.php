<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateComplaintRequest;
use App\Models\Complaint;
use Illuminate\Http\RedirectResponse;

class ComplaintController extends Controller
{
    /**
     * @param CreateComplaintRequest $request
     * @return RedirectResponse
     */
    public function store(CreateComplaintRequest $request)
    {
        // Валидация прошла успешно, можно сохранять жалобу
        $complaint = new Complaint();
        $complaint->user_id = auth()->id(); // или $request->user()->id;
        $complaint->paste_id = $request->input('paste_id');
        $complaint->reason = $request->input('reason');
        $complaint->status = 'pending'; // статус по умолчанию
        $complaint->save();

        return redirect()->back()->with('success', 'Жалоба успешно отправлена.');
    }

}

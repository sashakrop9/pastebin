<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\PasteRequest;
use App\Models\Paste;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PasteController extends Controller
{
    public function index()
    {
        $pastes = Paste::where('access', 'public')
            ->where(function ($query) {
                $query->where('expires_at', '>', Carbon::now())
                    ->orWhereNull('expires_at');
            })
            ->latest()
            ->take(10)
            ->get();

        $userPastes = Auth::check() ? Paste::where('user_id', Auth::id())->latest()->take(10)->get() : [];

        return view('paste.index', compact('pastes', 'userPastes'));
    }

    public function show($hash)
    {
        $paste = Paste::where('hash', $hash)->firstOrFail();

        if ($paste->expires_at && Carbon::now()->gt($paste->expires_at)) {
            abort(404);
        }

        if ($paste->access === 'private' && (!Auth::check() || Auth::id() !== $paste->user_id)) {
            abort(403);
        }

        return view('paste.show', compact('paste'));
    }

    public function create()
    {
        return view('paste.create');
    }

    public function store(PasteRequest $request)
    {
        $paste = new Paste;
        $paste->title = $request->title;
        $paste->paste_content = $request->paste_content;
        $paste->access = $request->access;
        $paste->language = $request->language;
        $paste->hash = Str::random(8);

        $paste->expires_at = match ($request['expires_at']) {
            '10min' => Carbon::now()->addMinutes(10),
            '1hour' => Carbon::now()->addHour(),
            '3hours' => Carbon::now()->addHours(3),
            '1day' => Carbon::now()->addDay(),
            '1week' => Carbon::now()->addWeek(),
            '1month' => Carbon::now()->addMonth(),
            'never' => null,
        };

        if (Auth::check()) {
            $paste->user_id = Auth::id();
        }

        $paste->save();

        return redirect('/paste/' . $paste->hash);
    }
}

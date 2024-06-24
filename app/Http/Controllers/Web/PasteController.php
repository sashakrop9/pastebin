<?php

namespace App\Http\Controllers\Web;

use App\Models\Paste;
use Illuminate\Http\Request;
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

    public function create()
    {
        return view('paste.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'language' => 'required|string',
            'expires_in' => 'required|string',
            'access' => 'required|string',
        ]);

        $expiresAt = match ($data['expires_in']) {
            '10min' => Carbon::now()->addMinutes(10),
            '1hour' => Carbon::now()->addHour(),
            '3hours' => Carbon::now()->addHours(3),
            '1day' => Carbon::now()->addDay(),
            '1week' => Carbon::now()->addWeek(),
            '1month' => Carbon::now()->addMonth(),
            'never' => null,
        };

        $paste = Paste::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'hash' => Str::random(8),
            'title' => $data['title'],
            'content' => $data['content'],
            'language' => $data['language'],
            'expires_at' => $expiresAt,
            'access' => $data['access'],
        ]);

        return redirect('/paste/' . $paste->hash);
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
}

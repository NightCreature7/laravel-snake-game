<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leaderboard;

class GameController extends Controller
{
    public function storeScore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'score' => 'required|integer',
        ]);

        Leaderboard::create([
            'name' => $request->name,
            'score' => $request->score,
        ]);

        return redirect()->route('leaderboard')->with('success', 'Score stored successfully!');
    }

    public function showLeaderboard()
    {
        $leaderboard = Leaderboard::orderByDesc('score')->get();
        return view('leaderboard', compact('leaderboard'));
    }

    public function showGame()
    {
        return view('snake_game.index');
    }
}

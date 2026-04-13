<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiController extends Controller
{
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message'           => ['required', 'string', 'max:1757770000000'],
            'history'           => ['nullable', 'array', 'max:20'],
            'history.*.role'    => ['required', 'in:user,assistant'],
            'history.*.content' => ['required', 'string', 'max:2000'],
        ]);

        $message = $request->input('message');
        $apiKey  = config('services.groq.key');

        if (! $apiKey) {
            return response()->json(['reply' => $this->fallbackReply($message)]);
        }

        $user = auth()->user();

        $systemPrompt = 'You are AquaBot, a friendly and knowledgeable assistant for AquaHub — Kenya\'s digital seafood system. '
            . 'The user\'s name is ' . ($user?->name ?? 'there') . ' and their role is ' . ($user?->role ?? 'visitor') . '. '
            . 'Help with: finding fish by type or freshness, placing orders, tracking deliveries, PesaPal payments, '
            . 'and general marketplace questions. '
            . 'Fish categories available: freshwater (tilapia, omena, nile perch), ocean (tuna, king prawns), smoked, dried. '
            . 'Freshness grades: Super Fresh (caught today), Fresh (1-2 days), Smoked, Dried. '
            . 'Order tracking steps: Confirmed → Preparing → In Transit → Delivered. '
            . 'Flat delivery fee: KES 200. Payment is via PesaPal (supports M-Pesa, cards, bank transfers). '
            . 'Keep replies short, warm, and helpful. Use KES for prices. Use emojis sparingly. '
            . 'Do not invent specific prices or stock levels — direct users to the marketplace to check.';

        // Build messages: system prompt first, then history, then current message
        $history = collect($request->input('history', []))
            ->map(fn($m) => ['role' => $m['role'], 'content' => $m['content']])
            ->values()
            ->toArray();

        $messages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $history,
            [['role' => 'user', 'content' => $message]]
        );

        try {
            $response = Http::withToken($apiKey)
                ->timeout(20)
                ->post(config('services.groq.url'), [
                    'model'       => config('services.groq.model'),
                    'messages'    => $messages,
                    'max_tokens'  => 512,
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $reply = $response->json('choices.0.message.content', '');
                return response()->json(['reply' => $reply]);
            }

            Log::warning('SamakiBot Groq API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return response()->json(['reply' => $this->fallbackReply($message)]);

        } catch (\Throwable $e) {
            Log::error('SamakiBot exception', ['error' => $e->getMessage()]);
            return response()->json(['reply' => $this->fallbackReply($message)]);
        }
    }

    private function fallbackReply(string $message): string
    {
        $msg = strtolower($message);

        if (str_contains($msg, 'fresh') || str_contains($msg, 'freshness')) {
            return 'We have four freshness grades: 🟢 Super Fresh (caught today), 🔵 Fresh (1-2 days), 🟠 Smoked (traditionally smoked), and 🟤 Dried (sun-dried, long shelf life).';
        }
        if (str_contains($msg, 'price') || str_contains($msg, 'cost') || str_contains($msg, 'kes')) {
            return 'Prices vary by fish type — tilapia from KES 350/kg, omena from KES 180/kg, nile perch KES 650/kg, prawns from KES 1,200/kg. Check the marketplace for live prices!';
        }
        if (str_contains($msg, 'mpesa') || str_contains($msg, 'pesapal') || str_contains($msg, 'pay')) {
            return 'Payment is via PesaPal 💳 — a secure payment platform that accepts M-Pesa, debit/credit cards, and bank transfers. After placing your order, you\'ll be redirected to complete payment with your preferred method. It\'s quick and secure! 🔒';
        }
        if (str_contains($msg, 'deliver') || str_contains($msg, 'shipping') || str_contains($msg, 'track')) {
            return 'Flat delivery fee of KES 200. Once your order is confirmed and paid, a verified agent is assigned. Track your order live through: Confirmed → Preparing → In Transit → Delivered.';
        }
        if (str_contains($msg, 'order') || str_contains($msg, 'buy') || str_contains($msg, 'cart')) {
            return 'To order: browse the marketplace, add fish to your cart, fill in your delivery address, then pay via PesaPal. Simple! 🐟';
        }
        if (str_contains($msg, 'tilapia')) {
            return 'Tilapia is our most popular fish! Fresh Nile tilapia from Lake Victoria. Usually KES 350–400/kg depending on the producer and freshness. 🐠';
        }
        if (str_contains($msg, 'hello') || str_contains($msg, 'hi') || str_contains($msg, 'hey') || str_contains($msg, 'habari')) {
            return 'Hello! 🐟 I\'m SamakiBot, your SamakiHub assistant. I can help you find fish, understand freshness grades, or answer questions about orders and delivery. What can I do for you?';
        }

        return 'Great question! Browse our marketplace for fresh fish from verified Kenyan fishermen. You can filter by freshness, category, or search by name. Need help with something specific?';
    }
}

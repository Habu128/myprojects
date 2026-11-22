<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AssistantController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'messages' => ['required', 'array', 'min:1', 'max:10'],
            'messages.*.role' => ['required', 'string', 'in:user,assistant'],
            'messages.*.content' => ['required', 'string', 'max:1200'],
        ]);
        $lastMessage = $validated['messages'][array_key_last($validated['messages'])];

        if ($lastMessage['role'] !== 'user') {
            return response()->json([
                'message' => 'Send a question to continue the conversation.',
            ], 422);
        }

        $apiKey = config('services.openai.key');

        if (! is_string($apiKey) || $apiKey === '') {
            return response()->json([
                'message' => 'The local information assistant is not connected yet. Please contact the council for help.',
            ], 503);
        }

        $messages = array_map(
            static fn (array $message): array => [
                'role' => $message['role'],
                'content' => $message['content'],
            ],
            $validated['messages'],
        );

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(25)
            ->post('https://api.openai.com/v1/responses', [
                'model' => config('services.openai.model'),
                'instructions' => implode(' ', [
                    'You are the public information assistant for Shani Local Government Area in Borno State, Nigeria.',
                    'Be warm, concise, and helpful to residents and visitors.',
                    'Help users navigate this website and find relevant public service information.',
                    'Only state local government facts explicitly supplied in this conversation or on this website.',
                    'Do not invent service requirements, fees, dates, policies, office locations, contact details, or announcements.',
                    'If a question needs official information you do not have, say so and advise the user to contact the relevant council office.',
                    'Do not ask for sensitive personal information.',
                ]),
                'input' => $messages,
                'max_output_tokens' => 350,
            ]);

        if (! $response->successful()) {
            return response()->json([
                'message' => 'The assistant could not respond right now. Please try again in a moment.',
            ], 502);
        }

        $reply = null;

        foreach ($response->json('output', []) as $outputItem) {
            foreach ($outputItem['content'] ?? [] as $contentItem) {
                if (($contentItem['type'] ?? null) === 'output_text') {
                    $reply = $contentItem['text'] ?? null;

                    break 2;
                }
            }
        }

        if (! is_string($reply) || trim($reply) === '') {
            return response()->json([
                'message' => 'The assistant could not respond right now. Please try again in a moment.',
            ], 502);
        }

        return response()->json([
            'reply' => trim($reply),
        ]);
    }
}

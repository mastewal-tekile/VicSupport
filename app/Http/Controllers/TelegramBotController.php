<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Container\Attributes\Log;

class TelegramBotController extends Controller
{
    protected $telegramApiUrl;

    public function __construct()
    {
        $this->telegramApiUrl = 'https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN');
    }

    public function webhook(Request $request)
    {
        // Log::info($request->all()); // Log incoming updates

        $update = $request->all();

        if (isset($update['message'])) {
            $chatId = $update['message']['chat']['id'];
            $text = $update['message']['text'];

            // Handle different inquiries
            switch (strtolower($text)) {
                case 'door types':
                    $this->sendMessage($chatId, "We offer wooden, fiberglass, and steel doors.");
                    break;
                case 'frame types':
                    $this->sendMessage($chatId, "Available frame types are standard and custom.");
                    break;
                case 'color selections':
                    $this->sendMessage($chatId, "We have a variety of colors: white, brown, black, and custom colors.");
                    break;
                case 'product details':
                    $this->sendMessage($chatId, "Our doors come in various sizes and materials. Please specify for details.");
                    break;
                case 'delivery time':
                    $this->sendMessage($chatId, "Delivery typically takes 4-6 weeks.");
                    break;
                default:
                    $this->sendMessage($chatId, "I'm sorry, I didn't understand that. You can ask about door types, frame types, color selections, product details, or delivery time.");
            }
        }

        return response()->json(['status' => 'ok']);
    }

    protected function sendMessage($chatId, $text)
    {
        $client = new Client();
        $client->post($this->telegramApiUrl . '/sendMessage', [
            'json' => [
                'chat_id' => $chatId,
                'text' => $text,
            ],
        ]);
    }
}
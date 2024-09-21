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
                    $this->sendMessage($chatId, "We offer Single frame, Extension frame, Double frame, and Romaniya frame.");
                    break;
                case 'color selections':
                    $this->sendMessage($chatId, "We have a variety of colors: white, brown, blue,yellow,mixed,other and custom colors.");
                    break;
                case 'product details':
                    $this->sendMessage($chatId, "
                            🚪Imported የምናደርጋቸው እጅግ ዘመናዊ የብረት በሮች ባህሪያቸው የእንጨት የሆኑ ናቸው

                            🔑ከ6-10 የቁልፍ ተወርዋሪ ያላቸው Secured master key ያላቸው ናቸው

                            ⭕️ 2 አመት ሙሉ ዋስትና ጋር በጥራት ሰርተን እናስረክቦታለን:: 

                            🚪ዙሪያቸውን ጎምኒ (Rubber Sill ) የተገጠመላቸው በመሆኑ ድምፅ የማያሳልፉ (Sound proof ) , ነፍሳት እና ተባዮችን በቀላሉ የማያሳልፉ ናቸው ።

                            ");
                    break;
                case 'delivery time':
                    $this->sendMessage($chatId, "Delivery typically takes 3-4 weeks.");
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
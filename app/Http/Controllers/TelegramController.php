<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Siswa;
use App\Models\Telegram_user;
use Illuminate\Http\Request;
use App\Services\TelegramBotService;

class TelegramController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramBotService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function handleWebhook(Request $request)
    {
        $message = $request->input('message');
        $chatId = $message['chat']['id'];
        $text = trim($message['text']);

        // Cek apakah telegram_id sudah terdaftar
        $existingUser = Telegram_user::where('telegram_id', $chatId)->first();

        if (!$existingUser) {
            // Jika user belum terverifikasi, meminta nomor HP
            if (preg_match('/^\d{10,15}$/', $text)) {
                $siswa = Siswa::where('no_hp', $text)->first();
                if ($siswa) {
                    // Simpan telegram_id ke tabel telegram_users
                    Telegram_user::create([
                        'siswa_id' => $siswa->id,
                        'telegram_id' => $chatId
                    ]);
                    $this->sendMenu($chatId, "Verifikasi berhasil. Silakan pilih menu:");
                } else {
                    $this->sendMessage($chatId, "Nomor HP tidak ditemukan. Pastikan sesuai dengan data yang terdaftar.");
                }
            } else {
                $this->sendMessage($chatId, "User tidak terverifikasi, silakan kirim nomor HP Anda terlebih dahulu.");
            }
        } else {
            // User sudah terverifikasi, tampilkan menu
            if ($text == 'Sabaq') {
                $this->sendSabaqHistory($chatId, $existingUser->siswa_id);
            }elseif ($text == 'Sabqi') {
                $this->sendSabqiHistory($chatId, $existingUser->siswa_id);
            } elseif ($text == 'Manzil') {
                $this->sendManzilHistory($chatId, $existingUser->siswa_id);
            } elseif ($text == 'Iqro') {
                $this->sendIqroHistory($chatId, $existingUser->siswa_id);
            } else {
                $this->sendMenu($chatId, "Selamat datang kembali! Silakan pilih menu:");
            }
        }
    }

    private function sendSabaqHistory($chatId, $siswaId)
{
    // Ambil 3 data terakhir dari sabaq_histories untuk siswa tertentu
    $sabaqHistories = Siswa::find($siswaId)->sabaqHistories()->latest()->take(3)->get();

    if ($sabaqHistories->isEmpty()) {
        $this->sendMessage($chatId, "Tidak ada sejarah Sabaq ditemukan.");
    } else {
        $message = "<pre>";
        
        // Menampilkan data dari setiap history
        foreach ($sabaqHistories as $history) {
            $suratName = $history->surat->sura_name;

            // Format teks menggunakan <pre> untuk tampilan monospasi
            $message .= "------------------------------------\n";
            $message .= "Tanggal    : " . \Carbon\Carbon::parse($history->tgl_sabaq)->format('d F Y') . "\n";
            $message .= "Nama Surat : " . $suratName . "\n";
            $message .= "Dari Ayat  : " . $history->dariayat . "\n";
            $message .= "Sampai Ayat: " . $history->sampaiayat . "\n";
            $message .= "Nilai      : " . $history->nilai . "\n";
            $message .= "Keterangan : " . $history->keterangan . "\n";
            $message .= "------------------------------------\n\n";
        }

        $message .= "</pre>";

        // Kirim pesan ke Telegram dengan teks monospasi
        $this->sendMessage($chatId, $message, 'HTML');
    }
}

    private function sendSabqiHistory($chatId, $siswaId)
{
    // Ambil 3 data terakhir dari sabaq_histories untuk siswa tertentu
    $sabqiHistories = Siswa::find($siswaId)->sabqiHistories()->latest()->take(3)->get();

    if ($sabqiHistories->isEmpty()) {
        $this->sendMessage($chatId, "Tidak ada sejarah Sabqi ditemukan.");
    } else {
        $message = "<pre>";
        
        // Menampilkan data dari setiap history
        foreach ($sabqiHistories as $history) {
            $suratName = $history->surat->sura_name;

            // Format teks menggunakan <pre> untuk tampilan monospasi
            $message .= "------------------------------------\n";
            $message .= "Tanggal    : " . \Carbon\Carbon::parse($history->tgl_sabqi)->format('d F Y') . "\n";
            $message .= "Nama Surat : " . $suratName . "\n";
            $message .= "Dari Ayat  : " . $history->dariayat . "\n";
            $message .= "Sampai Ayat: " . $history->sampaiayat . "\n";
            $message .= "Nilai      : " . $history->nilai . "\n";
            $message .= "Keterangan : " . $history->keterangan . "\n";
            $message .= "------------------------------------\n\n";
        }

        $message .= "</pre>";

        // Kirim pesan ke Telegram dengan teks monospasi
        $this->sendMessage($chatId, $message, 'HTML');
    }
}

    private function sendManzilHistory($chatId, $siswaId)
{
    // Ambil 3 data terakhir dari sabaq_histories untuk siswa tertentu
    $manzilHistories = Siswa::find($siswaId)->manzilHistories()->latest()->take(3)->get();

    if ($manzilHistories->isEmpty()) {
        $this->sendMessage($chatId, "Tidak ada sejarah manzil ditemukan.");
    } else {
        $message = "<pre>";
        
        // Menampilkan data dari setiap history
        foreach ($manzilHistories as $history) {
            $suratName = $history->surat->sura_name;

            // Format teks menggunakan <pre> untuk tampilan monospasi
            $message .= "------------------------------------\n";
            $message .= "Tanggal    : " . \Carbon\Carbon::parse($history->tgl_manzil)->format('d F Y') . "\n";
            $message .= "Nama Surat : " . $suratName . "\n";
            $message .= "Dari Ayat  : " . $history->dariayat . "\n";
            $message .= "Sampai Ayat: " . $history->sampaiayat . "\n";
            $message .= "Nilai      : " . $history->nilai . "\n";
            $message .= "Keterangan : " . $history->keterangan . "\n";
            $message .= "------------------------------------\n\n";
        }

        $message .= "</pre>";

        // Kirim pesan ke Telegram dengan teks monospasi
        $this->sendMessage($chatId, $message, 'HTML');
    }
}

private function sendIqroHistory($chatId, $siswaId)
{
    // Ambil 3 data terakhir dari sabaq_histories untuk siswa tertentu
    $iqroHistories = Siswa::find($siswaId)->iqroHistories()->latest()->take(3)->get();

    if ($iqroHistories->isEmpty()) {
        $this->sendMessage($chatId, "Tidak ada sejarah iqro ditemukan.");
    } else {
        $message = "<pre>";
        
        // Menampilkan data dari setiap history
        foreach ($iqroHistories as $history) {
            
            // Format teks menggunakan <pre> untuk tampilan monospasi
            $message .= "------------------------------------\n";
            $message .= "Tanggal    : " . \Carbon\Carbon::parse($history->tgl_iqro)->format('d F Y') . "\n";
            $message .= "Jilid Iqro : " . $history->iqro_jilid . "\n";
            $message .= "Halaman    : " . $history->halaman . "\n";
            $message .= "Nilai      : " . $history->nilai . "\n";
            $message .= "Keterangan : " . $history->keterangan . "\n";
            $message .= "------------------------------------\n\n";
        }

        $message .= "</pre>";

        // Kirim pesan ke Telegram dengan teks monospasi
        $this->sendMessage($chatId, $message, 'HTML');
    }
}


    private function sendMessage($chatId, $text, $parseMode = 'Markdown')
{
    $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage";
    $params = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => $parseMode,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Log request before executing
    logger('Sending message via cURL', ['url' => $url, 'params' => $params]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        // Log cURL errors
        logger('cURL Error: ' . curl_error($ch));
    } else {
        // Log the response from Telegram
        logger('Response from Telegram: ' . $response);
    }

    curl_close($ch);
}

    

    private function sendMenu($chatId, $text)
    {
        $keyboard = [
            'keyboard' => [
                [['text' => 'Sabaq']],
                [['text' => 'Sabqi']],
                [['text' => 'Manzil']],
                [['text' => 'Iqro']],
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
        ];

        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'reply_markup' => json_encode($keyboard)
        ];

        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            logger('Curl error: ' . curl_error($ch)); // log jika error
        }
        curl_close($ch);

        logger('Telegram sendMenu response: ' . $response); // log respon API
    }

    

    public function setWebhook()
    {
        $webhookUrl = 'https://cd49a6829c35.ngrok-free.app/telegram/webhook';
        $this->telegramService->setWebhook($webhookUrl);
        return response()->json(['status' => 'Webhook set successfully']);
    }

    
}

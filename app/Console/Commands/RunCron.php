<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class RunCron extends Command
{
    protected $signature = 'cron:run';

    protected $description = 'Run cron and email execution result';

    public function handle(): int
    {
        $startedAt = now();

        $logFile = storage_path(
            'logs/cron-' . $startedAt->format('Y-m-d_H-i-s') . '.log'
        );

        /*
         * Buat file log
         */
        file_put_contents(
            $logFile,
            "========================================\n" . "CRON START\n" . "========================================\n\n"
        );

        /*
         * Tangkap output PHP
         */
        ob_start();

        $exitCode = 0;

        try {

            $this->writeLog(
                $logFile,
                'CRON START: ' . $startedAt
            );

            /*
             * ==========================================
             * JALANKAN COMMAND ANDA
             * ==========================================
             */

            $this->call('google-sheet:sync-data-from-google-sheet');

            echo "Proses selesai\n";

            $this->info('Semua proses berhasil.');

            $status = 'SUCCESS';
        } catch (Throwable $e) {

            $status = 'FAILED';

            $exitCode = 1;

            $this->error(
                'ERROR: ' . $e->getMessage()
            );

            Log::channel('cron')->error(
                'Cron failed',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            $this->writeLog(
                $logFile,
                "EXCEPTION: " . $e->getMessage()
            );

            $this->writeLog(
                $logFile,
                "FILE: " . $e->getFile()
            );

            $this->writeLog(
                $logFile,
                "LINE: " . $e->getLine()
            );
        }

        /*
         * Ambil semua echo/output
         */
        $bufferOutput = ob_get_clean();

        if ($bufferOutput) {
            $this->writeLog(
                $logFile,
                $bufferOutput
            );
        }

        $finishedAt = now();

        $duration = $startedAt->diffInSeconds($finishedAt);

        $this->writeLog(
            $logFile,
            "\nSTATUS: " . $status
        );

        $this->writeLog(
            $logFile,
            "STARTED: " . $startedAt
        );

        $this->writeLog(
            $logFile,
            "FINISHED: " . $finishedAt
        );

        $this->writeLog(
            $logFile,
            "DURATION: " . $duration . " seconds"
        );

        $this->writeLog(
            $logFile,
            "\n========================================\n" . "CRON END\n" . "========================================\n"
        );

        /*
         * ==========================================
         * BACA HASIL CRON
         * ==========================================
         */

        $cronOutput = file_get_contents($logFile);

        /*
         * Tambahkan Laravel cron log
         */
        $laravelCronLog = storage_path('logs/cron.log');

        if (file_exists($laravelCronLog)) {

            $cronOutput .= "\n\n";
            $cronOutput .= "========================================\n";
            $cronOutput .= "LARAVEL LOG\n";
            $cronOutput .= "========================================\n\n";

            $cronOutput .= file_get_contents(
                $laravelCronLog
            );
        }

        /*
         * ==========================================
         * KIRIM EMAIL
         * ==========================================
         */

        try {

            Mail::raw(
                $cronOutput,
                function ($message) use ($status) {

                    $message
                        ->to(config('mail.cron_recipient'))
                        ->subject(
                            '[CRON ' . $status . '] ' . config('app.name')
                        );
                }
            );
        } catch (Throwable $e) {

            Log::error(
                'Cannot send cron email',
                [
                    'exception' => $e,
                ]
            );

            return 2;
        }

        /*
         * Hapus temporary log
         */
        @unlink($logFile);

        return $exitCode;
    }

    private function writeLog(
        string $file,
        string $message
    ): void {

        file_put_contents(
            $file,
            $message . "\n",
            FILE_APPEND
        );
    }
}

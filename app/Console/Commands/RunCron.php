<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class RunCron extends Command
{
    protected $signature = 'cron:run';

    protected $description = 'Run Google Sheet sync and email execution result';

    public function handle(): int
    {
        $startedAt = now();

        $logFile = storage_path(
            'logs/cron-' . $startedAt->format('Y-m-d_H-i-s') . '.log'
        );

        /*
         * ==========================================
         * CRON START
         * ==========================================
         */

        file_put_contents(
            $logFile,
            "========================================\n" . "CRON START\n" . "========================================\n\n"
        );

        $status = 'SUCCESS';
        $exitCode = 0;

        try {

            $this->writeLog(
                $logFile,
                'STARTED: ' . $startedAt
            );

            /*
             * ==========================================
             * JALANKAN COMMAND
             * ==========================================
             *
             * call() mengembalikan exit code.
             *
             * 0 = berhasil
             * selain 0 = gagal
             */

            $this->writeLog(
                $logFile,
                'Running: google-sheet:sync-data-from-google-sheet'
            );

            $result = Artisan::call(
                'google-sheet:sync-data-from-google-sheet'
            );

            /*
             * Simpan exit code
             */
            $exitCode = $result;

            /*
             * ==========================================
             * CAPTURE ARTISAN OUTPUT
             * ==========================================
             */

            $commandOutput = Artisan::output();

            if (!empty($commandOutput)) {

                $this->writeLog(
                    $logFile,
                    "\nCOMMAND OUTPUT:\n" .
                        $commandOutput
                );
            }

            /*
             * Jika command gagal, anggap CRON gagal
             */
            if ($result !== 0) {

                $status = 'FAILED';

                $this->writeLog(
                    $logFile,
                    'Command failed with exit code: ' . $result
                );

                $this->error(
                    'Google Sheet sync FAILED. Exit code: ' . $result
                );
            } else {

                $this->writeLog(
                    $logFile,
                    'Google Sheet sync completed successfully.'
                );

                $this->info(
                    'Google Sheet sync completed successfully.'
                );
            }
        } catch (Throwable $e) {

            $status = 'FAILED';
            $exitCode = 1;

            /*
             * ==========================================
             * EXCEPTION
             * ==========================================
             */

            $this->error(
                'ERROR: ' . $e->getMessage()
            );

            $this->writeLog(
                $logFile,
                'EXCEPTION: ' . get_class($e)
            );

            $this->writeLog(
                $logFile,
                'MESSAGE: ' . $e->getMessage()
            );

            $this->writeLog(
                $logFile,
                'FILE: ' . $e->getFile()
            );

            $this->writeLog(
                $logFile,
                'LINE: ' . $e->getLine()
            );

            $this->writeLog(
                $logFile,
                "TRACE:\n" . $e->getTraceAsString()
            );

            /*
             * Log ke channel cron
             */
            Log::channel('cron')->error(
                'Cron execution failed',
                [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );
        }

        /*
         * ==========================================
         * WAKTU SELESAI
         * ==========================================
         */

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
            "\n========================================\n" .
                "CRON END\n" .
                "========================================"
        );

        /*
         * ==========================================
         * BACA CRON LOG
         * ==========================================
         */

        $cronLogFile = storage_path('logs/cron.log');

        $cronLog = '';

        if (file_exists($cronLogFile)) {

            $cronLog = file_get_contents($cronLogFile);
        }

        /*
         * ==========================================
         * HASIL AKHIR
         * ==========================================
         */

        $cronOutput = file_get_contents($logFile);

        if (!empty($cronLog)) {

            $cronOutput .=
                "\n\n" .
                "========================================\n" .
                "CRON LOG\n" .
                "========================================\n\n" .
                $cronLog;
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
                            '[CRON ' .
                                $status .
                                '] ' .
                                config('app.name')
                        );
                }
            );

            if (file_exists($cronLogFile)) {
                file_put_contents($cronLogFile, '');
            }
        } catch (Throwable $e) {

            /*
             * Email gagal dikirim.
             */
            Log::error(
                'Cannot send cron email',
                [
                    'exception' => $e,
                ]
            );

            /*
             * Jangan hapus log jika email gagal,
             * supaya masih bisa diperiksa.
             */
            return 2;
        }

        /*
         * Email berhasil dikirim,
         * hapus temporary log.
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

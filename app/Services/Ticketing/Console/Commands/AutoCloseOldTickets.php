<?php

namespace App\Services\Ticketing\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Ticketing\Models\Laporan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoCloseOldTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:autoclose';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically closes tickets that are awaiting reporter confirmation for more than 24 hours.';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting auto-close process for old tickets...');
        Log::info('Scheduler tickets:autoclose - Running');

        $batasWaktu = Carbon::now()->subHours(24);

        $tiketsToClose = Laporan::where('STATUS', 'Menunggu Konfirmasi Pelapor')
                                ->where('TGL_SELESAI', '<=', $batasWaktu)
                                ->get();

        if ($tiketsToClose->isEmpty()) {
            $this->info('No tickets to auto-close.');
            Log::info('Scheduler tickets:autoclose - No tickets to auto-close.');
            return 0;
        }

        foreach ($tiketsToClose as $tiket) {
            $aktor = "Sistem";
            $tanggalAksi = Carbon::now()->toDateTimeString();
            $judulAksi = "Ditutup Otomatis";
            $deskripsiAksi = "Tiket ditutup otomatis karena tidak ada tanggapan dari pelapor dalam 1x24 jam setelah tindak lanjut internal.";

            $keteranganSebelumnya = $tiket->KETERANGAN ? trim($tiket->KETERANGAN) . ';;' : '';

            $tiket->STATUS = 'Close'; // Atau 'Close (Otomatis)'
            $tiket->KETERANGAN = $keteranganSebelumnya . "{$tanggalAksi}|{$aktor}|{$judulAksi}|{$deskripsiAksi}";
            $tiket->TGL_UPDATE = Carbon::now(); // Untuk memicu updated_at
            $tiket->save();

            $this->info("Ticket {$tiket->ID_COMPLAINT} auto-closed.");
            Log::info("Scheduler tickets:autoclose - Ticket {$tiket->ID_COMPLAINT} auto-closed.");
        }

        $this->info('Auto-close process finished. ' . $tiketsToClose->count() . ' tickets processed.');
        Log::info('Scheduler tickets:autoclose - Finished. ' . $tiketsToClose->count() . ' tickets processed.');
        return 0;
    }
}

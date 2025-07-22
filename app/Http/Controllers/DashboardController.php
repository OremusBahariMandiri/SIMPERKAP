<?php

namespace App\Http\Controllers;

use App\Models\A03DmPerusahaan;
use App\Models\A05DmKapal;
use App\Models\B01DokumenKpl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Count total data
        $totalPerusahaan = A03DmPerusahaan::count();
        $totalKapal = A05DmKapal::count();
        $totalDokumen = B01DokumenKpl::count();

        // Count dokumen status
        $expiredDokumen = B01DokumenKpl::whereDate('tgl_berakhir_dok', '<', now())->count();
        $warningDokumen = B01DokumenKpl::whereDate('tgl_peringatan', '<=', now())
            ->whereDate('tgl_berakhir_dok', '>=', now())
            ->count();
        $validDokumen = B01DokumenKpl::whereDate('tgl_peringatan', '>', now())->count();

        // Get recent dokumen
        $recentDokumen = B01DokumenKpl::with(['kapal', 'kategoriDokumen', 'namaDokumen'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get upcoming expired documents
        $upcomingExpired = B01DokumenKpl::with(['kapal', 'kategoriDokumen', 'namaDokumen'])
            ->whereDate('tgl_berakhir_dok', '>=', now())
            ->orderBy('tgl_berakhir_dok', 'asc')
            ->limit(5)
            ->get();

        // Count dokumen by kapal
        $dokumenByKapal = A05DmKapal::withCount('dokumenKapal')
            ->orderBy('dokumen_kapal_count', 'desc')
            ->limit(5)
            ->get();

        // Count dokumen by kategori
        $dokumenByKategori = B01DokumenKpl::select('kategori_dok')
            ->selectRaw('count(*) as total')
            ->groupBy('kategori_dok')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Prepare data for dokumen status by month chart
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        $dokumenStatusByMonth = [];

        for ($i = 0; $i < 6; $i++) {
            $month = $sixMonthsAgo->copy()->addMonths($i);
            $nextMonth = $month->copy()->addMonth();

            $expired = B01DokumenKpl::whereDate('tgl_berakhir_dok', '<', $nextMonth)
                ->whereDate('tgl_berakhir_dok', '>=', $month)
                ->count();

            $warning = B01DokumenKpl::whereDate('tgl_peringatan', '<=', $nextMonth)
                ->whereDate('tgl_peringatan', '>=', $month)
                ->count();

            $dokumenStatusByMonth[] = [
                'month' => $month->format('M Y'),
                'expired' => $expired,
                'warning' => $warning,
            ];
        }

        return view('dashboard', compact(
            'totalPerusahaan',
            'totalKapal',
            'totalDokumen',
            'expiredDokumen',
            'warningDokumen',
            'validDokumen',
            'recentDokumen',
            'upcomingExpired',
            'dokumenByKapal',
            'dokumenByKategori',
            'dokumenStatusByMonth'
        ));
    }
}
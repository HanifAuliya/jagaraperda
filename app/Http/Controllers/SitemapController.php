<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Response;
use App\Models\News;
use App\Models\Raperda;

class SitemapController extends Controller
{
    public function index(): Response
    {
        // Cache 12 jam biar hemat query & cepat
        $xml = Cache::remember('sitemap.xml', now()->addHours(12), function () {
            $base = rtrim(config('app.url') ?? URL::to('/'), '/');

            // ===== URL statis utama (halaman publik) =====
            $static = [
                ['loc' => route('home'),                'priority' => '1.0', 'changefreq' => 'weekly', 'lastmod' => now()],
                ['loc' => route('publikasi.index'),     'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => now()],
                ['loc' => route('news.index'),          'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => now()],
                ['loc' => route('aspirasi.form'),       'priority' => '0.7', 'changefreq' => 'weekly', 'lastmod' => now()],
                ['loc' => route('aspirasi.tracking'),   'priority' => '0.7', 'changefreq' => 'daily',  'lastmod' => now()],
                ['loc' => route('galeri.index'),        'priority' => '0.6', 'changefreq' => 'weekly', 'lastmod' => now()],
                ['loc' => route('kontak'),              'priority' => '0.5', 'changefreq' => 'monthly', 'lastmod' => now()],
                // Tambah halaman publik lain di sini kalau ada
            ];

            // ===== URL dinamis: Raperda =====
            // Asumsi: kolom 'slug' ada, dan pakai route('publikasi.show', $raperda->slug)
            $raperdas = Raperda::query()
                ->select(['slug', 'updated_at', 'created_at'])
                ->orderByDesc('updated_at')
                ->get()
                ->map(function ($r) {
                    return [
                        'loc'        => route('publikasi.show', ['raperda' => $r->slug]),
                        'priority'   => '0.85',
                        'changefreq' => 'weekly',
                        'lastmod'    => $r->updated_at ?? $r->created_at ?? now(),
                    ];
                })
                ->all();

            // ===== URL dinamis: Berita (hanya yang active) =====
            $news = News::query()
                ->where('active', true)
                ->select(['slug', 'updated_at', 'created_at'])
                ->orderByDesc('updated_at')
                ->get()
                ->map(function ($n) {
                    return [
                        'loc'        => route('news.show', ['news' => $n->slug]),
                        'priority'   => '0.75',
                        'changefreq' => 'weekly',
                        'lastmod'    => $n->updated_at ?? $n->created_at ?? now(),
                    ];
                })
                ->all();

            // Gabungkan semua URL
            $urls = array_merge($static, $raperdas, $news);

            // Bangun XML manual
            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->formatOutput = true;

            $urlset = $dom->createElement('urlset');
            $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
            $dom->appendChild($urlset);

            foreach ($urls as $u) {
                $url = $dom->createElement('url');

                $loc = $dom->createElement('loc', htmlspecialchars($u['loc'], ENT_QUOTES | ENT_XML1, 'UTF-8'));
                $url->appendChild($loc);

                if (!empty($u['lastmod'])) {
                    $lastmod = \Illuminate\Support\Carbon::parse($u['lastmod'])->toAtomString(); // ISO 8601
                    $url->appendChild($dom->createElement('lastmod', $lastmod));
                }

                if (!empty($u['changefreq'])) {
                    $url->appendChild($dom->createElement('changefreq', $u['changefreq']));
                }

                if (!empty($u['priority'])) {
                    $url->appendChild($dom->createElement('priority', $u['priority']));
                }

                $urlset->appendChild($url);
            }

            return $dom->saveXML();
        });

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}

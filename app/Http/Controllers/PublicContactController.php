<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Str;

class PublicContactController extends Controller
{
    public function index()
    {
        $contactAddress = Setting::get('contact_address', '');
        $contactMapEmbed = Setting::get('contact_map_embed', '');
        $addresses = $this->splitContactValues($contactAddress);

        $settings = [
            'contact_email' => Setting::get('contact_email', ''),
            'contact_phone' => Setting::get('contact_phone', ''),
            'contact_address' => $contactAddress,
            'contact_addresses' => $addresses,
            'contact_maps' => $this->contactMaps($addresses, $contactMapEmbed),
            'contact_map_embed' => $contactMapEmbed,
            'social_facebook' => Setting::get('social_facebook', ''),
            'social_instagram' => Setting::get('social_instagram', ''),
            'social_youtube' => Setting::get('social_youtube', ''),
            'social_whatsapp' => Setting::get('social_whatsapp', ''),
            'social_linkedin' => Setting::get('social_linkedin', ''),
            'social_twitter' => Setting::get('social_twitter', ''),
        ];

        return view('contact.index', compact('settings'));
    }

    private function splitContactValues(?string $value): array
    {
        $parts = preg_split('/\r\n|\r|\n|\s+\|\s+|\|/', (string) $value);

        return collect($parts)
            ->map(fn ($item) => trim(strip_tags($item)))
            ->filter()
            ->values()
            ->all();
    }

    private function contactMaps(array $addresses, ?string $mapEmbed): array
    {
        $embedSources = $this->extractMapSources($mapEmbed);

        return collect($addresses)
            ->map(function (string $address, int $index) use ($embedSources) {
                return [
                    'address' => $address,
                    'src' => $embedSources[$index] ?? $this->googleMapsEmbedUrl($address),
                ];
            })
            ->values()
            ->all();
    }

    private function extractMapSources(?string $mapEmbed): array
    {
        preg_match_all('/<iframe\b[^>]*\bsrc=["\']([^"\']+)["\'][^>]*>/i', (string) $mapEmbed, $matches);

        $sources = collect($matches[1] ?? [])
            ->map(fn ($src) => html_entity_decode(trim($src), ENT_QUOTES, 'UTF-8'));

        if ($sources->isEmpty()) {
            $sources = collect(preg_split('/\r\n|\r|\n|\|/', (string) $mapEmbed))
                ->map(fn ($src) => trim(html_entity_decode($src, ENT_QUOTES, 'UTF-8')));
        }

        return $sources
            ->filter(fn ($src) => Str::startsWith($src, ['https://www.google.com/maps', 'https://maps.google.com']))
            ->values()
            ->all();
    }

    private function googleMapsEmbedUrl(string $address): string
    {
        return 'https://www.google.com/maps?q='.rawurlencode($address).'&output=embed';
    }
}

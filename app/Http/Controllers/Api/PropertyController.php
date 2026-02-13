<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;



class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $response = Http::timeout(10)
            ->retry(2, 200)
            ->get('https://biim.ci/wp-json/wp/v2/property', [
                'page' => $request->page ?? 1,
                '_embed' => true
            ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Erreur récupération WordPress'
            ], 500);
        }

        $properties = $response->json();

        $formatted = collect($properties)->map(function ($item) {

            $metas = $item['all_metas'] ?? [];
            $classList = $item['class_list'] ?? [];

            // Extraction simple sans optional()
            $extract = function ($prefix) use ($classList) {
                foreach ($classList as $class) {
                    if (Str::startsWith($class, $prefix)) {
                        return Str::after($class, $prefix);
                    }
                }
                return null;
            };

            return [
                "id" => $item['id'],
                "libelle" => $item['title']['rendered'] ?? '',

                "city" => str_replace('-', ' ', $extract('property-city-')),
                "neighborhood" => str_replace('-', ' ', $extract('property-neighborhood-')),

                "price" => (int) ($metas['real_estate_property_price'] ?? 0),

                "rooms" => (int) ($metas['real_estate_property_rooms'] ?? 0),
                "bedrooms" => (int) ($metas['real_estate_property_bedrooms'] ?? 0),
                "bathrooms" => (int) ($metas['real_estate_property_bathrooms'] ?? 0),

                "address" => $metas['real_estate_property_address'] ?? null,
                "availability" => $metas['real_estate_disponibilite'] ?? null,

                "views" => (int) ($metas['real_estate_property_views_count'] ?? 0),

                "cover_image" => $item['_embedded']['wp:featuredmedia'][0]['source_url'] ?? null,
            ];
        });

        return response()->json([
            "property" => $formatted->values()
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $response = Http::timeout(10)
            ->retry(2, 200)
            ->get("https://biim.ci/wp-json/wp/v2/property/{$id}", [
                '_embed' => true
            ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Erreur récupération WordPress'
            ], 500);
        }

        $item = $response->json(); // ✅ objet simple

        /*
    |--------------------------------------------------------------------------
    | 1️⃣ Récupération des IDs images galerie
    |--------------------------------------------------------------------------
    */
        $metas = $item['all_metas'] ?? [];

        $galleryIds = !empty($metas['real_estate_property_images'])
            ? explode('|', $metas['real_estate_property_images'])
            : [];

        /*
    |--------------------------------------------------------------------------
    | 2️⃣ Récupération des images galerie
    |--------------------------------------------------------------------------
    */
        $galleryUrls = [];

        if (!empty($galleryIds)) {

            $mediaResponse = Http::timeout(10)
                ->retry(2, 200)
                ->get("https://biim.ci/wp-json/wp/v2/media", [
                    'include' => implode(',', $galleryIds)
                ]);

            if ($mediaResponse->successful()) {
                $galleryUrls = collect($mediaResponse->json())
                    ->pluck('source_url')
                    ->values();
            }
        }

        /*
    |--------------------------------------------------------------------------
    | 3️⃣ Extraction des class_list
    |--------------------------------------------------------------------------
    */
        $classList = collect($item['class_list'] ?? []);

        $extract = function ($prefix) use ($classList) {
            $value = $classList->first(fn($c) => Str::startsWith($c, $prefix));
            return $value ? Str::after($value, $prefix) : null;
        };

        $features = $classList
            ->filter(fn($c) => Str::startsWith($c, 'property-feature-'))
            ->map(fn($c) => str_replace('-', ' ', Str::after($c, 'property-feature-')))
            ->values();

        /*
    |--------------------------------------------------------------------------
    | 4️⃣ Cover image
    |--------------------------------------------------------------------------
    */
        $coverImage = $item['_embedded']['wp:featuredmedia'][0]['source_url'] ?? null;

        /*
    |--------------------------------------------------------------------------
    | 5️⃣ Formatage final
    |--------------------------------------------------------------------------
    */
        $formatted = [
            "id" => $item['id'],
            "libelle" => $item['title']['rendered'] ?? '',
            "lien" => $item['link'],
            "description" => strip_tags($item['content']['rendered'] ?? ''),

            "type" => $extract('property-type-'),
            "city" => str_replace('-', ' ', $extract('property-city-')),
            "neighborhood" => str_replace('-', ' ', $extract('property-neighborhood-')),
            "features" => $features,

            "price" => (int) ($metas['real_estate_property_price'] ?? 0),
            "price_short" => (int) ($metas['real_estate_property_price_short'] ?? 0),
            "price_postfix" => $metas['real_estate_property_price_postfix'] ?? null,

            "rooms" => (int) ($metas['real_estate_property_rooms'] ?? 0),
            "bedrooms" => (int) ($metas['real_estate_property_bedrooms'] ?? 0),
            "bathrooms" => (int) ($metas['real_estate_property_bathrooms'] ?? 0),

            "address" => $metas['real_estate_property_address'] ?? null,
            "availability" => $metas['real_estate_disponibilite'] ?? null,

            "contact_name" => $metas['real_estate_property_other_contact_name'] ?? null,
            "contact_phone" => $metas['real_estate_property_other_contact_phone'] ?? null,

            "views" => (int) ($metas['real_estate_property_views_count'] ?? 0),

            "gallery" => $galleryUrls,

            "location" => isset($metas['real_estate_property_location'])
                ? @unserialize($metas['real_estate_property_location'])
                : null,

            "label" => str_replace('-', ' ', $extract('property-label-')),
            "cover_image" => $coverImage,

            "is_active" => $item['status'] === 'publish' ? 1 : 0,
            "user_enreg" => $item['author'] ?? 1,

            "created_at" => Carbon::parse($item['date']),
            "updated_at" => Carbon::parse($item['modified']),
        ];

        return response()->json([
            "property_show" => $formatted
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

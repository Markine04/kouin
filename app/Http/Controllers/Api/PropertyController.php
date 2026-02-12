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

        $properties = collect($response->json());

        /*
    |--------------------------------------------------------------------------
    | 1️⃣ Récupération globale de TOUS les IDs images
    |--------------------------------------------------------------------------
    */
        $allGalleryIds = $properties->pluck('all_metas.real_estate_property_images')
            ->filter()
            ->flatMap(fn($ids) => explode('|', $ids))
            ->unique()
            ->values();

        /*
    |--------------------------------------------------------------------------
    | 2️⃣ Requête unique pour toutes les images
    |--------------------------------------------------------------------------
    */
        $galleryMap = [];

        if ($allGalleryIds->isNotEmpty()) {

            $mediaResponse = Http::timeout(10)
                ->retry(2, 200)
                ->get("https://biim.ci/wp-json/wp/v2/media", [
                    'include' => $allGalleryIds->implode(',')
                ]);

            if ($mediaResponse->successful()) {

                $galleryMap = collect($mediaResponse->json())
                    ->mapWithKeys(fn($media) => [
                        $media['id'] => $media['source_url']
                    ])
                    ->toArray();
            }
        }

        /*
    |--------------------------------------------------------------------------
    | 3️⃣ Formatage des propriétés (SANS requête HTTP dedans)
    |--------------------------------------------------------------------------
    */
        $formatted = $properties->map(function ($item) use ($galleryMap) {

            $coverImage = $item['_embedded']['wp:featuredmedia'][0]['source_url'] ?? null;

            $classList = collect($item['class_list'] ?? []);

            $extract = function ($prefix) use ($classList) {
                return optional(
                    $classList->first(fn($c) => Str::startsWith($c, $prefix))
                ) ? Str::after(
                    $classList->first(fn($c) => Str::startsWith($c, $prefix)),
                    $prefix
                ) : null;
            };

            $features = $classList
                ->filter(fn($c) => Str::startsWith($c, 'property-feature-'))
                ->map(fn($c) => str_replace('-', ' ', Str::after($c, 'property-feature-')))
                ->values();

            $metas = $item['all_metas'] ?? [];

            /*
        |--------------------------------------------------------------------------
        | Gallery via Map (aucune requête ici)
        |--------------------------------------------------------------------------
        */
            $galleryIds = !empty($metas['real_estate_property_images'])
                ? explode('|', $metas['real_estate_property_images'])
                : [];

            $galleryUrls = collect($galleryIds)
                ->map(fn($id) => $galleryMap[$id] ?? null)
                ->filter()
                ->values();

            return [
                "id" => $item['id'],
                "libelle" => $item['title']['rendered'] ?? '',
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
    public function show(string $id)
    {
        //
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

<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;



class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function home(Request $request)
    {
        $search = $request->search ?? null;

        $queryParams = [
            'per_page' => 100,              // Réduit : on prend 20 pour avoir de la marge après tri
            // 'orderby'  => 'meta_value_num', // Tri par vues côté WordPress
            // 'meta_key' => 'real_estate_property_views_count',
            // 'order'    => 'desc',
            // '_fields'  => implode(',', [   // On ne récupère que les champs nécessaires
            //     'id',
            //     'title',
            //     'date',
            //     'class_list',
            //     'all_metas',
            //     '_embedded',
            // ]),
            // '_embed'   => 'wp:featuredmedia', // Embed uniquement les médias
            '_embed' => true
        ];

        if ($search) {
            $queryParams['search'] = $search;
        }

        $response = Http::timeout(20)
            ->retry(2, 200)
            ->get('https://biim.ci/wp-json/wp/v2/property', $queryParams);

        if ($response->status() === 400) {
            return response()->json(['message' => 'Page inexistante'], 404);
        }

        if (!$response->successful()) {
            return response()->json(['message' => 'Erreur récupération WordPress'], 500);
        }

        $formatted = collect($response->json())
            ->take(5)
            ->map(function ($item) {
                $metas     = $item['all_metas'] ?? [];
                $classList = $item['class_list'] ?? [];

                $extract = function (string $prefix) use ($classList): ?string {
                    foreach ($classList as $class) {
                        if (str_starts_with($class, $prefix)) {
                            return str_replace('-', ' ', substr($class, strlen($prefix)));
                        }
                    }
                    return null;
                };

                return [
                    'id'           => $item['id'],
                    'libelle'      => $item['title']['rendered'] ?? '',
                    'city'         => $extract('property-city-'),
                    'neighborhood' => $extract('property-neighborhood-'),
                    'price'        => (int) ($metas['real_estate_property_price'] ?? 0),
                    'rooms'        => (int) ($metas['real_estate_property_rooms'] ?? 0),
                    'bedrooms'     => (int) ($metas['real_estate_property_bedrooms'] ?? 0),
                    'bathrooms'    => (int) ($metas['real_estate_property_bathrooms'] ?? 0),
                    'address'      => $metas['real_estate_property_address'] ?? null,
                    'availability' => $metas['real_estate_disponibilite'] ?? null,
                    'views'        => (int) ($metas['real_estate_property_views_count'] ?? 0),
                    'cover_image'  => $item['_embedded']['wp:featuredmedia'][0]['source_url'] ?? null,
                ];
            });

        $a_la_une = collect($response->json())
            ->take(3)
            ->map(function ($item) {
                $metas     = $item['all_metas'] ?? [];
                $classList = $item['class_list'] ?? [];

                $extract = function (string $prefix) use ($classList): ?string {
                    foreach ($classList as $class) {
                        if (str_starts_with($class, $prefix)) {
                            return str_replace('-', ' ', substr($class, strlen($prefix)));
                        }
                    }
                    return null;
                };

                return [
                    'id'           => $item['id'],
                    'libelle'      => $item['title']['rendered'] ?? '',
                    'city'         => $extract('property-city-'),
                    'neighborhood' => $extract('property-neighborhood-'),
                    'price'        => (int) ($metas['real_estate_property_price'] ?? 0),
                    'rooms'        => (int) ($metas['real_estate_property_rooms'] ?? 0),
                    'bedrooms'     => (int) ($metas['real_estate_property_bedrooms'] ?? 0),
                    'bathrooms'    => (int) ($metas['real_estate_property_bathrooms'] ?? 0),
                    'address'      => $metas['real_estate_property_address'] ?? null,
                    'availability' => $metas['real_estate_disponibilite'] ?? null,
                    'views'        => (int) ($metas['real_estate_property_views_count'] ?? 0),
                    'cover_image'  => $item['_embedded']['wp:featuredmedia'][0]['source_url'] ?? null,
                ];
            });


        return response()->json([
            'data' => $formatted->sortByDesc('views')->values(),
            'a_la_une' => $a_la_une->sortByDesc('id')->values(),
        ]);
    }

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 50;
        $search = $request->search ?? null;

        $queryParams = [
            'page' => $page,
            'per_page' => $perPage,
            '_embed' => true,
        ];

        if ($search) {
            $queryParams['search'] = $search;
        }

        $response = Http::timeout(10)
            ->retry(2, 200)
            ->get('https://biim.ci/wp-json/wp/v2/property', $queryParams);

        if ($response->status() === 400) {
            return response()->json([
                'message' => 'Page inexistante'
            ], 404);
        }

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Erreur récupération WordPress'
            ], 500);
        }

        $properties = collect($response->json());

        $formatted = $properties->map(function ($item) {

            $metas = $item['all_metas'] ?? [];
            $classList = $item['class_list'] ?? [];

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
                "created_at" => Carbon::parse($item['date']),
            ];
        });

        return response()->json([
            "current_page" => (int) $page,
            "per_page" => (int) $perPage,
            "total" => (int) $response->header('X-WP-Total'),
            "total_pages" => (int) $response->header('X-WP-TotalPages'),
            "data" => $formatted->values(),
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
        $token = $request->token; // JWT WordPress
        $IDs = $request->IDs; // IDs des images déjà uploadées (optionnel)

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Token manquant'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ UPLOAD IMAGES
        |--------------------------------------------------------------------------
        */

        $uploadedIds = [];

        if ($request->hasFile('cover_image')) {

            foreach ($request->file('cover_image') as $image) {

                $uploadResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Disposition' => 'attachment; filename="'.$image->getClientOriginalName().'"',
                ])->attach(
                    'file',
                    file_get_contents($image->getRealPath()),
                    $image->getClientOriginalName()
                )->post('https://biim.ci/wp-json/wp/v2/media');

                if ($uploadResponse->successful()) {
                    $uploadedIds[] = $uploadResponse->json()['id'];
                } else {
                    Log::error('Erreur upload WP', $uploadResponse->json());
                }
            }
        }

        if (empty($uploadedIds)) {
            return response()->json([
                'status' => false,
                'message' => 'Aucune image uploadée'
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ PREPARATION DONNEES PROPERTY
        |--------------------------------------------------------------------------
        */

        $featuredMedia = $uploadedIds[0]; // Première image = mise en avant
        $gallery = implode('|', $uploadedIds);
        

        $propertyData = [
            'title'   => $request->title,
            'content' => json_encode($request->description),
            'status'  => 'pending',
            "slug" => str_replace(' ', '-', strtolower($request->title)),
            "type" => "property",
            'featured_media' => $featuredMedia,
            "link" => "https://biim.ci/property/".str_replace(' ', '-', strtolower($request->title)),
            'meta' => [
                "real_estate_property_price_unit" => "1",
                "real_estate_property_price_short" => $request->price ?? null,
                "real_estate_property_price" => $request->price ?? null,
                "real_estate_property_address" => $request->address ?? null,
                "real_estate_property_price" => $request->price ?? null,
                "real_estate_property_price_postfix" => $request->period ?? "NUITÉE",
                "real_estate_property_rooms" => $request->rooms ?? null,
                "real_estate_property_bathrooms" => $request->toilets ?? null,
                "real_estate_property_kitchens" => $request->kitchens ?? null,
                "real_estate_floors" => $request->floors ?? null,
                "real_estate_floors_enable" => $request->floors ?? null,
                "real_estate_property_other_contact_phone" => $request->contact_phone ?? null,
                "real_estate_property_images" => $gallery,
                "real_estate_disponibilite" => $request->disponibilite ?? "Disponible",
                // "real_estate_price" => $request->price ?? null,
                // "real_estate_price_short" => $request->price ?? null,
                "real_estate_negociations" => $request->negociations ?? null,
                "real_estate_property_other_contact_mail" => $request->contact_email ?? null,
                "real_estate_property_country" => $request->country ?? "CI",
                "real_estate_property_city" => $request->city ?? "Abidjan",
                // "real_estate_property_location" => serialize([
                //     'lat' => $request->latitude ?? null,
                //     'lng' => $request->longitude ?? null,
                // ]),
                "real_estate_property_identity" => $request->identity ?? null,
                "real_estate_property_year" => $request->year ?? null,
                "real_estate_property_other_contact_description" => $request->contact_description ?? null,
                "real_estate_documents-disponibles" => $request->documents ?? null,
                "real_estate_property_size" => $request->area ?? null,
                "real_estate_property_land" => $request->land ?? null,
                "real_estate_property_bedrooms" => $request->bedrooms ?? null,
                "real_estate_property_garage" => $request->garage ?? null,
                "real_estate_property_garage_size" => $request->garage_size ?? null,
            ],
            'class_list' => [
                "property-type-{$request->property_type}",
                "property-city-" . str_replace(' ', '-', $request->city),
                "property-neighborhood-" . str_replace(' ', '-', $request->neighborhood),
                "property-label-" . str_replace(' ', '-', $request->label),
                "property-status-" . str_replace(' ', '-', $request->status),
                "property-state-" . str_replace(' ', '-', $request->district),
                // Ajouter les features
                // ...collect($request->features ?? [])->map(fn($f) => "property-feature-" . str_replace(' ', '-', $f)),
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ INSERTION PROPERTY
        |--------------------------------------------------------------------------
        */

        $propertyResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->post('https://biim.ci/wp-json/wp/v2/property', $propertyData);

        if (!$propertyResponse->successful()) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur création propriété',
                'error' => $propertyResponse->json()
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Propriété créée avec succès',
            'data' => $propertyResponse->json()
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Cache::remember(
            "property_$id",
            600,
            function () use ($id) {
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
                        // $coverImage = $item['_embedded']['wp:featuredmedia'][0]['source_url'] ?? null;

                        /*
                |--------------------------------------------------------------------------
                | 5️⃣ Formatage final
                |--------------------------------------------------------------------------
                */

                $WhatsappLuxe = '+2250715056104';

                $WhatsappStandard = '+2250748044105';

                if ($extract('property-type-') === 'luxe') {
                    $contact = $WhatsappLuxe;
                } else {
                    $contact = $WhatsappStandard;
                }

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

                    "negociations" => $metas['real_estate_negociations'] ?? null,

                    "contact_whatsapp" => $contact,

                    "contact_name" => $metas['real_estate_property_other_contact_name'] ?? null,
                    "contact_phone" => $metas['real_estate_property_other_contact_phone'] ?? null,

                    "views" => (int) ($metas['real_estate_property_views_count'] ?? 0),

                    "gallery" => $galleryUrls,

                    "location" => isset($metas['real_estate_property_location'])
                        ? @unserialize($metas['real_estate_property_location'])
                        : null,

                    "label" => str_replace('-', ' ', $extract('property-label-')),
                    // "cover_image" => $coverImage,

                    "is_active" => $item['status'] === 'publish' ? 1 : 0,
                    "user_enreg" => $item['author'] ?? 1,

                    "created_at" => Carbon::parse($item['date']),
                    "updated_at" => Carbon::parse($item['modified']),
                ];

                return response()->json([
                    "property_show" => $formatted
                ]);
            }
        );
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

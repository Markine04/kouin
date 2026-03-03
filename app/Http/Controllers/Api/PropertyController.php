<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;



class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function home(Request $request)
    {
        $search = $request->search;

        $queryParams = [
            'per_page' => 10,
            '_embed'   => true, // IMPORTANT
        ];

        if ($search) {
            $queryParams['search'] = $search;
        }

        $response = Http::timeout(15)
            ->retry(2, 200)
            ->get('https://biim.ci/wp-json/wp/v2/property', $queryParams);

        if (!$response->successful()) {
            return response()->json(['message' => 'Erreur récupération WordPress'], 500);
        }

        $items = collect($response->json());

        // Fonction extraction optimisée
        $extractFromClass = function ($classList, $prefix) {
            foreach ($classList as $class) {
                if (strpos($class, $prefix) === 0) {
                    return str_replace('-', ' ', substr($class, strlen($prefix)));
                }
            }
            return null;
        };

        // Fonction format unique (évite duplication)
        $format = function ($item) use ($extractFromClass) {

            $metas     = $item['all_metas'] ?? [];
            $classList = $item['class_list'] ?? [];

            $extract = function ($prefix) use ($classList) {
                foreach ($classList as $class) {
                    if (Str::startsWith($class, $prefix)) {
                        return Str::after($class, $prefix);
                    }
                }
                return null;
            };

            $WhatsappLuxe = '+2250715056104';

                $WhatsappStandard = '+2250748044105';

                if ($extract('property-type-') === 'luxe') {
                    $contact = $WhatsappLuxe;
                } else {
                    $contact = $WhatsappStandard;
                }
            return [
                'id'           => $item['id'],
                'libelle'      => $item['title']['rendered'] ?? '',
                'city'         => $extractFromClass($classList, 'property-city-'),
                'neighborhood' => $extractFromClass($classList, 'property-neighborhood-'),
                'price'        => (int) ($metas['real_estate_property_price'] ?? 0),
                'rooms'        => (int) ($metas['real_estate_property_rooms'] ?? 0),
                'bedrooms'     => (int) ($metas['real_estate_property_bedrooms'] ?? 0),
                'bathrooms'    => (int) ($metas['real_estate_property_bathrooms'] ?? 0),
                'address'      => $metas['real_estate_property_address'] ?? null,
                'availability' => $metas['real_estate_disponibilite'] ?? null,
                'views'        => (int) ($metas['real_estate_property_views_count'] ?? 0),
                'contact_whatsapp' => $contact,
                // IMAGE SAFE
                'cover_image'  =>
                $item['_embedded']['wp:featuredmedia'][0]['source_url']
                    ?? $item['jetpack_featured_media_url']
                    ?? null,
            ];
        };

        $formatted = $items->map($format);

        return response()->json([
            'data'     => $formatted->sortByDesc('views')->take(5)->values(),
            'a_la_une' => $formatted->sortByDesc('id')->take(3)->values(),
        ]);
    }


    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 20;
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

        $total = (int) $response->header('X-WP-Total');
        $totalPages = (int) $response->header('X-WP-TotalPages');

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

                $WhatsappLuxe = '+2250715056104';

                $WhatsappStandard = '+2250748044105';

                if ($extract('property-type-') === 'luxe') {
                    $contact = $WhatsappLuxe;
                } else {
                    $contact = $WhatsappStandard;
                }

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
                "contact_whatsapp" => $contact,
                "views" => (int) ($metas['real_estate_property_views_count'] ?? 0),
                "cover_image" => $item['_embedded']['wp:featuredmedia'][0]['source_url'] ?? null,
                "created_at" => Carbon::parse($item['date'])->toDateTimeString(),
            ];
        });

        $paginator = new LengthAwarePaginator(
            $formatted,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return response()->json([
            'data' => $paginator->items(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
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

    private function wpClient($token)
    {
        return Http::withToken($token)
            ->timeout(20)
            ->retry(2, 300);
    }

    private function uploadToWordPress($file, $token)
    {
        if (!$file || !$file->isValid()) return null;

        $response = $this->wpClient($token)
            ->attach(
                'file',
                fopen($file->getRealPath(), 'r'), // ⚡ 70% moins gourmand que file_get_contents
                $file->getClientOriginalName()
            )
            ->post('https://biim.ci/wp-json/wp/v2/media');

        if (!$response->successful()) {
            throw new \Exception($response->body());
        }

        return $response->json()['id'] ?? null;
    }

    private function uploadGallery($files, $token)
    {
        if (!$files) return [];

        if (!is_array($files)) {
            $files = [$files];
        }

        $responses = Http::pool(
            fn($pool) =>
            collect($files)
                ->filter(fn($f) => $f->isValid())
                ->map(
                    fn($file) =>
                    $pool->withToken($token)
                        ->attach(
                            'file',
                            fopen($file->getRealPath(), 'r'),
                            $file->getClientOriginalName()
                        )
                        ->post('https://biim.ci/wp-json/wp/v2/media')
                )
                ->toArray()
        );

        return collect($responses)
            ->filter->successful()
            ->map(fn($r) => $r->json()['id'])
            ->values()
            ->toArray();
    }


    // public function store(Request $request)
    // {
    //     $token = $request->token;
    //     $user = $request->userID;

    //     if (!$token) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Token manquant'
    //         ], 401);
    //     }

    //     $uploadedIds = [];
    //     $featuredMediaId = null;

    //     try {
    //         if ($request->hasFile('cover_image')) {

    //             $file = $request->file('cover_image');

    //             if ($file && $file->isValid()) {
    //                 $imageResponse = Http::withToken($token)->attach(
    //                     'file',
    //                     file_get_contents($file->getRealPath()),
    //                     $file->getClientOriginalName()
    //                 )->post('https://biim.ci/wp-json/wp/v2/media');


    //                 if ($imageResponse->successful()) {

    //                     $featuredMediaId = $imageResponse->json()['id'];

    //                 } else {

    //                     return response()->json([
    //                         'status' => false,
    //                         'token' => $token,
    //                         'message' => 'Erreur lors de l’upload WordPress',
    //                         'error' => $imageResponse->body()
    //                     ], 500);
    //                 }
    //             }
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | 2️⃣ UPLOAD IMAGES DE GALERIE (MULTIPLE)
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($request->hasFile('gallery_images')) {

    //             // $uploadedIds = [];
    //             $galleryFiles = $request->file('gallery_images');

    //             if ($galleryFiles) {

    //                 if (!is_array($galleryFiles)) {
    //                     $galleryFiles = [$galleryFiles];
    //                 }

    //                 foreach ($galleryFiles as $image) {

    //                     if (!$image->isValid()) continue;

    //                     $uploadResponse = Http::withToken($token)->attach(
    //                         'file',
    //                         file_get_contents($image->getRealPath()),
    //                         $image->getClientOriginalName()
    //                     )->post('https://biim.ci/wp-json/wp/v2/media');

    //                     if ($uploadResponse->successful()) {
    //                         $uploadedIds[] = $uploadResponse->json()['id'];
    //                     } else {

    //                         return response()->json([
    //                             'status' => false,
    //                             'token' => $token,
    //                             'message' => 'Erreur lors de l’upload WordPress',
    //                             'error' => $uploadResponse->body()
    //                         ], 500);
    //                     }
    //                 }

    //             }

    //             if (empty($uploadedIds)) {
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => 'Aucune image uploadée'
    //                 ], 400);
    //             }
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Erreur upload galerie', [
    //             'message' => $e->getMessage(),
    //             'stack' => $e->getTraceAsString(),
    //         ]);

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Erreur lors de l’upload des images de galerie',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | 3️⃣ PREPARATION DONNEES PROPERTY
    //     |--------------------------------------------------------------------------
    //     */
    //     // dd($uploadedIds);
    //     $featuredMedia = $featuredMediaId; // Première image = cover
    //     $gallery = implode('|', $uploadedIds); // Le reste = galerie

    //     $recupererDonnéees = [
    //         "real_estate_property_price_short" => $request->price ?? '',
    //         'real_estate_property_price' => $request->price ?? '',
    //         'real_estate_property_price_postfix' => $request->period ?? 'NUITÉE',
    //         'real_estate_property_address' => $request->address ?? '',
    //         'real_estate_property_rooms' => $request->bedrooms ?? '',
    //         'real_estate_property_bathrooms' => $request->toilets ?? '',
    //         'real_estate_property_garage' => $request->garages ?? '',
    //         'real_estate_property_other_contact_name' => $request->contact_name ?? '',
    //         "real_estate_property_other_contact_phone" => $request->contact_phone ?? '',
    //         'real_estate_property_images' => $gallery,
    //         'real_estate_disponibilite' =>  $request->disponibilite ?? 'Disponible',
    //         'real_estate_negociations' => $request->negociation ?? '',
    //         'real_estate_property_other_contact_mail' => $request->contact_email ?? '',
    //         'real_estate_property_country' => 'CI',
    //         'real_estate_property_city' => $request->city ?? 'Abidjan',
    //         'real_estate_property_size' => $request->area ?? '',
    //         'real_estate_property_land' => $request->land_area ?? '',
    //         'real_estate_property_bedrooms' => $request->bedrooms ?? '',
    //     ];

    //     $description =
    //         "<!-- wp:paragraph --> <p>" . $request->description . "</p> <!-- /wp:paragraph -->";

    //     $propertyData = [
    //         'title'   => $request->title,
    //         'content' => $description,
    //         'status'  => 'pending',
    //         'slug'    => str_replace(' ', '-', strtolower($request->title)),
    //         'type'    => 'property',
    //         'featured_media' => $featuredMedia,
    //         'meta' => $recupererDonnéees,
    //         'property-status' => 693,
    //         'author'=> $user,
    //         'property-type' => $request->property_type ?? 21,
    //         'property-feature' => $request->input('amenities', []), // Tableau d'IDs des features
    //         'property-city' => $request->city_id ?? null,
    //         'property-neighborhood' => $request->neighborhood_id ?? null,

            
    //     ];

    //     /*
    //     |--------------------------------------------------------------------------
    //     | 4️⃣ INSERTION PROPERTY
    //     |--------------------------------------------------------------------------
    //     */

    //     $propertyResponse = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $token,
    //         'Content-Type' => 'application/json',
    //         'accept' => 'application/json',
    //     ])
    //         ->post('https://biim.ci/wp-json/wp/v2/property', $propertyData);
        
    //     if ($propertyResponse->successful()) {
    //         return response()->json([
    //             'status' => true,
    //             // 'status' => 'success',
    //             'wp_id'  => $propertyResponse->json()['id'],
    //             'message' => 'Propriété transmise à WordPress'
    //         ], 201);
    //     }

    // }


    public function store(Request $request)
    {
        $token = $request->token;
        $user  = $request->userID;

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Token manquant'
            ], 401);
        }

        try {

            // COVER
            $featuredMediaId = $request->hasFile('cover_image')
                ? $this->uploadToWordPress($request->file('cover_image'), $token)
                : null;

            // GALLERY (PARALLEL 🚀)
            $uploadedIds = $request->hasFile('gallery_images')
                ? $this->uploadGallery($request->file('gallery_images'), $token)
                : [];

            $gallery = implode('|', $uploadedIds);

            $meta = [
                "real_estate_property_price_short" => $request->price,
                'real_estate_property_price' => $request->price,
                'real_estate_property_price_postfix' => $request->period ?? 'NUITÉE',
                'real_estate_property_address' => $request->address,
                'real_estate_property_rooms' => $request->bedrooms,
                'real_estate_property_bathrooms' => $request->toilets,
                'real_estate_property_garage' => $request->garages,
                'real_estate_property_other_contact_name' => $request->contact_name,
                "real_estate_property_other_contact_phone" => $request->contact_phone,
                'real_estate_property_images' => $gallery,
                'real_estate_disponibilite' => $request->disponibilite ?? 'Disponible',
                'real_estate_negociations' => $request->negociation,
                'real_estate_property_other_contact_mail' => $request->contact_email,
                'real_estate_property_country' => 'CI',
                'real_estate_property_city' => $request->city ?? 'Abidjan',
                'real_estate_property_size' => $request->area,
                'real_estate_property_land' => $request->land_area,
                'real_estate_property_bedrooms' => $request->bedrooms,
            ];

            $propertyData = [
                'title' => $request->title,
                'content' => "<!-- wp:paragraph --><p>{$request->description}</p><!-- /wp:paragraph -->",
                'status' => 'pending',
                'slug' => Str::slug($request->title),
                'type' => 'property',
                'featured_media' => $featuredMediaId,
                'meta' => $meta,
                'property-status' => 693,
                'author' => $user,
                'property-type' => $request->property_type ?? 21,
                'property-feature' => $request->input('amenities', []),
                'property-city' => $request->city_id,
                'property-neighborhood' => $request->neighborhood_id,
            ];

            $response = $this->wpClient($token)
                ->post('https://biim.ci/wp-json/wp/v2/property', $propertyData);

            if (!$response->successful()) {
                throw new \Exception($response->body());
            }

            return response()->json([
                'status' => true,
                'wp_id'  => $response->json()['id'],
                'message' => 'Propriété transmise à WordPress'
            ], 201);
        } catch (\Throwable $e) {

            Log::error('WP STORE ERROR', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Erreur WordPress',
                'error' => $e->getMessage()
            ], 500);
        }
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
                    "garages" => (int) ($metas['real_estate_property_garages'] ?? 0),

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
    public function eltinsert(Request $request)
    {
        // Récupération des types de propriétés
        $response_type_properties = Http::get(
            'https://biim.ci/wp-json/wp/v2/property-type?per_page=100'
        );

        $type_properties = collect($response_type_properties->json())->map(function ($item) {
            return [
                'id' => $item['id'],
                'title' => $item['name'] ?? null,
            ];
        });



        // Récupération des villes
        $response_city_properties = Http::get(
            'https://biim.ci/wp-json/wp/v2/property-city?per_page=100'
        );
        if (!$response_type_properties->successful()) {
            return response()->json(['error' => 'Erreur API'], 500);
        }
        if (!$response_city_properties->successful()) {
            return response()->json(['error' => 'Erreur API'], 500);
        }
        $city_properties = collect($response_city_properties->json())->map(function ($item) {
            return [
                'id' => $item['id'],
                'title' => $item['name'] ?? null,
            ];
        });


        //Recuperation des features
        $response_features_properties = Http::get(
            'https://biim.ci/wp-json/wp/v2/property-feature?per_page=100'
        );
        if (!$response_features_properties->successful()) {
            return response()->json(['error' => 'Erreur API'], 500);
        }
        $features_properties = collect($response_features_properties->json())->map(function ($item) {
            return [
                'id' => $item['id'],
                'title' => $item['name'] ?? null,
            ];
        });


        // Récupération des quartiers
        $response_neighborhood_properties = Http::get(
            'https://biim.ci/wp-json/wp/v2/property-neighborhood?per_page=100'
        );
        if (!$response_neighborhood_properties->successful()) {
            return response()->json(['error' => 'Erreur API'], 500);
        }
        $neighborhood_properties = collect($response_neighborhood_properties->json())->map(function ($item) {
            return [
                'id' => $item['id'],
                'title' => $item['name'] ?? null,
            ];
        });

        // Récupération des status
        $response_status_properties = Http::get(
            'https://biim.ci/wp-json/wp/v2/property-status?per_page=100'
        );
        if (!$response_status_properties->successful()) {
            return response()->json(['error' => 'Erreur API'], 500);
        }
        $status_properties = collect($response_status_properties->json())->map(function ($item) {
            return [
                'id' => $item['id'],
                'title' => $item['name'] ?? null,
            ];
        });
        

        return response()->json([
            "type_properties" => $type_properties,
            "city_properties" => $city_properties,
            "features_properties" => $features_properties,
            "neighborhood_properties" => $neighborhood_properties,
            "status_properties" => $status_properties,
        ]);
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

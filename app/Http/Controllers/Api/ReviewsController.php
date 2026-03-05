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



class ReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function home(Request $request)
    {
        // $search = $request->search;

        $baseParams = [
            'per_page' => 10,
            '_embed'   => true,
        ];

        // if ($search) {
        //     $baseParams['search'] = $search;
        // }

        // 🔹 1️⃣ Requête principale
        $normalResponse = Http::timeout(15)
            ->retry(2, 200)
            ->get('https://biim.ci/wp-json/wp/v2/property',[
                '_embed' => true,
            ]);

        // 🔹 2️⃣ Requête À LA UNE
        $featuredResponse = Http::timeout(15)
            ->retry(2, 200)
            ->get('https://biim.ci/wp-json/wp/v2/property', [
                'featured' => 1,
                '_embed'   => true,
            ]);

        if (!$normalResponse->successful() || !$featuredResponse->successful()) {
            return response()->json([
                'message' => 'Erreur récupération WordPress'
            ], 500);
        }

        $normalItems   = collect($normalResponse->json());
        $featuredItems = collect($featuredResponse->json());

        // 🔥 FORMAT UNIQUE
        $format = function ($item) {

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

            $contact = $extract('property-type-') === 'luxe'
                ? '+2250715056104'
                : '+2250748044105';

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
                'contact_whatsapp' => $contact,
                'cover_image'  =>
                $item['_embedded']['wp:featuredmedia'][0]['source_url']
                    ?? $item['jetpack_featured_media_url']
                    ?? null,
            ];
        };

        return response()->json([
            'data' => $normalItems
                ->map($format)
                ->sortByDesc('views')
                ->take(5)
                ->values(),

            'a_la_une' => $featuredItems
                ->map($format)
                ->take(4)
                ->values(),
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
                "type" => str_replace('-', ' ', $extract('property-type-')),
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

    public function storeReview(Request $request)
    {
        // Les infos automatiques viennent de l'utilisateur connecté dans Laravel
        // $user = auth()->user();
        $token = $request->header('Authorization') ? str_replace('Bearer ', '', $request->header('Authorization')) : null;
        // $user = $request->name;

        // if (!$token) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Token manquant'
        //     ], 401);
        // }

        $response = Http::withToken($this->$token)
            ->post('https://biim.ci/wp-json/wp/v2/biim_review', [
                'title'   => "Avis de " . $request->author_name . " sur Propriété #" . $request->property_id,
                'content' => $request->comment, // Le commentaire de l'utilisateur
                'status'  => 'pending',         // En attente de validation admin
                'meta'    => [
                    'biim_rating'         => $request->rating, // 1 à 5
                    'biim_customer_name'  => $request->author_name,      // AUTOMATIQUE
                    'biim_customer_email' => $request->email,     // AUTOMATIQUE
                    'biim_property_id'    => $request->property_id,
                    'biim_stay_date'      => Carbon::now(),
                    'biim_verified'       => 'no'              // Par défaut non vérifié
                ]
            ]);

        return $response->json();
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
                    "id_type_propriety" => $item['property-type'],
                    "id_city" => $item['property-city'],
                    "id_neighborhood" => $item['property-neighborhood'],
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

    public function similaires(Request $request)
    {
        $type = $request->query('type');
        $neighborhood = $request->query('neighborhood');
        $price = $request->query('price');

        // 🔥 Construire dynamiquement les paramètres WP
        $queryParams = [
            'per_page' => 20,
            '_embed' => true,
        ];

        // if ($type) {
        //     $queryParams['property-type'] = $type;
        // }

        // if ($neighborhood) {
        //     $queryParams['property-neighborhood'] = $neighborhood;
        // }

        if ($price) {
            $queryParams['property-price'] = $price;
        }

        // ⚠️ WordPress ne filtre pas directement par price meta
        // Donc on ne met PAS property-price ici

        $response = Http::timeout(10)
            ->retry(2, 200)
            ->get('https://biim.ci/wp-json/wp/v2/property', $queryParams);

        if (!$response->successful()) {
            return response()->json([
                "similar_properties" => []
            ], 200);
        }

        $items = collect($response->json());

        // 🔥 Filtrage dynamique côté Laravel
        $results = $items->filter(function ($item) use ($price) {

            $metas = $item['all_metas'] ?? [];
            $itemPrice = (int) ($metas['real_estate_property_price'] ?? 0);

            // Si prix fourni → filtre ±20%
            if ($price) {
                $minPrice = $price * 0.8;
                $maxPrice = $price * 1.2;

                if ($itemPrice < $minPrice || $itemPrice > $maxPrice) {
                    return false;
                }
            }

            return true;
        });

        $final = $results->take(6)->map(function ($item) {

            $metas = $item['all_metas'] ?? [];
            $classList = collect($item['class_list'] ?? []);

            $extract = function ($prefix) use ($classList) {
                $value = $classList->first(fn($c) => str_starts_with($c, $prefix));
                return $value
                    ? str_replace('-', ' ', str_replace($prefix, '', $value))
                    : null;
            };

            return [
                "id" => $item['id'],
                "libelle" => $item['title']['rendered'] ?? '',
                "city" => $extract('property-city-'),
                "neighborhood" => $extract('property-neighborhood-'),
                "price" => (int) ($metas['real_estate_property_price'] ?? 0),
                "rooms" => (int) ($metas['real_estate_property_rooms'] ?? 0),
                "bedrooms" => (int) ($metas['real_estate_property_bedrooms'] ?? 0),
                "bathrooms" => (int) ($metas['real_estate_property_bathrooms'] ?? 0),
                "address" => $metas['real_estate_property_address'] ?? null,
                "availability" => $metas['real_estate_disponibilite'] ?? null,
                "views" => (int) ($metas['real_estate_property_views_count'] ?? 0),
                "contact_whatsapp" => "+2250748044105",
                "cover_image" =>
                $item['_embedded']['wp:featuredmedia'][0]['source_url']
                    ?? $item['jetpack_featured_media_url']
                    ?? null,
            ];
        })->values();

        return response()->json([
            "similar_properties" => $final
        ], 200);
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

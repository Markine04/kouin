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
        $user = auth()->user();

        $token = $request->token;

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Token manquant'
            ], 401);
        }

        try {

            $response = $this->wpClient($token)
                ->post('https://biim.ci/wp-json/wp/v2/biim_review', [
                    'title'   => "Avis de " . $request->author_name . " sur Propriété #" . $request->property_id,
                    'content' => $request->comment,
                    'status'  => 'pending',
                    'biim_rating'         => $request->rating,
                    'biim_customer_name'  => $request->author_name,
                    'biim_customer_email' => $request->email,
                    'biim_property_id'    => $request->property_id,
                    'biim_stay_date'      => Carbon::now(),
                    'biim_verified'       => 'no'
                ]);

            if (!$response->successful()) {
                throw new \Exception($response->body());
            }

            return $response->json();

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

    public function showReview(Request $request)
    {
        $token = $request->token;

        // if (!$token) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Token manquant'
        //     ], 401);
        // }

        $property = $request->property_id;

        try {

            $response = $this->wpClient($token)
                ->get('https://biim.ci/wp-json/wp/v2/biim_review', [
                    'biim_property_id' => $property,
                    '_embed' => true
                ]);

            if (!$response->successful()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erreur récupération WordPress'
                ], $response->status());
            }

            $reviews = collect($response->json())->map(function ($item) {

                $meta = $item['metadata'] ?? [];
                $author = $item['_embedded']['author'][0] ?? [];

                return [
                    'id' => $item['id'],

                    'title' => $item['title']['rendered'] ?? '',

                    'comment' => strip_tags($item['content']['rendered'] ?? ''),

                    'rating' => isset($meta['rating']) ? (int)$meta['rating'] : 0,

                    'author_name' => $meta['customer_name'] ?? '',

                    'author_email' => $meta['customer_email'] ?? '',

                    'date' => $meta['stay_date'] ?? null,

                    // 'date' => $item['date'] ?? null,

                    'avatar' => $author['avatar_urls']['96'] ?? null
                ];
            });

            return response()->json([
                'status' => true,
                'data' => $reviews
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}

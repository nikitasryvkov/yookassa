<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\Controller;
use App\Jobs\ReceiptStatusJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ReceiptWebhookController extends Controller
{
    public function index(
        int $id,
        Request $request,
    )
    {
        $executed = RateLimiter::attempt(
            'receipt webhook',
            $perMinute = 20,
            function() {

            }
        );

        if (! $executed) {
            return response('Too many requests', 429);
        }

        $post = $request->post();
//        if(!isset($post['ecrRegistrationNumber'])) {
//            return response('NotAllowed', 403);
//        }

        ReceiptStatusJob::dispatch($id, $post);
        return response('OK', 200);
    }
}

<?php

namespace App\Jobs;

use App\Models\Receipt\ReceiptStatus;
use App\Models\Receipt\ReceiptStatusWebhookData;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ReceiptStatusJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly int $id ,
        private readonly array $post,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $id = $this->id;
        $post = $this->post;
        Log::info('receipt_status_' . $id , $post);

        ReceiptStatusWebhookData::query()->create([
            'receipt_id' => $id,
            'response' => json_encode($post, JSON_UNESCAPED_UNICODE),
        ]);

        ReceiptStatus::query()->create([
            'receipt_id' => $id,
            'status' => $post['status'],
            'fn_state' => $post['fnState'],
            'failure_info' => json_encode($post['failureInfo'], JSON_UNESCAPED_UNICODE),
            'message' => $post['message'],
            'time_status_changed' => Carbon::parse($post['timeStatusChanged'])->format('Y-m-d H:i:s'),

            'shift_number' => $post['fiscalInfo']['shiftNumber'],
            'check_number' => $post['fiscalInfo']['checkNumber'],
            'kkt_number' => $post['fiscalInfo']['kktNumber'],
            'fn_number' => $post['fiscalInfo']['fnNumber'],
            'fn_doc_number' => $post['fiscalInfo']['fnDocNumber'],
            'fn_doc_mark' => $post['fiscalInfo']['fnDocMark'],
            'date' => $post['fiscalInfo']['date'],
            'sum' => $post['fiscalInfo']['sum'],
            'check_type' => $post['fiscalInfo']['checkType'],
            'qr' => $post['fiscalInfo']['qr'],
            'ecr_registration_number' => $post['fiscalInfo']['ecrRegistrationNumber'],
        ]);
    }
}

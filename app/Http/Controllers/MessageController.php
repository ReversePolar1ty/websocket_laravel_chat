<?php

namespace App\Http\Controllers;

use App\Events\StoreMessageEvent;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\MessageStatus;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $message = Message::create([
                'chat_id' => $data['chat_id'],
                'user_id' => auth()->id(),
                'body' => $data['body'],
            ]);

            foreach ($data['user_ids'] as $user_id) {

                MessageStatus::create([
                    'message_id' => $message->id,
                    'chat_id' => $data['chat_id'] ,
                    'user_id' => $user_id,
                ]);
            }

            broadcast(new StoreMessageEvent($message));
            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
                ]);
        }

        return MessageResource::make($message)->resolve();
    }
}

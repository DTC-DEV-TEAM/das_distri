<?php

namespace App\Http\Controllers;


use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Input;
use DB;
use CRUDBooster;
use App\Chat;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Str;


class ChatController extends Controller
{
    
		public function getComments($id, $to_comment = true) {
			$data = [];

			$item = DB::table('returns_header_retail')
				->where('id', $id)
				->get()
				->first();

			$data['comments'] = DB::table('chats')
				->where('chats.returns_header_retail_id', $id)
				->where('chats.status', 'ACTIVE')
				->select(
					'cms_users.name', 
					'cms_users.id as cms_users_id', 
					'chats.created_at as comment_added_at', 
					'chats.id as comment_id',
					'chats.file_name',
					'chats.message'
				)
				->leftJoin('cms_users', 'chats.created_by', '=', 'cms_users.id')
				->orderBy('comment_added_at', 'ASC')
				->get()
				->toArray();

			$data['new_items_id'] = $id;

			$data['table'] = 'returns_header_retail';

			$data['item_description'] = ($item->return_reference_no);

			$data['to_comment'] = $to_comment;

			return $data;
		}

		public function addComments(Request $request) {
			$comment_content = $request['comment_content'];
			$table = $request['table'];
			$returns_header_id = $request['returns_header_id'];
			$attached_image = $request['attached_image'];
			$action_by = CRUDBooster::myId();
			$time_stamp = date('Y-m-d H:i:s');


			if ($attached_image) {
				$filename_filler = Str::random(10);
				$img_file = $attached_image;
				$filename = date('Y-m-d') . "-$filename_filler." . $img_file->getClientOriginalExtension();
				$image = Image::make($img_file);
				
				$image->resize(1024, 768, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
	
				// Save the resized image to the public folder
				$image->save(public_path('chat_img/' . $filename));
				// Optimize the uploaded image
				$optimizerChain = OptimizerChainFactory::create();
				$optimizerChain->optimize(public_path('chat_img/' . $filename));
			}

			$inserted_id = DB::table('chats')
				->insertGetId([
					$table . '_id' => $returns_header_id,
                    'returns_header_retail_id' => $returns_header_id,
					'message' => $comment_content,
					'file_name' => $filename,
					'created_by' => $action_by,
					'created_at' => $time_stamp,
				]);

			$response = DB::table('chats')
				->where('chats.id', $inserted_id)
				->leftJoin('cms_users', 'chats.created_by', '=', 'cms_users.id')
				->select(
					'cms_users.name', 
					'cms_users.id as cms_users_id', 
					'chats.created_at as comment_added_at', 
					'chats.id as comment_id',
					'chats.file_name',
					'chats.message'
				)
				->get()
				->first();

			$returns_header_retail = DB::table($table)
				->where('id', $returns_header_id)
				->get()
				->first();

			return json_encode([$response]);
		}
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ApiUploadController extends Controller
{
    protected $categoryService;
    public function __construct()
    {
        $this->response['msg'] = "Error!";
        $this->response['success'] = false;
        $this->response['data'] = [];
        $this->response['creation_time'] = time();
    }

    public function upload(Request $request)
    {
        try {
            if (is_array($request->file)) {
                $file = $this->processFile($request->file);
                $this->response['data'] = $this->processUpload($file);
            }else{
                $this->response['data'] = $this->processUpload($request->file);
            }
            $this->response['success'] = true;
            $this->response['msg'] = "Thành công";

            return response()->json($this->response, 200);
        } catch (\Throwable $th) {
            $this->response['msg'] = $th->getMessage();
            return response()->json($this->response, 500);
        }
        
    }

    private function processUpload($file)
    {
        $folder = "/images/" . date('d') . "/" . date('m') . "/" . date("Y");
        $path = Storage::disk('local')->put($folder, $file, 'public');
        return $path;
    }

    private function processFile($file)
    {
        if (is_array($file)) {
            foreach ($file as $item) {
                return $this->processFile($item);
            }
        }else{
            return $file;
        }
    }

}

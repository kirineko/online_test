<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \QCloud_WeApp_SDK\Mysql\Mysql as DB;

class Addcomment extends CI_Controller {
    public function index() {
        $raw_input = $this->input->raw_input_stream;
        $comment = json_decode($raw_input, true);

        try {
            $res = DB::insert('comments', $comment);
            $this->json([
                'code' => 0,
                'data' => [
                    'msg' => 'success'
                ]
            ]);
        } catch (Exception $e) {
            $this->json([
                'code' => -1,
                'data' => [
                    'msg' => '评论失败: ' . $e->getMessage()
                ]
            ]);
        }
    }
}

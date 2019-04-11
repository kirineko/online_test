<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \QCloud_WeApp_SDK\Mysql\Mysql as DB;

class Addbook extends CI_Controller {
    public function index() {
        $raw_input = $this->input->raw_input_stream;
        $arr_input = json_decode($raw_input, true);

        if (!isset($arr_input['isbn']) || !isset($arr_input['openId'])) {
            $this->json([
                'code' => -1,
                'data' => [
                    'msg' => '没有取到ISBN数据'
                ]
            ]);
            return;
        }

        $find_res = DB::select('books', ['*'], ['isbn' => $arr_input['isbn']]);
        if (count($find_res)) {
            $this->json([
                'code' => -1,
                'data' => [
                    'msg' => '图书已存在'
                ]
            ]);
            return;
        }

        $doubanApikey =  $this->config->item('doubanApikey');
        $url = "https://api.douban.com/v2/book/isbn/{$arr_input['isbn']}?apikey={$doubanApikey}";
        $raw_bookinfo = Requests::get($url)->body;
        $bookinfo = json_decode($raw_bookinfo, true);

        $arr_tags = array_map(function($v) {
            return "{$v['title']} {$v['count']}";
        }, $bookinfo['tags']);
        $book_data = [
            'rate' => $bookinfo['rating']['average'],
            'title' => $bookinfo['title'],
            'image' => $bookinfo['image'],
            'alt' => $bookinfo['alt'],
            'publisher' => $bookinfo['publisher'],
            'summary' => $bookinfo['summary'],
            'price' => $bookinfo['price'],
            'tags' => implode(',', $arr_tags),
            'author' => implode(',',  $bookinfo['author']),
            'isbn' => $arr_input['isbn'],
            'openid' => $arr_input['openId']
        ];
        log_message('info', json_encode($book_data, JSON_UNESCAPED_UNICODE));

        try {
            $res = DB::insert('books', $book_data);
            $this->json([
                'code' => 0,
                'data' => [
                    'msg' => 'success',
                    'title' => $book_data['title']
                ]
            ]);
        } catch (Exception $e) {
            $this->json([
                'code' => -1,
                'data' => [
                    'msg' => '新增失败: ' . $e->getMessage()
                ]
            ]);
        }
    }
}

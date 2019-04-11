<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** 
 * url: http://localhost:5757/weapp/bookdetail
 * return book detail
 */
class Bookdetail extends CI_Controller {
    public function index() {
        $id = $this->input->get('id');
        $this->load->database();
        $result = $this->db->query("select books.*, cSessionInfo.user_info from `books`, `cSessionInfo` where books.openid = cSessionInfo.open_id and id = $id");
        $book = $result->result_array()[0];
        $book['tags'] = explode(',', $book['tags']);
        $book['summary'] = explode("\n", $book['summary']);
        $userinfo = json_decode($book['user_info'], true);
        $book['user_info'] = [
            'name' => $userinfo['nickName'],
            'image' => $userinfo['avatarUrl']
        ];
        $this->json([
            'code' => 0,
            'data' => $book
        ]);
        $this->db->query("update books set count = count + 1 where id = $id");
    }
}

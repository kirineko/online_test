<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \QCloud_WeApp_SDK\Mysql\Mysql as DB;

class Commentlist extends CI_Controller {
    public function index() {
        $bookid = $this->input->get('bookid');
        $openid = $this->input->get('openid');
        $this->load->database();
        $query_cond = "select comments.*, cSessionInfo.user_info from `comments`, `cSessionInfo` where comments.openid = cSessionInfo.open_id and ";
        if ($bookid) {
            $query_cond .= "comments.bookid = $bookid";
        } else if ($openid) {
            $query_cond .= "comments.openid = '$openid'";
        }
        $result = $this->db->query($query_cond);
        $comments = $result->result_array();
        $comments = array_map(function($comment){
            $userinfo = json_decode($comment['user_info'], true);
            $comment['title'] = $userinfo['nickName'];
            $comment['image'] = $userinfo['avatarUrl'];
            return $comment;
        }, $comments);
        $this->json([
            'code' => 0,
            'data' => [
                'list' => $comments
            ]
        ]);
    }
}

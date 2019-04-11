<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** 
 * url: http://localhost:5757/weapp/top
 * return book detail
 */
class Top extends CI_Controller {
    public function index() {
        $this->load->database();
        $result = $this->db->query("select id, title, count, image from books order by count desc limit 9");
        $this->json([
            'code' => 0,
            'data' => [
                'list' => $result->result_array()
            ]
        ]);
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Question extends CI_Controller
{
    public function index()
    {
        $this->load->database();
        $num = $this->input->get('num');
        $result = $this->db->from('question')->where('num', $num)->get();
        $result = $result->result_array()[0];
        unset($result['answer']);
        return $this->json_success($result);
    }
}

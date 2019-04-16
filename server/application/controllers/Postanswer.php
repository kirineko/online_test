<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 提交试卷
 * 1. 根据gid从question表中取出所有answer、score、num
 * 2. 组合标准答案
 * 3. 对比提交的答案和标准答案
 * 4. 计算总分
 * 5. 写answers表
 * 6. 返回结果给前端
 */
class Postanswer extends CI_Controller
{
    public function index()
    {
        $this->load->database();
        $raw_input = $this->input->raw_input_stream;
        $result = json_decode($raw_input, true);
        $gid = $result['gid'];
        $query = $this->db->select('num, answer, score')->from('question')->where('gid', $gid)->get();
        $std_answers = $query->result_array();
        $submit_answers = $result['answers'];
        $score_result = $this->_getScore($submit_answers, $std_answers);
        
        $answer_to_db = [
            'gid' => $gid,
            'gname' => $result['gname'],
            'answers' => json_encode($submit_answers, JSON_UNESCAPED_UNICODE),
            'openid' => $result['openid'],
            'total' => $score_result['total'],
            'submit_time' => date("Y-m-d H:i:s") 
        ];
        $this->db->insert('answers', $answer_to_db);
        return $this->json_success($answer_to_db);
    }


    private function _getScore($submit_answers, $std_answers)
    {
        $results = [];
        $wrong_answers = [];
        $total = 0;
        foreach ($std_answers as $std_answer) {
            $num = $std_answer['num'];
            $answer = $std_answer['answer'];
            $score = $std_answer['score'];
            foreach ($submit_answers as $subnum => $subanswer) {
                if ($num == $subnum) {
                    $score_got = $answer == $subanswer ? $score : 0;
                    if ($answer != $subanswer) {
                        $wrong_answers []= $num;
                    }
                    $results[$num] = [
                        'score' => $score_got,
                        'answer' => $answer
                    ];
                    $total += $score_got;
                } 
            }
        }
        $results['wrong_answers'] = $wrong_answers;
        $results['total'] = $total;
        return $results;
    }
}

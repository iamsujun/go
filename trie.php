<?php

class Trie
{
    /**
     * 最小匹配
     */
    const MATCH_MIN = 0;

    /**
     * 最大匹配
     */
    const MATCH_MAX = 1;

    /**
     * 全匹配
     */
    const MATCH_ALL = 2;

    /**
     * 词结尾标识
     */
    const END_KEY = "-";

    /**
     * 词组
     */
    public $dict = array();

    /**
     * 结构
     */
    public $tree = array();

    public function setup($data)
    {
        foreach ($data as $value) {
            $this->add($value);
        }
    }

    private function add_action($str, &$arr)
    {
        $sign = false;
        $pre = &$arr;
        $len = mb_strlen($str);
        for ($i=0; $i < $len; $i++) {
            $key = mb_substr($str, $i, 1);
            if (!isset($pre[$key])) {
                $pre[$key] = "";
            }
            $pre = &$pre[$key];
        }
        if (!isset($pre[self::END_KEY])) {
            $pre[self::END_KEY] = 1;
            $sign = true;
        }
        return $sign;
    }

    public function add($str)
    {
        $re = false;
        if (!isset($this->dict[$str])) {
            $this->dict[$str] = 1;
            $re = $this->add_action($str, $this->tree);
        }
        return $re;
    }

    public function check($str, $type = self::MATCH_MIN)
    {
        $num = 0;
        $arr = array();
        $new_str = $str;
        $len = mb_strlen($str);
        for ($n=0; $n < $len; $n++) {
            $tmp = '';
            $index = $n;
            $pre = $this->tree;
            for ($i=$n; $i < $len; $i++) {
                $num++;
                $key = mb_substr($str, $i, 1);
                if (!isset($pre[$key])) {
                    break;
                }
                $tmp .= $key;
                $pre = &$pre[$key];
                if (isset($pre[self::END_KEY])) {
                    switch ($type) {
                        case self::MATCH_MAX: //最大匹配
                            $arr[$index] = $tmp;
                            $n = $i;
                            break;
                        case self::MATCH_ALL: //匹配所有
                            $arr[] = $tmp;
                            break;
                        default: //最小匹配
                            $arr[$index] = $tmp;
                            $n = $i;
                            break 2;
                    }
                }
            }
        }
        // printf("data:%s, times:%s\n%s", $str, $num, print_r($arr, 1));
        return $arr;
    }
}
$data = array(123,12,12);
$trie = new Trie();
$trie->setup($data);
$trie->add(1);
$trie->add(43);
$trie->add(43);
// printf("%s \n %s", print_r($trie->dict,1), print_r($trie->tree,1));

$re = $trie->check(43678123);
print_r($re);

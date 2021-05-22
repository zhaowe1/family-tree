<?php

namespace FamilyTree\common;

trait Relationship
{
    /**
     * 家谱关系映射
     * （没有完全列出，按需补充）
     * @var array
     */
    public $relationshipData = [
        // f 父亲
        'f' => [
            'name' => ['父亲', '爸爸'],
            'next' => [
                'f' => ['name' => ['爷爷']],
                'm' => ['name' => ['奶奶']],
                'ob' => ['name' => ['伯父']],
                'yb' => ['name' => ['叔父']],
                'os' => ['name' => ['姑母']],
                'ys' => ['name' => ['姑母']],
            ],
        ],
        // m 母亲
        'm' => [
            'name' => ['母亲', '妈妈'],
            'next' => [
                'f' => ['name' => ['姥爷']],
                'm' => ['name' => ['姥姥']],
                'ob' => [
                    'name' => ['舅父'],
                    'next' => [
                        's' => [
                            'name' => ['表兄弟'],
                            // 需要细分比较性别和年龄大小时称呼不同
                            'detail' => ['1' => ['o' => ['表哥'], 'y' => ['表弟']], '2' => ['o' => ['表姐'], 'y' => ['表妹']]]
                        ],
                        'd' => [
                            'name' => ['表兄弟'],
                            'detail' => ['1' => ['o' => ['表哥'], 'y' => ['表弟']], '2' => ['o' => ['表姐'], 'y' => ['表妹']]]
                        ],
                    ],
                ],
                'yb' => [
                    'name' => ['舅父'],
                    'next' => [
                        's' => [
                            'name' => ['表兄弟'],
                            'detail' => ['1' => ['o' => ['表哥'], 'y' => ['表弟']], '2' => ['o' => ['表姐'], 'y' => ['表妹']]]
                        ],
                        'd' => [
                            'name' => ['表兄弟'],
                            'detail' => ['1' => ['o' => ['表哥'], 'y' => ['表弟']], '2' => ['o' => ['表姐'], 'y' => ['表妹']]]
                        ],
                    ],
                ],
                'os' => [
                    'name' => ['姨母'],
                    'next' => [
                        's' => [
                            'name' => ['表兄弟'],
                            'detail' => ['1' => ['o' => ['表哥'], 'y' => ['表弟']], '2' => ['o' => ['表姐'], 'y' => ['表妹']]]
                        ],
                        'd' => [
                            'name' => ['表兄弟'],
                            'detail' => ['1' => ['o' => ['表哥'], 'y' => ['表弟']], '2' => ['o' => ['表姐'], 'y' => ['表妹']]]
                        ],
                    ],
                ],
                'ys' => [
                    'name' => ['姨母'],
                    'next' => [
                        's' => [
                            'name' => ['表兄弟'],
                            'detail' => ['1' => ['o' => ['表哥'], 'y' => ['表弟']], '2' => ['o' => ['表姐'], 'y' => ['表妹']]]
                        ],
                        'd' => [
                            'name' => ['表兄弟'],
                            'detail' => ['1' => ['o' => ['表哥'], 'y' => ['表弟']], '2' => ['o' => ['表姐'], 'y' => ['表妹']]]
                        ],
                    ],
                ],
            ],
        ],
        // s 儿子
        's' => [
            'name' => ['儿子'],
            'next' => [
                's' => ['name' => ['孙子']],
                'd' => ['name' => ['孙女']],
            ],
        ],
        // d 女儿
        'd' => [
            'name' => ['女儿'],
            'next' => [
                's' => ['name' => ['外孙']],
                'd' => ['name' => ['外孙女']],
            ],
        ],
        // h 老公
        'h' => [
            'name' => ['老公'],
        ],
        // w 老婆
        'w' => [
            'name' => ['老婆'],
        ],
        // ob 哥哥
        'ob' => [
            'name' => ['哥哥'],
        ],
        // yb 弟弟
        'yb' => [
            'name' => ['弟弟'],
        ],
        // os 姐姐
        'os' => [
            'name' => ['姐姐'],
        ],
        // 妹妹
        'ys' => [
            'name' => ['妹妹'],
        ],
    ];

    /**
     * 获取称呼文本
     * 因为关系路径一定是最简，无需考虑关系再简化之类的操作
     * @param $relationTag
     * @param $targetSex
     * @param $targetAge
     * @return string
     */
    public function getText($relationTag, $targetSex, $targetAge)
    {
        $text = '';

        // 优先检测是否存在映射关系
        $tempData = $this->relationshipData;
        $endIndex = count($relationTag) - 1;
        foreach ($relationTag as $k => $tag) {
            if (isset($tempData[$tag])) {
                if ($k == $endIndex) {
                    if (isset($tempData[$tag]['detail'][$targetSex][$targetAge])) {
                        $text = $tempData[$tag]['detail'][$targetSex][$targetAge][array_rand($tempData[$tag]['detail'][$targetSex][$targetAge])];
                    } else {
                        $text = $tempData[$tag]['name'][array_rand($tempData[$tag]['name'])];
                    }
                    break;
                } elseif (isset($tempData[$tag]['next'])) {
                    $tempData = $tempData[$tag]['next'];
                    continue;
                }
            }
            break;
        }

        // 没定义预设关系时候的兜底处理
        if (empty($text)) {
            foreach ($relationTag as $tag) {
                $text .= $this->relationshipData[$tag]['name'][array_rand($this->relationshipData[$tag]['name'])] . '的';
            }
            $text = rtrim($text, '的');
        }

        return $text;
    }
}
<?php

namespace FamilyTree\common;

class Family
{
    use Relationship;

    /**
     * 用户数据存储
     * @var array
     */
    public $data = [];

    /**
     * 用户关系存储
     * @var array
     */
    public $graph = [];

    /**
     * 添加用户
     * @param User $user
     */
    public function add(User $user)
    {
        $this->data[$user->id] = $user;

        // 添加夫妻关系
        if ($user->f_id != 0 && $user->m_id != 0) {
            $this->graph[$user->f_id][$user->m_id] = 'w';
            $this->graph[$user->m_id][$user->f_id] = 'h';
        }

        // 添加父女关系
        if ($user->f_id != 0) {
            $this->graph[$user->id][$user->f_id] = 'f';
            if ($user->sex == 1) {
                $this->graph[$user->f_id][$user->id] = 's';
            }
            if ($user->sex == 2) {
                $this->graph[$user->f_id][$user->id] = 'd';
            }
        }

        // 添加母子关系
        if ($user->m_id != 0) {
            $this->graph[$user->id][$user->m_id] = 'm';
            if ($user->sex == 1) {
                $this->graph[$user->m_id][$user->id] = 's';
            }
            if ($user->sex == 2) {
                $this->graph[$user->m_id][$user->id] = 'd';
            }
        }

        // 添加兄弟关系
        foreach ($this->graph as $userEdgeArr) {
            $userEdgeArr = array_filter($userEdgeArr, function ($v) {
                return $v == 's' || $v == 'd';
            });
            if (count($userEdgeArr) >= 2) {
                $brotherArr = array_keys($userEdgeArr);
                $brotherCount = count($brotherArr);
                for ($i = 0; $i < $brotherCount; $i++) {
                    for ($j = $i + 1; $j < $brotherCount; $j++) {

                        $iUser = $this->data[$brotherArr[$i]];
                        $jUser = $this->data[$brotherArr[$j]];

                        if (strtotime($iUser->birthday) <= strtotime($jUser->birthday)) {
                            if ($iUser->sex == 1) {
                                $this->graph[$jUser->id][$iUser->id] = 'ob';
                            } else {
                                $this->graph[$jUser->id][$iUser->id] = 'os';
                            }
                            if ($jUser->sex == 1) {
                                $this->graph[$iUser->id][$jUser->id] = 'yb';
                            } else {
                                $this->graph[$iUser->id][$jUser->id] = 'ys';
                            }
                        } else {
                            if ($iUser->sex == 1) {
                                $this->graph[$jUser->id][$iUser->id] = 'yb';
                            } else {
                                $this->graph[$jUser->id][$iUser->id] = 'ys';
                            }
                            if ($jUser->sex == 1) {
                                $this->graph[$iUser->id][$jUser->id] = 'ob';
                            } else {
                                $this->graph[$iUser->id][$jUser->id] = 'os';
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * BFS最短路径寻址
     * @param $aId
     * @param $bId
     * @return array
     */
    public function BFSPath($aId, $bId)
    {
        $queue = [];
        $dist = [];
        $path = [];

        foreach (array_keys($this->graph) as $node) {
            $dist[$node] = -1;
        }
        $dist[$aId] = 0;

        array_push($queue, $aId);

        while (!empty($queue)) {
            $current = array_shift($queue);
            if (!empty($this->graph[$current])) {
                foreach ($this->graph[$current] as $node => $relation) {
                    if ($dist[$node] === -1) {
                        $dist[$node] = $dist[$current] + 1;
                        $path[$node] = $current;
                        array_push($queue, $node);

                        // 已找到最短路径
                        if ($node == $bId) {
                            $rPath = [$bId];
                            $tempId = $bId;
                            while ($tempId != $aId) {
                                array_unshift($rPath, $path[$tempId]);
                                $tempId = $path[$tempId];
                            }
                            return $rPath;
                        }
                    }
                }
            }
        }
        return [];
    }

    /**
     * 获取两个用户的关系
     * @param $aid
     * @param $bid
     */
    public function getRelation($aid, $bid)
    {
        $path = $this->BFSPath($aid, $bid);
        if (empty($path)) {
            return '没有关系';
        }

        $firstId = $path[0];
        $endId = $path[count($path) - 1];

        $rTag = [];

        for ($i = 0; $i < count($path) - 1; $i++) {
            $rTag[] = $this->graph[$path[$i]][$path[$i + 1]];
        }

        $text = $this->data[$firstId]->name . '喊' . $this->data[$endId]->name;
        $targetSex = $this->data[$bid]->sex;
        if (strtotime(strtotime($this->data[$bid]->birthday) <= strtotime($this->data[$aid]->birthday))) {
            $targetAge = 'o';
        } else {
            $targetAge = 'y';
        }
        return $text . $this->getText($rTag, $targetSex, $targetAge);
    }

    /**
     * 获取可视化图数据
     * @return string
     */
    public function getGraphData()
    {
        $data = ['nodes' => [], 'links' => [], 'categories' => [['name' => '男性'], ['name' => '女性']]];

        foreach ($this->graph as $id => $edgeArr) {
            $data['nodes'][] = [
                'name' => $this->data[$id]->name . '[' . $this->data[$id]->id . ']',
                'value' => $this->data[$id]->sex == User::MAN ? '男 ' . $this->data[$id]->birthday : '女 ' . $this->data[$id]->birthday,
                'symbolSize' => 50,
                'category' => $this->data[$id]->sex == 1 ? 0 : 1,
            ];
            foreach ($edgeArr as $edgeId => $edgeR) {
                $data['links'][] = [
                    'source' => $this->data[$id]->name . '[' . $this->data[$id]->id . ']',
                    'target' => $this->data[$edgeId]->name . '[' . $this->data[$edgeId]->id . ']',
                    'value' => $this->relationshipData[$edgeR]['name'][array_rand($this->relationshipData[$edgeR]['name'])],
                ];
            }
        }

        return json_encode($data);
    }
}
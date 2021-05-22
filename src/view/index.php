<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>家谱管理器</title>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts-nightly@5.1.2-dev.20210512/dist/echarts.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
</head>
<body>

<div class="container">
    <div class="page-header">
        <h2>家谱管理器</h2>
    </div>
    <div class="row">
        <form class="form-inline" action="" method="GET">
            <div class="form-group">
                <label>用户A</label>
                <?php $aid = $_GET['aid'] ?? 0 ?>
                <select class="form-control" name="aid">
                    <option <?php if ($aid == 0) { echo 'selected'; } ?> value="0">请选择</option>
                    <?php foreach ($this->family->data as $user): ?>
                        <option <?php if ($aid == $user->id) { echo 'selected'; } ?> value="<?php echo $user->id ?>"><?php echo $user->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            &nbsp;
            <div class="form-group">
                <label>用户B</label>
                <?php $bid = $_GET['bid'] ?? 0 ?>
                <select class="form-control" name="bid">
                    <option <?php if ($bid == 0) { echo 'selected'; } ?> value="0">请选择</option>
                    <?php foreach ($this->family->data as $user): ?>
                        <option <?php if ($bid == $user->id) { echo 'selected'; } ?> value="<?php echo $user->id ?>"><?php echo $user->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            &nbsp;&nbsp;&nbsp;
            <button type="submit" class="btn btn-primary">计算关系</button>
            <a href="/"><button type="button" class="btn btn-default">重置</button></a>
        </form>
        <?php if (!empty($rText)): ?>
        <div class="alert alert-success" role="alert" style="margin-top: 10px"><?php echo $rText; ?></div>
        <?php endif; ?>
    </div>

    <div class="row text-right" style="margin-bottom: 10px">
        <a href="?op=edit"><button type="button" class="btn btn-success">添加用户</button></a>
        <a href="?op=reset"><button type="button" class="btn btn-warning">重置数据</button></a>
    </div>
    <div class="row">
        <table class="table table-bordered table-hover">
            <tr>
                <th>ID</th>
                <th>姓名</th>
                <th>性别</th>
                <th>生日</th>
                <th>父亲[ID]</th>
                <th>母亲[ID]</th>
                <th>操作</th>
            </tr>
            <?php foreach ($this->family->data as $user): ?>
                <tr>
                    <td><?php echo $user->id ?></td>
                    <td><?php echo $user->name ?></td>
                    <td><?php echo $user->sex == \FamilyTree\common\User::MAN ? '男' : '女'; ?></td>
                    <td><?php echo $user->birthday ?></td>
                    <td><?php echo isset($this->family->data[$user->f_id]) ? $this->family->data[$user->f_id]->name . '[' . $user->f_id . ']' : '未知' ?></td>
                    <td><?php echo isset($this->family->data[$user->m_id]) ? $this->family->data[$user->m_id]->name . '[' . $user->m_id . ']' : '未知' ?></td>
                    <td style="width: 170px">
                        <a href="?op=edit&id=<?php echo $user->id ?>"><button type="button" class="btn btn-info">编辑</button></a>
                        <a href="?op=del&id=<?php echo $user->id ?>"><button type="button" class="btn btn-danger">删除</button></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div id="graph-container" style="height: 800px; margin: 20px"></div>
</div>
</body>
<script type="text/javascript">
    var dom = document.getElementById("graph-container");
    var myChart = echarts.init(dom);
    var app = {};
    var option;
    myChart.showLoading();
    $.getJSON('?op=data', function (graph) {
        myChart.hideLoading();
        graph.nodes.forEach(function (node) {
            node.label = {
                show: node.symbolSize > 30
            };
        });
        option = {
            title: {
                text: '家谱关系图',
                top: 'top',
                left: 'right'
            },
            tooltip: {},
            legend: [{
                // selectedMode: 'single',
                data: graph.categories.map(function (a) {
                    return a.name;
                })
            }],
            animationDuration: 1500,
            animationEasingUpdate: 'quinticInOut',
            series: [
                {
                    name: '家谱关系图',
                    type: 'graph',
                    layout: 'force',
                    data: graph.nodes,
                    links: graph.links,
                    autoCurveness: true,
                    categories: graph.categories,
                    roam: true,
                    label: {
                        position: 'right',
                        formatter: '{b}'
                    },
                    lineStyle: {
                        color: 'source',
                    },
                    edgeLabel: {
                        show: true,
                        formatter: "{c}",
                    },
                    emphasis: {
                        focus: 'adjacency',
                        lineStyle: {
                            width: 10
                        }
                    },
                    force: {
                        gravity: 0.01,
                        edgeLength: 500
                    }
                }
            ]
        };

        myChart.setOption(option);
    });

    if (option && typeof option === 'object') {
        myChart.setOption(option);
    }
</script>
</html>
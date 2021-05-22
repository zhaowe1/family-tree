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
    <form action="" method="post">
        <div class="form-group">
            <label>姓名</label>
            <input type="text" name="name" class="form-control" placeholder="请输入姓名" value="<?php echo $currentUser['name'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label>性别</label>
            <div class="radio">
                <?php if (!isset($currentUser['sex'])) { $currentUser['sex'] = 1; } ?>
                <label>
                    <input type="radio" name="sex" value="1" <?php echo $currentUser['sex'] ==1 ? 'checked' : '' ?>> 男
                </label>
                <label>
                    <input type="radio" name="sex" value="2" <?php echo $currentUser['sex'] ==2 ? 'checked' : '' ?>> 女
                </label>
            </div>
        </div>
        <div class="form-group">
            <label>生日</label>
            <input type="date" name="birthday" class="form-control" value="<?php echo $currentUser['birthday'] ?? date('Y-m-d') ?>">
        </div>
        <div class="form-group">
            <label>父亲ID</label>
            <select name="f_id" class="form-control">
                <?php if (!isset($currentUser['f_id'])) { $currentUser['f_id'] = 0; } ?>
                <?php foreach ($manArr as $u): ?>
                    <?php if (isset($_GET['id']) && $_GET['id'] == $u['id']) { continue; } ?>
                    <option <?php if ($currentUser['f_id'] == $u['id']) { echo 'selected'; } ?> value="<?php echo $u['id'] ?>"><?php echo $u['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>母亲ID</label>
            <select name="m_id" class="form-control">
                <?php if (!isset($currentUser['m_id'])) { $currentUser['m_id'] = 0; } ?>
                <?php foreach ($womanArr as $u): ?>
                    <?php if (isset($_GET['id']) && $_GET['id'] == $u['id']) { continue; } ?>
                    <option <?php if ($currentUser['m_id'] == $u['id']) { echo 'selected'; } ?> value="<?php echo $u['id'] ?>"><?php echo $u['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">提交</button>
            &nbsp;
            <a href="javascript:history.back(-1)"><button type="button" class="btn btn-default">返回</button></a>
        </div>
    </form>
</div>
</body>
</html>
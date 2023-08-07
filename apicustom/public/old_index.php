<?php
date_default_timezone_set('Europe/Kiev');
$pdo = new PDO("mysql:host=172.22.75.8; dbname=cms", "cms-user", "123456");
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $text = $_POST['text'];
    $title = $_POST['title'];
    $date = date('Y-m-d H:i:s');
    $pdo->query("INSERT INTO news (title, text,date) VALUES ('{$title}', '{$text}', '{$date}')");
}
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="" method="post">
    <div>Title:</div>
    <div>
        <input type="text" name="title">
    </div>
    <div>Text: </div>
    <div>
        <textarea name="text">

        </textarea>
    </div>
    <div>
        <button type="submit">Add</button>
    </div>
</form>

<div>
    <h1>News list:</h1>
    <table>
    <?php
        $rowCount = 5;
        $sth1 = $pdo->query('SELECT COUNT(*) FROM news');
        $reqCount = intval($sth1->fetch()[0]);
        $pageNum = $_GET['page'] ?? 1;
        $currentPage = ($pageNum-1) * $rowCount;
        $sth = $pdo->query("SELECT * FROM news LIMIT {$currentPage}, {$rowCount}");

        while($row = $sth->fetch()) :
    ?>
    <tr>
        <td><?=$row['id']?></td>
        <td><?=$row['title']?></td>
        <td><?=$row['date']?></td>
    </tr>
    <?php endwhile;?>
    </table>
    <?php for($i =1; $i<=ceil($reqCount/$rowCount); $i++):?>
    <a href="?page=<?=$i?>"> <?=$i?></a>
    <?php endfor;?>
</div>
</body>
</html>





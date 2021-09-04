<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>

<body>
    <?php

    $dsn = 'mysql:dbname=t****3**db;host=localhost'; //连接mysql数据库的DSN
    $user = 'tb-2****3';
    $password = 'PASSWORD';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = "CREATE TABLE IF NOT EXISTS mission5_1"
        . " ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date char(32),"
        . "pass char(32)"
        . ");";

    //新規投稿はじめ
    if (!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["num"])) {
        if (empty($_POST["pass_nc"])) { //パスワードが空の場合
            $errorpass = "パスワードを入力してください";
        } else { //パスワードが空でない場合
            $sql = $pdo->prepare("INSERT INTO mission5_1 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
            $sql->bindParam(':name', $name, PDO::PARAM_STR);
            $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql->bindParam(':date', $date, PDO::PARAM_STR);
            $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $date = date("Y/m/d H:i:s");
            $pass = $_POST["pass_nc"];
            $sql->execute();
        }
    }
    //新規投稿おわり

    //削除はじめ

    if (!empty($_POST["delete"])) {
        if (empty($_POST["pass_del"])) { //パスワードが空の場合
            $errorpass = "パスワードを入力してください";
        } else { //パスワードが空でない場合
            $sql = 'SELECT * FROM mission5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                if ($_POST["delete"] == $row['id'] && $_POST["pass_del"] == $row['pass']) {
                    $id = ($_POST["delete"]);
                    $sql = 'delete from mission5_1 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                } elseif ($_POST["delete"] == $row['id'] && $_POST["pass_del"] != $row['pass']) {
                    $errorpass = "パスワードが間違っています";
                }
            }
        }
    }

    //削除おわり

    //編集選択はじめ
    if (!empty($_POST["edit"])) {
        if (empty($_POST["pass_edit"])) { //パスワードが空の場合
            $name_edit = " ";
            $comment_edit = " ";
            $errorpass = "パスワードを入力してください";
        } else { //パスワードが空でない場合
            $sql = 'SELECT * FROM mission5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                if ($_POST["edit"] == $row['id'] && $_POST["pass_edit"] == $row['pass']) {
                    $num_edit = $row['id'];
                    $name_edit = $row['name'];
                    $comment_edit = $row['comment'];
                } elseif ($_POST["edit"] == $row['id'] && $_POST["pass_edit"] != $row['pass']) {
                    $errorpass = "パスワードが間違っています";
                    $name_edit = " ";
                    $comment_edit = " ";
                }
            }
        }
    }
    //編集選択おわり

    //編集投稿はじめ

    if (!empty($_POST["name"]) && ($_POST["comment"]) && ($_POST["num"])) {
        if (empty($_POST["pass_nc"])) { //パスワードが空の場合
            $errorpass = "パスワードを入力してください";
        } else { //パスワードが空でない場合
            $sql = 'SELECT * FROM mission5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                if ($_POST["num"] == $row['id'] && $_POST["pass_nc"] == $row['pass']) {
                    $id = $_POST["num"];
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $date = date("Y/m/d H:i:s");
                    $pass = $_POST["pass_nc"];
                    $sql = 'UPDATE mission5_1 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
                    //ここにdateとpassが足りなかった
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                } elseif ($_POST["num"] == $row['id'] && $_POST["pass_nc"] != $row['pass']) {
                    $errorpass = "パスワードが間違っています";
                }
            }
        }
    }

    //編集投稿おわり
    ?>

    <form action="" method="post">
        <input type="text" name="name" placeholder="名前を入力" value='<?php if (!empty($_POST["edit"])) {
                                                                        echo $name_edit;
                                                                    } ?>'><br>
        <input type="text" name="comment" placeholder="コメントを入力" value='<?php if (!empty($_POST["edit"])) {
                                                                            echo $comment_edit;
                                                                        } ?>'><br>
        <input type="password" name="pass_nc" placeholder="パスワードを入力">
        <input type="submit" name="submit"><br>
        <input type="hidden" name="num" value='<?php if (!empty($_POST["edit"])) {
                                                    echo $num_edit;
                                                } ?>'>
        <!--隠す項目-->


        <br><input type="number" name="delete" placeholder="削除対象番号"><br>
        <input type="password" name="pass_del" placeholder="パスワードを入力">
        <input type="submit" name="submit" value="削除"><br>

        <br><input type="number" name="edit" placeholder="編集対象番号"><br>
        <input type="password" name="pass_edit" placeholder="パスワードを入力">
        <input type="submit" name="submit" value="編集">
    </form>


    <?php
    if (!empty($errorpass)) {
        echo $errorpass . "<br>";
    } else {
        echo "<br>";
    }
    $sql = 'SELECT * FROM mission5_1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row) {
        echo $row['id'] . ',';
        echo $row['name'] . ',';
        echo $row['comment'] . ',';
        echo $row['date'] . '<br>';
        echo "<hr>";
    }

    ?>

</body>

</html>
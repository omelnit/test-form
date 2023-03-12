<?php
$users = [];
$user = [];
$currentUserKey = [];
$result = [];

if (str_contains($_POST['email'], '@')) {
    if ($_POST['password'] && $_POST['password'] === $_POST['submitPassword']) {

        if (file_exists('users.json')){
            $file = file_get_contents('users.json');
            $users = json_decode($file, true);
        }

        $currentUserKey = array_search($_POST['email'], array_column($users, 'email'));
        if ($currentUserKey === false) {
            $lastUser = end($users);
            $name = trim($_POST['name']. ' '. $_POST['surname']);
            $user["email"] = $_POST['email'];
            $user["id"] = $lastUser['id'] + 1;
            $user["name"] = $name ? $name : 'Користувач';
            $user["password"] = $_POST['password'];

            if ($user) {
                $users[]=$user;
                file_put_contents('users.json', json_encode($users));
            }
            $log = 'Додано нового користувача: id = '. $user['id'];
            writeToLog($log);

            $result['status'] = 'success';
            $result['message'] = 'Вітаємо! Реєстрація пройшла успішно';

        } else {
            $result['status'] = 'danger';
            $result['message'] = 'Користувач з такою адресою електронної пошти вже існує';
            $log = $result['message']. ' id = '. $users[$currentUserKey]['id'];
            writeToLog($log);
        }


    } else {
        $result['status'] = 'danger';
        $result['message'] = 'Пароль обов\'язкове поле і повинен співпадати з підтвердженням';
    }
} else {
    $result['status'] = 'danger';
    $result['message'] = 'Невірний формат електронної пошти (має містити @)';
}

function writeToLog($data) {
    if (isset($data)){
        $now = new DateTime('now');
        $log = "\n------------------------\n";
        $log .= $now->format("Y-m-d H:i:s:u") . "\n";
        $log .= var_export($data, 1);
        $log .= "\n------------------------\n";
        file_put_contents(/*getcwd() . */'log.log', $log, FILE_APPEND);
        return true;
    }
    return false;
}

echo json_encode($result);

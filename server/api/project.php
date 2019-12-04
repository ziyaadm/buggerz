<?php
    $link = get_db_link();

    require 'slack.php';


    /* if($request['method'] === 'PUT') {
        $update = updateProjects($link, $request);
        $response['body'] = $update;
        send($response);
    } */

    if($request['method'] === 'POST') {
        $user = get_user();
        $body = getBodyInfoPost($request);
        $create = createProject($link, $body, $user);
        //terminal_log($request['body']['users']);
        for($index = 0; $index < count($request['body']['users']); $index++ ) {
            $users = $request['body']['users'][$index];
            //terminal_log($users);
            $linkUserToProject = linkUserToProject($link, $create, $users);
        };
        $response['body'] = $create;
        send($response);
    }

    if ($request['method'] === 'GET') {
        $user = $_GET['userId'];
        $data = getAllUsersProjects($link, $user);
        $response['body'] = $data;
        send($response);
    }
    function get_user(){
        if (isset($_SESSION['user_id'])) {
            $userId  = $_SESSION['user_id'];
            return $userId;
        }
            throw new ApiError("No user exists", 404);
    }

    function getAllUsersProjects($link,$user){
        $query = "SELECT `userProjects`.`projectId`,`projects`.`title` AS `projectTitle`, `projects`.`description` FROM `userProjects` INNER JOIN `projects` ON `projects`.`id` =`userProjects`.`projectId` WHERE `userProjects`.`userId` = $user";
        $res = mysqli_query($link, $query);
        $output = mysqli_fetch_all($res, MYSQLI_ASSOC);
        return $output;
    }
    function getBodyInfoPost($request){

        if (!isset($request['body']['title'])) throw new ApiError("'title' not received", 400);
        if (!isset($request['body']['description'])) throw new ApiError("'description' not received", 400);
        if (!isset($request['body']['users'])) throw new ApiError("'users' not received", 400);

        return [
            'title' => $request['body']['title'],
            'description' => $request['body']['description'],
            'users' => $request['body']['users']
        ];
    }

    function createProject($link, $bodyData, $user) {

        $sql = "INSERT INTO `projects` (`title`, `description`, `createdBy`)
                    VALUES (?, ?, ?)";
        $statement = mysqli_prepare($link, $sql);
        $title = $bodyData['title'];
        $description = $bodyData['description'];
        mysqli_stmt_bind_param($statement, 'ssi', $title, $description, $user);
        mysqli_stmt_execute($statement);
        $insertId = $link->insert_id;

        if(empty($insertId)){
            throw new ApiError ("Fail to insert", 400);
        } else {
        $slackId = getSlackId($link, $user);
        postSlack('#general', "<@$slackId> Has Created A New Project:
    Title: $title
    Description: $description", 'UR4ER5WVA');
            return $insertId;
        }
    }

    function linkUserToProject($link, $create, $user) {

        $sql = "INSERT INTO `userProjects` (`projectId`, `userId`) VALUES ($create, $user)";
        $response = mysqli_query($link, $sql);
        $output = mysqli_fetch_all($response, MYSQLI_ASSOC);
        $insertId = $link->insert_id;
        $slackId = getSlackId($link,$user);
        postSlack($slackId, "<@$slackId>: you have just been linked to a project. View the project tickets at http://localhost:3000/api/tickets?projectId=$create&userId=$user");
        if(empty($insertId)){
            throw new ApiError ("Failed to insert", 400);
        } else {
            return $output;
        }


    }

    /* function updateProjects($link, $request) {
        $updateQuery = "UPDATE `projects` SET ";
        $valueDescription = $request['body']['description'];
        $valueTitle = $request['body']['title'];


        if(!isset($request['body']['projectId'])) {
            throw new ApiError ("No 'projectId' receive");
        } else {
            $valueId =  $request['body']['projectId'];
        }
        if (!isset($request['body']['title']) && !isset($request['body']['description'])) {
            return "Nothing was updated";
        }
        if (isset($request['body']['description'])) {
            $updateQuery = $updateQuery . "`description`='" . $valueDescription . "'";
        }
        if (isset($request['body']['title'])) {
            $updateQuery = $updateQuery . ", `title`='" . $valueTitle . "'";
        }
        $updateQuery = $updateQuery . " WHERE `id`=$valueId" ;
        return $updateQuery;
        $respone = mysqli_query($link, $updateQuery);
        $output = mysqli_fetch_all($respone, MYSQLI_ASSOC);
        return $output;
    } */
?>

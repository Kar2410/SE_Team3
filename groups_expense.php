<?php
require 'includes/init.php';
if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
    $user_data = $user_obj->find_user_by_id($_SESSION['user_id']);
    if($user_data===false){
        header('Location: logout.php');
    }

    //getting username from URL and retrieving from SQL
    if(isset($_GET['id'])){
        $group_data = $group_obj->find_group_members($_GET['id']);
//        if($group_data===false){
//            header('Location: profile.php');
//            exit;
//        }
    }
}
else{
    header('Location: logout.php');
    exit;
}

//username
$uname = $user_data->username;

//all users
// fetch all users except me
$all_users = $user_obj->all_users($_SESSION['user_id']);

//requesting notification number
$get_req_num = $friend_obj->req_notification($_SESSION['user_id'], false);
//total friends
$get_frnd_num = $friend_obj->get_all_friends($_SESSION['user_id'], false);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo  $user_data->username ;?></title>
<link rel="stylesheet" href="./style.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</head>
<body>
<div class="profile_container">
    <div class="inner_profile">
        <div class="img">
            <img src="profile_images/<?php echo $user_data->user_image;?>" alt="Profile image">
        </div>
        <h1><?php echo $user_data->username;?></h1>
    </div>


    <nav>
        <ul>
            <li><a href="profile.php" rel="noopener noreferrer" class="active">Home</a></li>

            <li><a href="expense.php" rel="noopener noreferrer">Add an Expense</a></li>
            <li><a href="balance.php" rel="noopener noreferrer">Balance</a></li>
            <li><a href="groups_create.php" rel="noopener noreferrer">Groups</a></li>

            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Edit
            </button>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="notifications.php" rel="noopener noreferrer">Requests<span class="badge <?php
                    if(
                        $get_req_num > 0){
                        echo 'redBadge';
                    }
                    ?>"><?php echo $get_req_num;?></span></a>
                <a class="dropdown-item" href="friends.php" rel="noopener noreferrer">Friends<span class="badge"><?php echo $get_frnd_num;?></span></a>
                <a class="dropdown-item" href="image_upload.php" rel="noopener noreferrer">Change Pic</a>
                <a class="dropdown-item" href="logout.php" rel="noopener noreferrer">Logout</a>
            </div>
        </ul>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col">
                <div class="login_signup_container groups_container" >
                    <div class="form-group">
                        <form action="" method="POST" name="add_name" id="add_name">
                            <div class="table-responsive">
                                <table><tr><th>Create Group Expense</th></tr></table>

                                <table class="table table-bordered" id="dynamic_field">

                                    <tr>
                                        <td> <label for="expense_name">Expense name:</label></td>
                                        <td><input type="text" name="expense_name" placeholder="Enter Group Name"></td>
                                    </tr>
                                    <tr>
                                        <td><label for="description">Description: </label></td>
                                        <td><input type="text" name="description" placeholder="Expense Description"></td>
                                    </tr>
                                    <tr>
                                        <td><label for="amount">Amount:</label></td>
                                        <td><input type="text" name="amount" placeholder="Enter amount"></td>
                                    </tr>

                                    <tr>
                                        <td>Check box with group members names</td>
                                    </tr>

<!--                                    <tr><td><label for="group_name">Add Group Members: </label></td></tr>-->
<!--                                    <tr>-->
<!--                                        <td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" required></td>-->
<!--                                        <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>-->
<!--                                    </tr>-->
                                </table>
                            </div>
                        </form>

                    </div>

                    <script>
                        $(document).ready(function(){
                            var i=1;
                            $('#add').click(function(){
                                i++;
                                $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" required /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
                            });
                            $(document).on('click', '.btn_remove', function(){
                                var button_id = $(this).attr("id");
                                $('#row'+button_id+'').remove();
                            });
                            $('#submit').click(function(){
                                $.ajax({
                                    url:"",
                                    method:"POST",
                                    data:$('#add_name').serialize(),
                                    success:function(data)
                                    {
                                        //alert(data);
                                        $('#add_name')[0].reset();
                                    }
                                });
                            });
                        });
                    </script>
                </div>

            </div>
            <div class="col">
                <div class="profile_container">
                    <div class="all_users">
                        <h4>Group Members</h4>
                        <?php
                        if($all_users){
                            foreach ($group_data as $item) {
                                foreach ($all_users as $row){
                                    if($row->username === $item){
                                        $uid = $row->id;
                                    }
                                    elseif($uname === $item){
                                        $uid = $user_data->id;
                                    }
                                }

                                echo '<div class="user_box">
                                 <div class="user_info"><span>'.$item.'</span></div>';
                                if($uname !== $item){
                                echo '<span><a href="user_profile.php?id='.$uid.'" class="see_profileBtn">See Profile</a></span>
                               </div>';
                                }
                                elseif ($uname === $item){
                                    echo '</div>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
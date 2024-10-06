<?php

//connection between the code and the database
$host = "localhost";
$username = "root";
$password = '';
$dbname = "company";
$connection = mysqli_connect($host, $username, $password, $dbname);


//Delete Data
if(isset($_GET['delete']))
{
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM employees WHERE id = $id";
    $delete = mysqli_query($connection, $deleteQuery);
    header("Location: index.php");
}

$name = '';
$email = '';
$phone = '';
$gender = '';
$department = '';
$empId = '';
$mode = 'create';

//Update Data
if(isset($_GET['edit']))
{
    $id = $_GET['edit'];
    $selectById = "SELECT * FROM employees WHERE id = $id ";
    $result = mysqli_query($connection, $selectById);
    $row = mysqli_fetch_assoc($result);
    $name =$row ['name'];
    $email =$row ['email'];
    $phone =$row ['phone'];
    $gender =$row ['gender'];
    $department =$row ['department'];
    $empId = $id;
    $mode = 'update';

}

if(isset($_POST['update']))
{
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $department = $_POST['department'];
    $updateQuery = "UPDATE employees SET `name` = '$name', phone = '$phone', email = '$email', gender = '$gender', department = '$department' WHERE id = $empId";
    $update = mysqli_query($connection, $updateQuery);
    header("Location: index.php");
}

//Create Data
if(isset($_POST['submit']))
{
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $department = $_POST['department'];
    $insertQuery = "INSERT INTO employees VALUES (NULL, '$name', '$email', '$phone', '$gender', '$department' )";
    $insert = mysqli_query($connection, $insertQuery);
    header("Location: index.php");
}


//Read Data
$selectQuery = "SELECT * FROM employees";
$search = '';
$message = '';

if(isset($_GET['search']))
{
    $value = $_GET['search'];
    $search = $value;
    $selectQuery = "SELECT * FROM employees WHERE 'name' LIKE '%$value%' OR email LIKE '%$value%' OR department LIKE '%$value%' ";
}

if(isset($_GET['asc']))
{
    //In case there is no data
    if(!isset($_GET['orderBy']))
    {
        $message = "Please Select a column to order by";
    }
    //If there is data
    else
    {
        $order = $_GET['orderBy'];
        $selectQuery = "SELECT * FROM employees ORDER BY $order ASC";
    }
}
if(isset($_GET['desc']))
{
    //In case there is no data
    if(!isset($_GET['orderBy']))
    {
        $message = "Please Select a column to order by";
    }
    //If there is data
    else
    {
        $order = $_GET['orderBy'];
        $selectQuery = "SELECT * FROM employees ORDER BY $order DESC";
    }
}

$select = mysqli_query($connection, $selectQuery);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<style>
    body{
        background-color: #333;
        color: white;
    }
    </style>
<body>
    
    <div class="container py-2">
        <div class="card bg-dark text-light">
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                    <div class="col-mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" value="<?= $name ?>" id="name" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" value="<?= $email ?>" id="email" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" value="<?= $phone ?>" id="phone" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" name="gender" class="form-select">
                            <?php if ($gender == 'male'): ?>
                                <option disabled selected>Choose...</option>
                                <option value="male" selected>Male</option>
                                <option value="female">female</option>
                            <?php elseif($gender == 'female'): ?>
                                <option disabled selected>Choose...</option>
                                <option value="male">Male</option>
                                <option value="female" selected>female</option>
                            <?php else: ?>
                                <option disabled selected>Choose...</option>
                                <option value="male">Male</option>
                                <option value="female">female</option>
                            <?php endif ?>
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" name="department" value="<?= $department ?>" id="department" class="form-control">
                    </div>
                    <div class="col-12">
                        <div class="col-12 text-center">
                            <?php if($mode == 'update'): ?>
                                <button class="btn btn-warning" name="update">UPDATE</button>
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                            <?php else:  ?>
                            <button class="btn btn-primary" name="submit">Submit</button>
                            <?php endif ?>
                        </div>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container py-2">
        <div class="card bg-dark text-light">
            <div class="card-body">
                <h2 class="text-center">filters</h2>
                <form>
                    <div class="mb-3">
                    <label for="search" class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="<?= $search ?>" name="search" id="search">
                        <button class="btn btn-primary">Search</button>
                    </div>
                    </div>
                </form>

                <!-- Key => value
                    orderBy => id -->

                <form>
                    <h5 class="text-danger"><?= $message ?></h5>
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3">
                            <label for="orderBy">Order By</label>
                            <select name="orderBy" id="orderBy" class="form-select">
                                <option disabled selected>Choose...</option>
                                <option value="id">Id</option>
                                <option value="name">Name</option>
                                <option value="gender">Gender</option>
                                <option value="email">email</option>
                                <option value="department">department</option>
                                <option value="phone">phone</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                        <button class="btn btn-info" name="asc">Ascending</button>
                        <button class="btn btn-info" name="desc">descending</button>
                    </div>
                    </div>
                </form>
                <a href="./index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </div>
    

    <div class="container">
        <div class="card bg-dark">
            <table class="table table-dark">
                <thread>
                    <tr>
                        <th>#</th>
                        <th>id</th>
                        <th>name</th>
                        <th>email</th>
                        <th>phone</th>
                        <th>gender</th>
                        <th>department</th>
                    </tr>
                </thread>
            <tbody>
                <!-- Data loop -->
                 <?php foreach($select as $index => $emp): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?=$emp ['name'] ?></td>
                    <td><?= $emp ['email'] ?></td>
                    <td><?= $emp ['phone'] ?></td>
                    <td><?= $emp ['gender'] ?></td>
                    <td><?= $emp ['department'] ?></td>
                    <td>
                       <!--  <form>
                            <input type="text"  name="delete" hidden value="<?= $emp['id']?>">
                            <button class="btn btn-danger">DELETE</button>
                        </form> -->
                        <a href="?edit=<?= $emp['id']?>" class="btn btn-warning">EDIT</a>
                        <a href="?delete=<?= $emp['id']?>" class="btn btn-danger">DELETE</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <!-- End of loop -->
            </tbody>
            </table>
        </div>
    </div>
    
</body>
</html>
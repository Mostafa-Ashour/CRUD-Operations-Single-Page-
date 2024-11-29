<?php
// -------------------- Connecting to Database --------------------

$host = "localhost";
$username = "root";
$password = "";
$dbname = "crud_single_page";

$con = mysqli_connect($host, $username, $password, $dbname);

// -------------------- Inserting into Database --------------------

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $salary = $_POST['salary'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];


    $insert_query = "INSERT INTO `employees` VALUES (NULL, '$name', '$email', '$gender', '$address', $salary, '$phone', '$department')";
    $insert = mysqli_query($con, $insert_query);

    $success_message = '';

    if ($insert) {
        $success_message = 'Employee Added Successfully';
    }
}

// -------------------- Delete an Employee --------------------

// echo "<pre>";
// print_r($_GET);
// echo "</pre>";

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_query = "DELETE FROM `employees` WHERE `id`={$id};";
    $delete = mysqli_query($con, $delete_query);
    if ($delete) {
        header("Location: .\index.php");
    }
}

// -------------------- Edit an Employee's Data --------------------

// Initialize Data with an Empty String,
// Values will be printed in Inputs Tags in The Form
// In Case of Editing: Those Values will get The Values of The Targeted Employee, Then Show Them in Table to Edit Them.
// In Case of Not Editing: The Inputs will be Empty Cause I'm not Editing and They're initialized with an Empty String.

$name = '';
$email = '';
$gender = '';
$address = '';
$salary = '';
$phone = '';
$department = '';

$genders = ["Male", "Female"];
$all_departments = ["IT", "HR", "Accounting", "Software", "Web", "Sales", "Marketing"];

// Flag For Cancel & Update Buttons in Case Of Editing.
$edit_mode = false;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    // Get The Employee Row Depending on The ID
    $select_query = "SELECT * FROM `employees` WHERE `id`={$id};";
    $select = mysqli_query($con, $select_query);
    $row = mysqli_fetch_assoc($select);
    // print_r($row);

    $name = $row['name'];
    $email = $row['email'];
    $gender = $row['gender'];
    $address = $row['address'];
    $salary = $row['salary'];
    $phone = $row['phone'];
    $department = $row['department'];
    $edit_mode = true;

    if (isset($_POST['update'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $salary = $_POST['salary'];
        $phone = $_POST['phone'];
        $department = $_POST['department'];

        $update_query = "UPDATE `employees` SET `name`='$name', `email`='$email', `gender`='$gender', `address`='$address', `salary`=$salary, `phone`='$phone', `department`='$department' WHERE `id`=$id;";
        $update = mysqli_query($con, $update_query);
        if ($update) {
            $edit_mode = false;
            header("Location: .\index.php");
        }
    }
}

// -------------------- Search --------------------
$select_query = "SELECT * FROM `employees`;";
$value = "";

if (isset($_GET['search'])) {
    $value = $_GET['search'];
    $select_query = "SELECT * FROM `employees` WHERE `name` like '%$value%' or `email` like '%$value%' or `gender` like '%$value%' or `address` like '%$value%' or `phone` like '%$value$' or `department` like '%$value%';";
}

if (isset($_GET['asc'])) {
    // print_r($_GET);
    $column = $_GET['column'];
    $select_query = "SELECT * FROM `employees` ORDER BY $column ASC;";
}

if (isset($_GET['desc'])) {
    $column = $_GET['column'];
    $select_query = "SELECT * FROM `employees` ORDER BY $column DESC;";
}

// -------------------- Reading from Database --------------------

$select = mysqli_query($con, $select_query);

// echo "<pre>";
// print_r(mysqli_fetch_all($select));
// echo "</pre>";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #4e4e4e;
            color: white;
        }
    </style>
</head>

<body>


    <div class="container col-9 mt-5">
        <div class="card bg-dark text-light mb-4">
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success text-center">
                    <?= $success_message ?>
                </div>
            <?php endif; ?>
            <!-- Form To Submit New Employee Data -->
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="<?= $name ?>">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" name="email" id="email" class="form-control" value="<?= $email ?>">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-control">
                                <?php foreach ($genders as $gen): ?>
                                    <?php if ($gender == $gen): ?>
                                        <option selected value="<?= $gen ?>"><?= $gen ?></option>
                                    <?php else: ?>
                                        <option value="<?= $gen ?>"><?= $gen ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="address" class="form-label">Address</label>
                            <textarea rows="1" name="address" id="address" class="form-control"><?= $address ?></textarea>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="number" name="salary" id="salary" class="form-control" value="<?= $salary ?>"></input>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="<?= $phone ?>"></input>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="department" class="form-label">Department</label>
                            <select name="department" id="department" class="form-control">
                                <?php foreach ($all_departments as $dep): ?>
                                    <?php if ($department == $dep): ?>
                                        <option value="<?= $dep ?>" selected><?= $dep ?></option>
                                    <?php endif; ?>
                                    <option value="<?= $dep ?>"><?= $dep ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 text-center mb-2">
                            <?php if ($edit_mode): ?>
                                <button class="btn btn-warning" name="update">Update</button>
                                <a href="./index.php" class="btn btn-secondary" name="cancel">Cancel</a>
                            <?php else: ?>
                                <button class="btn btn-primary" name="submit">Submit</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Search -->
        <div class="card bg-dark text-light mb-4">
            <div class="card-body">
                <form>
                    <div class="col-12 mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-lg" placeholder="Search Here">
                            <button class="btn btn-info">Search</button>
                            <?php if (!empty($value)): ?>
                                <a href="./index.php" class="btn btn-secondary">Cancel</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
                <form>
                    <div class="row  mb-3">
                        <div class="col-md-6">
                            <select name="column" class="form-select form-select-lg">
                                <option value="name">Name</option>
                                <option value="email">Email</option>
                                <option value="gender">Gender</option>
                                <option value="salary">Salary</option>
                                <option value="address">Address</option>
                                <option value="department">department</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="<?php echo isset($_GET['asc']) || isset($_GET['desc']) ? "col-4" : "col-6" ?>">
                                    <button class="btn btn-lg w-100" style="background-color: darkmagenta; color: white;" name="asc">ASC</button>
                                </div>
                                <div class="<?php echo isset($_GET['asc']) || isset($_GET['desc']) ? "col-4" : "col-6" ?>">
                                    <button class="btn btn-lg w-100" style="background-color: darkmagenta; color: white;" name="desc">DESC</button>
                                </div>
                                <?php if (isset($_GET['asc']) || isset($_GET['desc'])): ?>
                                    <div class="col-4">
                                        <a href="./index.php" class="btn btn-lg btn-secondary w-100">Cancel</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Table To List All Employees -->
        <div class="card bg-dark text-light mb-4">
            <div class="card-body table-responsive">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Address</th>
                            <th>Salary</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th colspan="2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($select as $idx => $emp): ?>
                            <tr>
                                <td><?= ($idx + 1) ?></td>
                                <td><?= $emp['name'] ?></td>
                                <td><?= $emp['email'] ?></td>
                                <td><?= $emp['gender'] ?></td>
                                <td><?= $emp['address'] ?></td>
                                <td><?= $emp['salary'] ?>$</td>
                                <td><?= $emp['phone'] ?></td>
                                <td><?= $emp['department'] ?></td>
                                <td><a href="?delete=<?= $emp['id'] ?>" class="btn btn-danger">Delete</a></td>
                                <td><a href="?edit=<?= $emp['id'] ?>" class="btn btn-warning">Edit</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
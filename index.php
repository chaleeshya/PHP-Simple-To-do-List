<?php
session_start();

if (!isset($_SESSION['todos'])) {
    $_SESSION['todos'] = [];
}

$msg = "";
$type = "";

// Handle adding
if (isset($_POST['add'])) {
    $item = trim($_POST['item']);
    if ($item !== '') {
        $_SESSION['todos'][] = ['text' => $item, 'done' => false];
        $_SESSION['flash'] = ['msg' => "Item Added Successfully!", 'type' => "success"];
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Handle mark as done
if (isset($_GET['done'])) {
    $index = (int)$_GET['done'];
    if (isset($_SESSION['todos'][$index])) {
        $_SESSION['todos'][$index]['done'] = true;
        $_SESSION['flash'] = ['msg' => "Item Marked as Done!", 'type' => "info"];
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $index = (int)$_GET['delete'];
    if (isset($_SESSION['todos'][$index])) {
        unset($_SESSION['todos'][$index]);
        $_SESSION['todos'] = array_values($_SESSION['todos']); // reindex
        $_SESSION['flash'] = ['msg' => "Item Deleted Successfully!", 'type' => "danger"];
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Flash message
if (isset($_SESSION['flash'])) {
    $msg = $_SESSION['flash']['msg'];
    $type = $_SESSION['flash']['type'];
    unset($_SESSION['flash']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Todo List Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container pt-5">
    <?php if ($msg): ?>
        <div class="alert alert-<?= $type ?>" id="alertMsg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-sm-12 col-md-3"></div>
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <p>Todo List</p>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="item" placeholder="Add a Todo Item" required>
                        </div>
                        <input type="submit" class="btn btn-dark" name="add" value="Add Item">
                    </form>
                    <hr>
                    <?php if (empty($_SESSION['todos'])): ?>
                        <div class="text-center mt-4">
                            <img src="https://img.icons8.com/ios/50/000000/folder-invoices--v1.png"/>
                            <p>Your List is Empty</p>
                        </div>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($_SESSION['todos'] as $index => $todo): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= $index + 1 ?>.</strong>
                                        <span style="<?= $todo['done'] ? 'text-decoration: line-through;' : '' ?>">
                                            <?= htmlspecialchars($todo['text']) ?>
                                        </span>
                                    </div>
                                    <div>
                                        <a href="?done=<?= $index ?>" class="btn btn-sm <?= $todo['done'] ? 'btn-info' : 'btn-outline-info' ?>">Mark as Done</a>
                                        <a href="?delete=<?= $index ?>" class="btn btn-sm btn-outline-danger">Delete</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-3"></div>
    </div>
</div>

<script>
    setTimeout(function(){
        var alert = document.getElementById("alertMsg");
        if(alert){
            alert.style.display = "none";
        }
    }, 3000);
</script>
</body>
</html>

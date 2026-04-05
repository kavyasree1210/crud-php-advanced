<?php
session_start();

if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

// PAGINATION LOGIC
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// SEARCH
$search = "";
if(isset($_GET['search'])) {
    $search = $_GET['search'];
}

// QUERY
if($search != "") {
    $query = "SELECT * FROM posts 
              WHERE title LIKE '%$search%' 
              OR content LIKE '%$search%' 
              LIMIT $start, $limit";

    $count_query = "SELECT COUNT(*) as total FROM posts 
                    WHERE title LIKE '%$search%' 
                    OR content LIKE '%$search%'";
} else {
    $query = "SELECT * FROM posts LIMIT $start, $limit";
    $count_query = "SELECT COUNT(*) as total FROM posts";
}

$result = mysqli_query($conn, $query);

// TOTAL PAGES
$count_result = mysqli_query($conn, $count_query);
$row = mysqli_fetch_assoc($count_result);
$total_posts = $row['total'];
$total_pages = ceil($total_posts / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Posts Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom UI -->
    <style>
        body {
            background: linear-gradient(to right, #eef2f3, #dfe9f3);
        }
        .card {
            border-radius: 15px;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn-custom {
            padding: 5px 10px;
            border-radius: 6px;
        }
    </style>
</head>

<body>

<div class="container mt-5">

<div class="card p-4 shadow">

<h2 class="text-center mb-4">📌 Posts Dashboard</h2>

<h5 class="text-muted">Welcome, <?php echo $_SESSION['user']; ?> 👋</h5>

<!-- Search -->
<form method="GET" class="d-flex mb-3">
    <input type="text" name="search" class="form-control me-2" placeholder="Search posts..." value="<?php echo $search; ?>">
    <button class="btn btn-primary">Search</button>
</form>

<!-- Buttons -->
<div class="mb-3">
    <a href="create.php" class="btn btn-success">+ Add New Post</a>
    <a href="logout.php" class="btn btn-danger float-end">Logout</a>
</div>

<!-- Table -->
<table class="table table-bordered table-hover text-center">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Content</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php
while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['title']}</td>
        <td>{$row['content']}</td>
        <td>
            <a href='edit.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
            <a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm'>Delete</a>
        </td>
    </tr>";
}
?>
</tbody>
</table>

<!-- Pagination -->
<div class="text-center mt-3">

<?php if($page > 1): ?>
<a class="btn btn-outline-primary btn-sm" href="?page=<?php echo $page-1; ?>&search=<?php echo $search; ?>">Prev</a>
<?php endif; ?>

<?php for($i = 1; $i <= $total_pages; $i++): ?>
<a class="btn btn-sm <?php echo ($i == $page) ? 'btn-primary' : 'btn-outline-primary'; ?>" 
   href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>">
   <?php echo $i; ?>
</a>
<?php endfor; ?>

<?php if($page < $total_pages): ?>
<a class="btn btn-outline-primary btn-sm" href="?page=<?php echo $page+1; ?>&search=<?php echo $search; ?>">Next</a>
<?php endif; ?>

</div>

</div>
</div>

</body>
</html>
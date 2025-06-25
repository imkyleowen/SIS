<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include DB connection
require_once("dbConnection.php");

// Handle bulk deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_selected'])) {
    if (!empty($_POST['selected_ids'])) {
        $ids_to_delete = $_POST['selected_ids'];
        $ids_string = implode(",", array_map('intval', $ids_to_delete)); // sanitize IDs

        $delete_query = "DELETE FROM students WHERE id IN ($ids_string)";
        mysqli_query($mysqli, $delete_query);
        header("Location: " . $_SERVER['PHP_SELF']); // Refresh page
        exit();
    }
}

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$result = mysqli_query($mysqli, "SELECT * FROM students ORDER BY id ASC LIMIT $offset, $limit");
$total_result = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM students");
$total_row = mysqli_fetch_assoc($total_result);
$total_students = $total_row['total'];
$total_pages = ceil($total_students / $limit);
?>

<!DOCTYPE html>
<html>
<head>    
    <title>Student List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Student List</h2>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <a href="add.php" class="btn btn-success mb-3">Add New Data</a>

    <form method="POST" action="">
        <table class="table table-bordered table-striped">
            <thead class="table-secondary">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><input type="checkbox" name="selected_ids[]" value="<?= $row['id'] ?>"></td>
                        <td><?= htmlspecialchars($row['sid']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= $row['age'] ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['course']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <button type="submit" id="delete-selected" name="delete_selected" class="btn btn-danger mb-3 d-none" onclick="return confirm('Delete selected records?')">
    Delete Selected
</button>
    </form>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<!-- JS to toggle checkboxes -->
<script>
    const selectAllCheckbox = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
    const deleteBtn = document.getElementById('delete-selected');

    // Show/hide delete button
    function toggleDeleteButton() {
        const anyChecked = [...checkboxes].some(cb => cb.checked);
        deleteBtn.classList.toggle('d-none', !anyChecked);
    }

    // Trigger on individual checkbox change
    checkboxes.forEach(cb => {
        cb.addEventListener('change', toggleDeleteButton);
    });

    // Trigger on "Select All"
    selectAllCheckbox.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        toggleDeleteButton();
    });

    // Initial check in case of back navigation
    toggleDeleteButton();
</script>
</body>
</html>
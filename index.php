
<?php
session_start();

// Initialize the task list
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

// Add a new task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $task = trim($_POST['task']);

    if ($task !== '') {
        $_SESSION['tasks'][] = [
            'name' => $task,
            'completed' => false
        ];
    }
}

// Mark task as completed or pending
if (isset($_GET['complete'])) {
    $id = (int) $_GET['complete'];

    if (isset($_SESSION['tasks'][$id])) {
        $_SESSION['tasks'][$id]['completed'] =
            !$_SESSION['tasks'][$id]['completed'];
    }
}

// Delete a task
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    if (isset($_SESSION['tasks'][$id])) {
        unset($_SESSION['tasks'][$id]);
    }
}

// Redirect to avoid repeating actions on refresh
if ($_SERVER['REQUEST_METHOD'] === 'POST' ||
    isset($_GET['complete']) ||
    isset($_GET['delete'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <h1>Student Task Manager</h1>

    <p class="subtitle">
        Organize your studies. Stay productive.
    </p>

    <form method="POST" action="index.php">
        <input
            type="text"
            name="task"
            placeholder="Enter your task..."
            required
        >

        <button type="submit" name="add_task">
            Add Task
        </button>
    </form>

    <h2>My Tasks</h2>

    <?php if (empty($_SESSION['tasks'])): ?>

        <p class="empty">No tasks added yet. Start by adding one!</p>

    <?php else: ?>

        <ul class="task-list">

            <?php foreach ($_SESSION['tasks'] as $id => $task): ?>

                <li>
                    <span class="<?php echo $task['completed'] ? 'completed' : ''; ?>">
                        <?php echo htmlspecialchars($task['name']); ?>
                    </span>

                    <div class="actions">
                        <a href="?complete=<?php echo $id; ?>">
                            <?php echo $task['completed'] ? 'Undo' : 'Complete'; ?>
                        </a>

                        <a href="?delete=<?php echo $id; ?>"
                           class="delete">
                            Delete
                        </a>
                    </div>
                </li>

            <?php endforeach; ?>

        </ul>

    <?php endif; ?>

    <footer>
    <p>Built with PHP and deployed on Red Hat OpenShift.</p>

    <p>
        Developed by: Esha Bhattacharya
    </p>

    <p>
        GitHub: debugwithE
    </p>
</footer>

</div>

</body>
</html>

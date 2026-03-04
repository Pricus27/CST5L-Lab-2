<?php
include 'logic.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="portfolio.css">
    <title>OnlineCourseEnrollmentSystem</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

    <div class="container py-1">
        <div class="row" style="margin-top: 100px; margin-left: 100px;">

           
            <div class="col-lg-4 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-mortarboard-fill"></i> Enrollment System</h5>
                        
                        <form method="post" action="OnlineCourseEnrollmentSystem.php<?php echo $editId > 0 ? '?edit=' . (int) $editId : ''; ?>">

                            <label for="FirstName" class="form-label">First Name</label>
                            <input
                                id="FirstName"
                                name="first_name"
                                type="text"
                                class="form-control"
                                placeholder="Type your name..."
                                value="<?php echo htmlspecialchars($formFirstName); ?>"
                                required
                            >

                            <label for="LastName" class="form-label">Last Name</label>
                            <input
                                id="LastName"
                                name="last_name"
                                type="text"
                                class="form-control"
                                placeholder="Type your last name..."
                                value="<?php echo htmlspecialchars($formLastName); ?>"
                                required
                            >

                            <label for="EmailAddress" class="form-label">Email Address</label>
                            <input
                                id="EmailAddress"
                                name="email_address"
                                type="email"
                                class="form-control"
                                placeholder="email@example.com"
                                value="<?php echo htmlspecialchars($formEmailAddress); ?>"
                                required
                            >

                            <label for="CourseName" class="form-label">Course Name</label>
                            <input
                                id="CourseName"
                                name="course_name"
                                type="text"
                                class="form-control"
                                placeholder="ex: Computer Science"
                                value="<?php echo htmlspecialchars($formCourseName); ?>"
                                required
                            >

                            <label for="entry_date" class="form-label">Entry Date</label>
                            <input
                                type="date"
                                class="form-control"
                                id="entry_date"
                                name="entry_date"
                                value="<?php echo htmlspecialchars($formEntryDate); ?>"
                                required
                            >

                            <button id="submitBtn" type="submit" class="btn btn-primary w-100 mt-2">
                                <?php echo $editId > 0 ? 'Update Enrollment' : 'Complete Enrollment'; ?>
                            </button>
                            <p id="result" class="mt-3 mb-0"></p>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mb-3" id="EnrollmentList">
                    <div class="card-body p-4">
                        <div class="card shadow-sm">
                            <div class="card-body p-0">

                                <?php if (isset($result) && mysqli_num_rows($result) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Name</th>   
                                                    <th>Course</th>
                                                    <th>Entry Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                                        <td>
                                                            <?php echo htmlspecialchars($row['course_name']); ?>
                                                            <button type="button" class="btn btn-primary btn-sm ms-2"></button>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($row['entry_date']); ?></td>
                                                        <td>
                                                            <a href="OnlineCourseEnrollmentSystem.php?edit=<?php echo (int) $row['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                                            <a href="OnlineCourseEnrollmentSystem.php?delete=<?php echo (int) $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this entry?');">Delete</a>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="p-3 mb-0 text-muted">No enrollments yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>


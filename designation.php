<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Management</title>

      <!-- Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Data Table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }
        nav {
            width: 100%;
        }
        .container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            height: 600px;
            margin-top: 3%;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            border-bottom: 1px solid;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header-container > h3 {
            font-weight: 500;
        }
        .designation-container {
            position: relative;
            width: 100%;
        }
        .action-button {
            display: flex;
            justify-content: center;
        }
        .action-button > button {
            width: 25px;
            height: 25px;
            font-size: 17px;
            display: flex !important;
            justify-content: center;
            align-items: center;
            margin: 0px 2px;
        }
        .dataTables_wrapper .dataTables_info {
            position: absolute !important;
            bottom: 20px !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="home.php" style="margin-left: 20px;">Employee Management System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav mr-auto my-2 my-lg-0 navbar-nav-scroll">
                <li class="nav-item">
                    <a class="nav-link " href="http://localhost/patient-management-system/employee.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="http://localhost/patient-management-system/department.php">Department</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="http://localhost/patient-management-system/designation.php">Designation</a>
                </li>
            </ul>
            <ul class="navbar-nav mr-auto my-2 my-lg-0 navbar-nav-scroll" style="max-height: 100px; margin-left: 70%;">
                <li class="nav-item">
                    <a class="nav-link" href="./index.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

  <main class="container">
  <div class="header-container">
                <h3>Designation</h3>
                <div class="header-buttons">
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addDesignationModal">
                        Add Designation
                    </button>
                </div>
            </div>

            <div class="designation-container">
                <table class="table table-striped table-hover table-sm" id="designationTable">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Designation</th>
                            <th scope="col">Date Added</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            include('./conn/conn.php');

                            $stmt = $conn->prepare("SELECT * FROM tbl_designation");
                            $stmt->execute();

                            $result = $stmt->fetchAll();

                            foreach ($result as $row) {
                                $designationId = $row['tbl_designation_id'];
                                $designation = $row['designation'];
                                $dateAdded = $row['date_added'];
                                ?>

                                <tr>
                                    <td id="designationId-<?= $designationId ?>"><?= $designationId ?></td>
                                    <td id="designation-<?= $designationId ?>"><?= $designation ?></td>
                                    <td id="dateAdded-<?= $designationId ?>"><?= $dateAdded ?></td>
                                    <td>
                                        <div class="action-button">
                                            <button class="btn btn-primary" onclick="updateDesignation(<?= $designationId ?>)">&#128393;</button>
                                            <button class="btn btn-danger" onclick="deleteDesignation(<?= $designationId ?>)">X</button>
                                        </div>
                                    </td>
                                </tr>

                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
    </form>
  </main>

    <!-- Add Modal -->
    <div class="modal fade" id="addDesignationModal" tabindex="-1" aria-labelledby="addDesignation" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content mt-5">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDesignation">Add Designation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./endpoint/add-designation.php" method="POST">
                        <div class="mb-3">
                            <label for="designation" class="form-label">Designation Name:</label>
                            <input type="text" class="form-control" id="designation" name="designation">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateDesignationModal" tabindex="-1" aria-labelledby="updateDesignation" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content mt-5">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateDesignation">Update Designation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./endpoint/update-designation.php" method="POST">
                        <input type="text" class="form-control" id="updateDesignationId" name="designationId" hidden>
                        <div class="mb-3">
                            <label for="designation" class="form-label">Designation Name:</label>
                            <input type="text" class="form-control" id="updateDesignationName" name="designation">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Data Table -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <script>
        $(document).ready( function () {
            $('#designationTable').DataTable();
        });

        // Update designation 
        function updateDesignation(id) {
            $("#updateDesignationModal").modal("show");

            let updateDesignationName = $("#designation-" + id).text();

            $("#updateDesignationId").val(id);
            $("#updateDesignationName").val(updateDesignationName);
         }

        // Delete designation
        function deleteDesignation(id) {
            if (confirm("Do you want to delete this designation?")) {
                window.location = "http://localhost/patient-management-system/endpoint/delete-designation.php?designation=" + id;
            }
        }

    </script>
</body>
</html>

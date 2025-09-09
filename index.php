
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MENTORA - Installation Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .install-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .step-indicator {
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 50px;
            color: white;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .requirement-check {
            display: flex;
            align-items: center;
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
        }
        .requirement-pass {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
        }
        .requirement-fail {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
        }
        .requirement-warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="install-container p-5">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bold text-primary">
                            <i class="fas fa-graduation-cap"></i> MENTORA
                        </h1>
                        <p class="lead">Education Portal Installation</p>
                    </div>

                    <?php
                    // Check if already installed
                    if (file_exists('../config/.installed')) {
                        echo '<div class="alert alert-success text-center">';
                        echo '<i class="fas fa-check-circle fa-2x mb-3"></i><br>';
                        echo '<h4>MENTORA is Already Installed!</h4>';
                        echo '<p>Your education portal is ready to use.</p>';
                        echo '<a href="../index.php" class="btn btn-primary btn-lg">Launch MENTORA</a>';
                        echo '</div>';
                        exit;
                    }

                    // Display errors or success messages
                    if (isset($_GET['error'])):
                    ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($_GET['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle fa-2x mb-3"></i><br>
                            <h4>Installation Successful!</h4>
                            <p><?php echo htmlspecialchars($_GET['success']); ?></p>
                            <a href="../index.php" class="btn btn-primary btn-lg">Launch MENTORA</a>
                        </div>
                    <?php else: ?>

                    <!-- System Requirements Check -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="step-indicator">1</div>
                            <h4 class="mb-0">System Requirements</h4>
                        </div>

                        <?php
                        $phpVersion = version_compare(PHP_VERSION, '7.4.0', '>=');
                        $pdoMysql = extension_loaded('pdo_mysql');
                        $fileinfo = extension_loaded('fileinfo');
                        $uploadsWritable = is_writable('../uploads');
                        $configWritable = is_writable('../config');
                        
                        $allRequirementsMet = $phpVersion && $pdoMysql && $fileinfo && $uploadsWritable && $configWritable;
                        ?>

                        <div class="requirement-check <?php echo $phpVersion ? 'requirement-pass' : 'requirement-fail'; ?>">
                            <i class="fas <?php echo $phpVersion ? 'fa-check text-success' : 'fa-times text-danger'; ?> me-2"></i>
                            PHP Version 7.4+ (Current: <?php echo PHP_VERSION; ?>)
                        </div>

                        <div class="requirement-check <?php echo $pdoMysql ? 'requirement-pass' : 'requirement-fail'; ?>">
                            <i class="fas <?php echo $pdoMysql ? 'fa-check text-success' : 'fa-times text-danger'; ?> me-2"></i>
                            PDO MySQL Extension
                        </div>

                        <div class="requirement-check <?php echo $fileinfo ? 'requirement-pass' : 'requirement-fail'; ?>">
                            <i class="fas <?php echo $fileinfo ? 'fa-check text-success' : 'fa-times text-danger'; ?> me-2"></i>
                            Fileinfo Extension
                        </div>

                        <div class="requirement-check <?php echo $uploadsWritable ? 'requirement-pass' : 'requirement-fail'; ?>">
                            <i class="fas <?php echo $uploadsWritable ? 'fa-check text-success' : 'fa-times text-danger'; ?> me-2"></i>
                            Uploads Directory Writable
                        </div>

                        <div class="requirement-check <?php echo $configWritable ? 'requirement-pass' : 'requirement-fail'; ?>">
                            <i class="fas <?php echo $configWritable ? 'fa-check text-success' : 'fa-times text-danger'; ?> me-2"></i>
                            Config Directory Writable
                        </div>

                        <?php if (!$allRequirementsMet): ?>
                            <div class="alert alert-warning mt-3">
                                <strong>Action Required:</strong> Please fix the requirements above before proceeding.
                                <ul class="mt-2 mb-0">
                                    <?php if (!$uploadsWritable): ?>
                                        <li>Make uploads directory writable: <code>chmod 755 ../uploads</code></li>
                                    <?php endif; ?>
                                    <?php if (!$configWritable): ?>
                                        <li>Make config directory writable: <code>chmod 755 ../config</code></li>
                                    <?php endif; ?>
                                    <?php if (!$pdoMysql): ?>
                                        <li>Enable PDO MySQL extension in php.ini</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Installation Form -->
                    <?php if ($allRequirementsMet): ?>
                    <form method="POST" action="installer.php" id="installForm">
                        <!-- Database Configuration -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-3">
                                <div class="step-indicator">2</div>
                                <h4 class="mb-0">MySQL Database Configuration</h4>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> 
                                <strong>XAMPP Users:</strong> Default settings should work. MySQL host is usually 'localhost', username 'root', and password is empty.
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">MySQL Host</label>
                                    <input type="text" class="form-control" name="db_host" value="localhost" required>
                                    <div class="form-text">Usually 'localhost' for XAMPP</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">MySQL Port</label>
                                    <input type="number" class="form-control" name="db_port" value="3306" required>
                                    <div class="form-text">Default MySQL port is 3306</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Database Name</label>
                                    <input type="text" class="form-control" name="db_name" value="education_portal" required>
                                    <div class="form-text">Will be created if it doesn't exist</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">MySQL Username</label>
                                    <input type="text" class="form-control" name="db_username" value="root" required>
                                    <div class="form-text">Default XAMPP username is 'root'</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">MySQL Password</label>
                                <input type="password" class="form-control" name="db_password" placeholder="Leave empty for XAMPP default">
                                <div class="form-text">XAMPP default MySQL password is usually empty</div>
                            </div>

                            <button type="button" class="btn btn-outline-primary" onclick="testConnection()">
                                <i class="fas fa-plug"></i> Test Database Connection
                            </button>
                            <div id="connectionResult" class="mt-2"></div>
                        </div>

                        <!-- Admin Account Setup -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-3">
                                <div class="step-indicator">3</div>
                                <h4 class="mb-0">Administrator Account</h4>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Admin Username</label>
                                    <input type="text" class="form-control" name="admin_username" value="admin" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Admin Email</label>
                                    <input type="email" class="form-control" name="admin_email" value="admin@mentora.edu" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Admin Password</label>
                                    <input type="password" class="form-control" name="admin_password" required minlength="6">
                                    <div class="form-text">Minimum 6 characters</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="admin_password_confirm" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Admin Full Name</label>
                                <input type="text" class="form-control" name="admin_fullname" value="System Administrator" required>
                            </div>
                        </div>

                        <!-- Installation Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5" onclick="return validateForm()">
                                <i class="fas fa-rocket"></i> Install MENTORA
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function testConnection() {
            const formData = new FormData();
            formData.append('action', 'test_connection');
            formData.append('db_host', document.querySelector('[name="db_host"]').value);
            formData.append('db_port', document.querySelector('[name="db_port"]').value);
            formData.append('db_username', document.querySelector('[name="db_username"]').value);
            formData.append('db_password', document.querySelector('[name="db_password"]').value);
            formData.append('db_name', document.querySelector('[name="db_name"]').value);

            const resultDiv = document.getElementById('connectionResult');
            resultDiv.innerHTML = '<div class="spinner-border spinner-border-sm me-2"></div>Testing connection...';

            fetch('test_connection.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = '<div class="alert alert-success mb-0"><i class="fas fa-check"></i> ' + data.message + '</div>';
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger mb-0"><i class="fas fa-times"></i> ' + data.message + '</div>';
                }
            })
            .catch(error => {
                resultDiv.innerHTML = '<div class="alert alert-danger mb-0"><i class="fas fa-times"></i> Connection test failed: ' + error.message + '</div>';
            });
        }

        function validateForm() {
            const password = document.querySelector('[name="admin_password"]').value;
            const confirmPassword = document.querySelector('[name="admin_password_confirm"]').value;

            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return false;
            }

            if (password.length < 6) {
                alert('Password must be at least 6 characters long!');
                return false;
            }

            return confirm('Are you ready to install MENTORA? This will create the database and set up your admin account.');
        }
    </script>
</body>
</html>

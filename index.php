<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AWS S3 Backup Cost Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }
        form {
            max-width: 600px;
            margin-bottom: 20px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .result {
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            margin-top: 20px;
        }
    </style>
    <script>
        function updateCosts() {
            const backupPreset = document.getElementById('backup_preset').value;
            const bucketPreset = document.getElementById('bucket_preset').value;
            const storageCostInput = document.getElementById('storage_cost');
            const putCostInput = document.getElementById('put_cost');
            const getCostInput = document.getElementById('get_cost');
            const listCostInput = document.getElementById('list_cost');

            const backupPresets = {
                'backup_warm': { storage: 0.05 },
                'backup_air_gapped': { storage: 0.0575 }
            };

            const bucketPresets = {
                's3_standard': { put: 0.005, get: 0.0004, list: 0.005 },
                's3_intelligent_tiering': { put: 0.005, get: 0.0004, list: 0.005 },
                's3_standard_ia': { put: 0.01, get: 0.001, list: 0.01 },
                's3_onezone_ia': { put: 0.01, get: 0.001, list: 0.01 },
                's3_glacier_instant': { put: 0.02, get: 0.01, list: 0.02},
                's3_glacier_flexible': { put: 0.03, get: 0.0004, list: 0.03 },
                's3_glacier_deep_archive': { put: 0.05, get: 0.0004, list: 0.05 }
            };

            if (backupPresets[backupPreset]) {
                storageCostInput.value = backupPresets[backupPreset].storage;
            }

            if (bucketPresets[bucketPreset]) {
                putCostInput.value = bucketPresets[bucketPreset].put;
                getCostInput.value = bucketPresets[bucketPreset].get;
                listCostInput.value = bucketPresets[bucketPreset].list;
            }
        }

        function convertSizeToGB() {
            const size = parseFloat(document.getElementById('size').value);
            const unit = document.getElementById('size_unit').value;

            switch (unit) {
                case 'MB':
                    return size / 1024;
                case 'GB':
                    return size;
                case 'TB':
                    return size * 1024;
                default:
                    return size;
            }
        }
    </script>
</head>
<body>
    <h1>AWS S3 Backup Cost Calculator</h1>
    <form method="POST">
        <label for="size">Total Size:</label>
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="number" id="size" name="size" step="0.01" value="<?= isset($_POST['size']) ? htmlspecialchars($_POST['size']) : 500 ?>" required>
            <select id="size_unit" name="size_unit">
                <option value="GB" <?= isset($_POST['size_unit']) && $_POST['size_unit'] === 'GB' ? 'selected' : '' ?>>GB</option>
                <option value="MB" <?= isset($_POST['size_unit']) && $_POST['size_unit'] === 'MB' ? 'selected' : '' ?>>MB</option>
                <option value="TB" <?= isset($_POST['size_unit']) && $_POST['size_unit'] === 'TB' ? 'selected' : '' ?>>TB</option>
            </select>
        </div>

        <label for="objects">Object Count:</label>
        <input type="number" id="objects" name="objects" value="<?= isset($_POST['objects']) ? htmlspecialchars($_POST['objects']) : 1000000 ?>" required>

        <label for="backup_preset">Backup Storage Tier:</label>
        <select id="backup_preset" name="backup_preset" onchange="updateCosts()">
            <option value="backup_warm" <?= isset($_POST['backup_preset']) && $_POST['backup_preset'] === 'backup_warm' ? 'selected' : '' ?>>Amazon S3 Backup - Warm Storage</option>
            <option value="backup_air_gapped" <?= isset($_POST['backup_preset']) && $_POST['backup_preset'] === 'backup_air_gapped' ? 'selected' : '' ?>>Amazon S3 Backup - Logically Air-Gapped</option>
        </select>

        <label for="bucket_preset">Bucket Storage Class:</label>
        <select id="bucket_preset" name="bucket_preset" onchange="updateCosts()">
            <option value="s3_standard" <?= isset($_POST['bucket_preset']) && $_POST['bucket_preset'] === 's3_standard' ? 'selected' : '' ?>>S3 Standard</option>
            <option value="s3_intelligent_tiering" <?= isset($_POST['bucket_preset']) && $_POST['bucket_preset'] === 's3_intelligent_tiering' ? 'selected' : '' ?>>S3 Intelligent-Tiering</option>
            <option value="s3_standard_ia" <?= isset($_POST['bucket_preset']) && $_POST['bucket_preset'] === 's3_standard_ia' ? 'selected' : '' ?>>S3 Standard-IA</option>
            <option value="s3_onezone_ia" <?= isset($_POST['bucket_preset']) && $_POST['bucket_preset'] === 's3_onezone_ia' ? 'selected' : '' ?>>S3 One Zone-IA</option>
            <option value="s3_glacier_instant" <?= isset($_POST['bucket_preset']) && $_POST['bucket_preset'] === 's3_glacier_instant' ? 'selected' : '' ?>>S3 Glacier Instant Retrieval</option>
            <option value="s3_glacier_flexible" <?= isset($_POST['bucket_preset']) && $_POST['bucket_preset'] === 's3_glacier_flexible' ? 'selected' : '' ?>>S3 Glacier Flexible Retrieval</option>
            <option value="s3_glacier_deep_archive" <?= isset($_POST['bucket_preset']) && $_POST['bucket_preset'] === 's3_glacier_deep_archive' ? 'selected' : '' ?>>S3 Glacier Deep Archive</option>
        </select>

        <label for="runs_per_month">Backups Per Month:</label>
        <input type="number" id="runs_per_month" name="runs_per_month" value="<?= isset($_POST['runs_per_month']) ? htmlspecialchars($_POST['runs_per_month']) : 1 ?>" required>

        <label for="storage_cost">Storage Cost per GB ($):</label>
        <input type="number" id="storage_cost" name="storage_cost" step="0.00001" value="<?= isset($_POST['storage_cost']) ? htmlspecialchars($_POST['storage_cost']) : 0.05 ?>" required>

        <label for="put_cost">PUT Request Cost per 1,000 requests ($):</label>
        <input type="number" id="put_cost" name="put_cost" step="0.00001" value="<?= isset($_POST['put_cost']) ? htmlspecialchars($_POST['put_cost']) : 0.005 ?>" required>

        <label for="get_cost">GET Request Cost per 1,000 requests ($):</label>
        <input type="number" id="get_cost" name="get_cost" step="0.00001" value="<?= isset($_POST['get_cost']) ? htmlspecialchars($_POST['get_cost']) : 0.0004 ?>" required>

        <label for="list_cost">LIST Request Cost per 1,000 requests ($):</label>
        <input type="number" id="list_cost" name="list_cost" step="0.00001" value="<?= isset($_POST['list_cost']) ? htmlspecialchars($_POST['list_cost']) : 0.0005 ?>" required>

        <button type="submit">Calculate</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $size = isset($_POST['size']) ? (float)$_POST['size'] : 0;
        $size_unit = isset($_POST['size_unit']) ? $_POST['size_unit'] : 'GB';
        $objects = isset($_POST['objects']) ? (int)$_POST['objects'] : 0;
        $runs_per_month = isset($_POST['runs_per_month']) ? (int)$_POST['runs_per_month'] : 1;
        $storage_cost = isset($_POST['storage_cost']) ? (float)$_POST['storage_cost'] : 0;
        $put_cost = isset($_POST['put_cost']) ? (float)$_POST['put_cost'] : 0;
        $get_cost = isset($_POST['get_cost']) ? (float)$_POST['get_cost'] : 0;
        $list_cost = isset($_POST['list_cost']) ? (float)$_POST['list_cost'] : 0;

        // Convert size to GB
        switch ($size_unit) {
            case 'MB':
                $size_in_gb = $size / 1024;
                break;
            case 'TB':
                $size_in_gb = $size * 1024;
                break;
            default:
                $size_in_gb = $size;
        }

        $storage_total = $size_in_gb * $storage_cost;
        $put_api_cost = ($objects / 1000) * $put_cost * $runs_per_month;
        $get_api_cost = ($objects / 1000) * $get_cost * $runs_per_month;
        $list_api_cost = ($objects / 1000) * $list_cost * $runs_per_month;
        $api_total = $put_api_cost + $get_api_cost + $list_api_cost;
        $total_cost = $storage_total + $api_total;

        echo "<div class='result'>";
        echo "<h2>Calculation Results</h2>";
        echo "<p><strong>Storage Cost:</strong> $" . number_format($storage_total, 2) . "</p>";
        echo "<p><strong>API Costs:</strong></p>";
        echo "<ul>";
        echo "<li>PUT Requests: $" . number_format($put_api_cost, 2) . "</li>";
        echo "<li>GET Requests: $" . number_format($get_api_cost, 2) . "</li>";
        echo "<li>LIST Requests: $" . number_format($list_api_cost, 2) . "</li>";
        echo "</ul>";
        echo "<p><strong>Total API Cost:</strong> $" . number_format($api_total, 2) . "</p>";
        echo "<p><strong>Total Monthly Cost:</strong> $" . number_format($total_cost, 2) . "</p>";
        echo "</div>";
    }
    ?>
</body>
</html>

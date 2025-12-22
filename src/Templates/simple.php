<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= $charset ?>">
    <title><?= htmlspecialchars($title, ENT_QUOTES, $charset) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        <?= $customCss ?>
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($title, ENT_QUOTES, $charset) ?></h1>
        
        <?php if (!empty($data)): ?>
            <table>
                <?php foreach ($data as $index => $row): ?>
                    <?php if ($index === 0): ?>
                        <thead>
                            <tr>
                                <?php foreach ($row as $cell): ?>
                                    <th><?= htmlspecialchars((string) $cell, ENT_QUOTES, $charset) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                    <?php else: ?>
                        <tr>
                            <?php foreach ($row as $cell): ?>
                                <td><?= htmlspecialchars((string) $cell, ENT_QUOTES, $charset) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Veri bulunamadı.</p>
        <?php endif; ?>
    </div>

    <?php if (!empty($customJs)): ?>
        <script><?= $customJs ?></script>
    <?php endif; ?>
</body>
</html>


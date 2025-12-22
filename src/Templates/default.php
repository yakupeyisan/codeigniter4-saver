<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="<?= $charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, $charset) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .content {
            padding: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        tbody tr {
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .footer {
            text-align: center;
            padding: 1.5rem;
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 0.9rem;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
            }

            tbody tr:hover {
                transform: none;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .header h1 {
                font-size: 1.75rem;
            }

            table {
                font-size: 0.875rem;
            }

            th, td {
                padding: 0.75rem 0.5rem;
            }

            .stats {
                flex-direction: column;
                gap: 1rem;
            }
        }

        <?= $customCss ?>
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?= htmlspecialchars($title, ENT_QUOTES, $charset) ?></h1>
            <p>Oluşturulma: <?= date('d.m.Y H:i:s') ?></p>
        </div>

        <div class="content">
            <?php if (!empty($data)): ?>
                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-value"><?= count($data) - 1 ?></div>
                        <div class="stat-label">Toplam Kayıt</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= !empty($data) ? count($data[0]) : 0 ?></div>
                        <div class="stat-label">Kolon Sayısı</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= date('d.m.Y') ?></div>
                        <div class="stat-label">Tarih</div>
                    </div>
                </div>

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
                <p style="text-align: center; padding: 2rem; color: #666;">Veri bulunamadı.</p>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p>CodeIgniter 4 Saver tarafından oluşturuldu</p>
            <p>© <?= date('Y') ?> - Tüm hakları saklıdır</p>
        </div>
    </div>

    <?php if (!empty($customJs)): ?>
        <script>
            <?= $customJs ?>
        </script>
    <?php endif; ?>
</body>
</html>


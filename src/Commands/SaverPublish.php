<?php

namespace Yakupeyisan\CodeIgniter4Saver\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SaverPublish extends BaseCommand
{
    /**
     * Command grup
     *
     * @var string
     */
    protected $group = 'Saver';

    /**
     * Command adı
     *
     * @var string
     */
    protected $name = 'saver:publish';

    /**
     * Command açıklaması
     *
     * @var string
     */
    protected $description = 'Saver konfigürasyon dosyasını app/Config dizinine kopyalar.';

    /**
     * Command kullanımı
     *
     * @var string
     */
    protected $usage = 'saver:publish';

    /**
     * Command çalıştır
     *
     * @param array $params
     * @return void
     */
    public function run(array $params)
    {
        $source = __DIR__ . '/../Config/Saver.php';
        $destination = APPPATH . 'Config/Saver.php';

        // Config dizini var mı kontrol et
        if (!is_dir(APPPATH . 'Config')) {
            mkdir(APPPATH . 'Config', 0755, true);
        }

        // Dosya zaten var mı?
        if (file_exists($destination)) {
            $overwrite = CLI::prompt('Config dosyası zaten mevcut. Üzerine yazılsın mı?', ['y', 'n']);
            
            if ($overwrite !== 'y') {
                CLI::write('İşlem iptal edildi.', 'yellow');
                return;
            }
        }

        // Dosyayı kopyala
        if (copy($source, $destination)) {
            CLI::write('Konfigürasyon dosyası başarıyla kopyalandı!', 'green');
            CLI::write('Konum: ' . $destination, 'blue');
            CLI::newLine();
            CLI::write('Şimdi app/Config/Saver.php dosyasını düzenleyebilirsiniz.', 'yellow');
        } else {
            CLI::error('Konfigürasyon dosyası kopyalanamadı!');
            CLI::error('Kaynak: ' . $source);
            CLI::error('Hedef: ' . $destination);
        }
    }
}

